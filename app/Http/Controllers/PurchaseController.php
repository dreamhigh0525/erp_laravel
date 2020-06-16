<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Models\Merchandises\Purchase;
use App\Models\Merchandises\receipt_product;
use App\Models\Merchandises\PurchaseProduct;
use App\Models\Product\Product;
use App\Models\Partner\partner_credit;
use App\Models\Partner\res_partner;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Auth;
use App\access_right;
use App\User;
use PDF;

class PurchaseController extends Controller
{
    public function index()
    {
        $access=access_right::where('user_id',Auth::id())->first();
        $group=user::find(Auth::id());
        $purchases = Purchase::join('res_partners', 'purchases.client', '=', 'res_partners.id')
                    ->select('purchases.*', 'res_partners.partner_name')
                    ->orderBy('created_at', 'desc')
                    ->paginate(30);
        return view('purchases.index', compact('access','group','purchases'));
    }

    public function search(Request $request)
    {
        $access=access_right::where('user_id',Auth::id())->first();
        $group=user::find(Auth::id());
        $key=$request->filter;
        $value=$request->value;
        if ($key!=""){
            $purchases = Purchase::join('res_partners', 'purchases.client', '=', 'res_partners.id')
                    ->select('purchases.*', 'res_partners.partner_name')
                    ->orderBy('created_at', 'desc')
                    ->where($key,'like',"%".$value."%")
                    ->paginate(30);
            $purchases ->appends(['filter' => $key ,'value' => $value,'submit' => 'Submit' ])->links();
        }else{
            $purchases = Purchase::join('res_partners', 'purchases.client', '=', 'res_partners.id')
                    ->select('purchases.*', 'res_partners.partner_name')
                    ->orderBy('created_at', 'desc')
                    ->paginate(30);
        }
        return view('purchases.index', compact('access','group','purchases'));
    }

    public function create()
    {
        $access=access_right::where('user_id',Auth::id())->first();
        $group=user::find(Auth::id());
        $partner = res_partner::orderBy('partner_name', 'asc')->get();
        $product = Product::orderBy('name', 'asc')->where('can_be_purchase','1')->get();
        return view('purchases.create', compact('access','group','product','partner'));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'client' => 'required|max:255',
            'purchase_date' => 'required|date_format:Y-m-d',
            'due_date' => 'required|date_format:Y-m-d',
            'discount' => 'required|numeric|min:0',
            'products.*.name' => 'required|max:255',
            'products.*.price' => 'required|numeric|min:1',
            'products.*.qty' => 'required|integer|min:1'
        ]);
 
        $year=date("Y");
        $prefixcode = "BILL-$year-";
        $count = Purchase::all()->count();
        if ($count==0){
            $Purchase_no= "$prefixcode"."000001";
        }else {
            $latestPo = Purchase::orderBy('id','DESC')->first();
            $Purchase_no = $prefixcode.str_pad($latestPo->id + 1, 6, "0", STR_PAD_LEFT);
        }

        $products = collect($request->products)->transform(function($product) {
            $product['total'] = $product['qty'] * $product['price'];
            return new PurchaseProduct($product);
        });

        if($products->isEmpty()) {
            return response()
            ->json([
                'products_empty' => ['One or more Product is required.']
            ], 422);
        }

        $data = $request->except('products'); 
        $data['purchase_no'] = $Purchase_no;
        $data['merchandise'] = Auth::id();
        $data['sub_total'] = $products->sum('total');
        $data['grand_total'] = $data['sub_total'] - $data['discount'];

        $purchases = Purchase::create($data);

        $purchases->products()->saveMany($products);

        return response()
            ->json([
                'created' => 'success',
                'id' => $purchases->id
            ]);
    }

    public function show($id)
    {
        $access=access_right::where('user_id',Auth::id())->first();
        $group=user::find(Auth::id());
        $purchases = Purchase::with('products')->findOrFail($id);
        $receipt = receipt_product::where('purchase_no',$purchases->purchase_no)->first();
        $partner = res_partner::orderBy('partner_name', 'asc')->get();
        return view('purchases.show', compact('access','group','purchases','partner','receipt'));
    }

    public function edit($id)
    {
        $access=access_right::where('user_id',Auth::id())->first();
        $group=user::find(Auth::id());
        $purchase = Purchase::with('products','products.product')->findOrFail($id);
        $purchases = PurchaseProduct::where('purchase_id', $id)->get();
        return view('purchases.edit', compact('access','group','purchase','purchases'));
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'purchase_no' => 'required|alpha_dash|unique:purchases,purchase_no,'.$id.',id',
            'client' => 'required|max:255',
            'purchase_date' => 'required|date_format:Y-m-d',
            'due_date' => 'required|date_format:Y-m-d',
            'discount' => 'required|numeric|min:0',
            'products.*.name' => 'required|max:255',
            'products.*.price' => 'required|numeric|min:1',
            'products.*.qty' => 'required|integer|min:1'
        ]);

        $purchase = Purchase::findOrFail($id);
        $old_total = $purchase->grand_total;

        $products = collect($request->products)->transform(function($product) {
            $product['total'] = $product['qty'] * $product['price'];
            return new PurchaseProduct($product);
        });

        if($products->isEmpty()) {
            return response()
            ->json([
                'products_empty' => ['One or more Product is required.']
            ], 422);
        }

        $data = $request->except('products');
        $data['sub_total'] = $products->sum('total');
        $data['grand_total'] = $data['sub_total'] - $data['discount'];

        $purchase->update($data);

        PurchaseProduct::where('purchase_id', $purchase->id)->delete();

        $purchase->products()->saveMany($products);

        partner_credit::where('purchase_no',$request->purchase_no)->update([
            'total'=>$data['grand_total'],
        ]);

        $partner = res_partner::findOrFail($request->client);
        $oldbalance = $partner->debit_limit;
        $total = $oldbalance - $old_total;
        $new_balance = $data['grand_total'] + $total; 
        $partner->update([
            'debit_limit' => $new_balance,
        ]);

        return response()
            ->json([
                'updated' => "success",
                'id' => $purchase->id
            ]);
    }

    public function destroy($id)
    {
        $purchase = purchase::findOrFail($id);

        PurchaseProduct::where('purchase_id', $purchase->id)
            ->delete();

        $purchase->delete();

        return redirect()
            ->route('purchases.index');
    }

    public function approved($id)
    {
        try{
            $purchase = purchase::findOrFail($id);
            partner_credit::insert([
                'purchase_no'=>$purchase->purchase_no,
                'journal'=>'2',
                'partner_id'=>$purchase->client,
                'purchase_date'=>$purchase->purchase_date,
                'due_date'=>$purchase->due_date,
                'total'=>$purchase->grand_total,
            ]);
            $partner = res_partner::findOrFail($purchase->client);
            $balance = $partner->debit_limit;
            $new_balance = $balance + $purchase->grand_total;
            
            $partner->update([
                'debit_limit' => $new_balance,
            ]);
            $purchase->update([
                'approved'=> True,
                'status'=>"Complete",
            ]);
            return redirect(route('accountmove.purchase',$id));
        }catch (\Exception $e) {
            Toastr::error($e->getMessage(),'Something Wrong');
            // Toastr::error('Check In Error!','Something Wrong');
            return redirect()->back();
        }
    }

    public function Report()
    {
        $month = date('m');
        $year = date('Y');
        $access=access_right::where('user_id',Auth::id())->first();
        $group=user::find(Auth::id());
        $income=purchase::whereMonth('purchase_date', '=', $month)->whereYear('purchase_date', '=', $year)->sum('grand_total');
        $unpaid=purchase::where('paid','0')->whereMonth('purchase_date', '=', $month)->whereYear('purchase_date', '=', $year)->count();
        $notvalidate=purchase::where('approved','0')->whereMonth('purchase_date', '=', $month)->whereYear('purchase_date', '=', $year)->count();
        $purchases = purchase::join('res_partners', 'purchases.client', '=', 'res_partners.id')
                            ->join('hr_employees', 'purchases.merchandise', '=', 'hr_employees.user_id')
                            ->join('partner_credit', 'purchases.purchase_no', '=', 'partner_credit.purchase_no')
                            ->select('purchases.*', 'partner_credit.payment','partner_credit.status','res_partners.partner_name','hr_employees.employee_name')
                            ->orderBy('created_at', 'desc')
                            ->whereMonth('purchases.purchase_date', '=', $month)->whereYear('purchases.purchase_date', '=', $year)
                            ->paginate(10);
        return view('purchases.report', compact('access','group','income','unpaid','notvalidate','purchases'));
    }

    public function print_pdf($id)
    {
        $access=access_right::where('user_id',Auth::id())->first();
        $group=user::find(Auth::id());
        $purchase = purchase::with('products','vendor')->findOrFail($id);
    	$pdf = PDF::setOptions(['dpi' => 150, 'defaultFont' => 'sans-serif'])
            ->loadview('reports.purchases.purchase_pdf', compact('access','group','purchase'));
    	return $pdf->stream();
    }

    public function report_print(){
        $month = date('m');
        $year = date('Y');
        $monthName = date("F", mktime(0, 0, 0, $month, 10));
        $access=access_right::where('user_id',Auth::id())->first();
        $group=user::find(Auth::id());
        $income=purchase::whereMonth('purchase_date', '=', $month)->whereYear('purchase_date', '=', $year)->sum('grand_total');
        $unpaid=purchase::where('paid','0')->whereMonth('purchase_date', '=', $month)->whereYear('purchase_date', '=', $year)->count();
        $notvalidate=purchase::where('approved','0')->whereMonth('purchase_date', '=', $month)->whereYear('purchase_date', '=', $year)->count();
        $purchases = purchase::join('res_partners', 'purchases.client', '=', 'res_partners.id')
                            ->join('hr_employees', 'purchases.merchandise', '=', 'hr_employees.user_id')
                            ->join('partner_credit', 'purchases.purchase_no', '=', 'partner_credit.purchase_no')
                            ->select('purchases.*', 'partner_credit.payment','partner_credit.status','res_partners.partner_name','hr_employees.employee_name')
                            ->orderBy('created_at', 'desc')
                            ->whereMonth('purchases.purchase_date', '=', $month)->whereYear('purchases.purchase_date', '=', $year)
                            ->paginate(10);
        $pdf = PDF::setOptions(['dpi' => 150, 'defaultFont' => 'sans-serif'])
                ->loadview('reports.purchases.purchase_report_pdf', compact('monthName','year','income','unpaid','notvalidate','purchases'));
                return $pdf->stream();
    }
}
