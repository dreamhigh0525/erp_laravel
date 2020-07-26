<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;
use App\Models\Customer\customer_dept;
use App\Models\Customer\res_customer;
use App\Models\Sales\Invoice;
use App\Models\Sales\InvoiceProduct;
use App\Addons\Sales\Models\sales_order_product;
use App\Models\Product\Product;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Auth;
use App\access_right;
use App\User;
use PDF;
use Sale;

class InvoiceController extends Controller
{
    public function calculate_code()
    {
        $year=date("Y");
        $month=date("m");
        $prefixcode = "INV/$year/$month/";
        $count = Invoice::where('invoice_no','like',"%".$prefixcode."%")->count();
        if ($count==0){
            return "$prefixcode"."000001";
        }else {
            return $prefixcode.str_pad($count + 1, 6, "0", STR_PAD_LEFT);
        }
    }

    public function index()
    {
        $invoices = Invoice::with('partner')
                    ->join('res_customers', 'invoices.client', '=', 'res_customers.id')
                    ->select('invoices.*', 'res_customers.name')
                    ->orderBy('created_at', 'desc')
                    ->paginate(30);
        return view('invoices.index', compact('invoices'));
    }

    public function search(Request $request)
    {
        $key=$request->filter;
        $value=$request->value;
        if ($key!=""){
            $invoices = Invoice::join('res_customers', 'invoices.client', '=', 'res_customers.id')
                    ->select('invoices.*', 'res_customers.name')
                    ->orderBy('created_at', 'desc')
                    ->where($key,'like',"%".$value."%")
                    ->paginate(30);
            $invoices ->appends(['filter' => $key ,'value' => $value,'submit' => 'Submit' ])->links();
        }else{
            $invoices = Invoice::join('res_customers', 'invoices.client', '=', 'res_customers.id')
                    ->select('invoices.*', 'res_customers.name')
                    ->orderBy('created_at', 'desc')
                    ->paginate(30);
        }
        return view('invoices.index', compact('invoices'));
    }

    public function create()
    {
        $customer = res_customer::orderBy('name', 'asc')->get();
        $product = Product::orderBy('name', 'asc')->where('can_be_sold','1')->get();
        return view('invoices.create', compact('product','customer'));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'client' => 'required|max:255',
            'invoice_date' => 'required|date_format:Y-m-d',
            'due_date' => 'required|date_format:Y-m-d',
            'discount' => 'required|numeric|min:0',
            'products.*.name' => 'required|max:255',
            'products.*.price' => 'required|numeric|min:1',
            'products.*.qty' => 'required|integer|min:1'
        ]);
        $invoice_no = $this->calculate_code();

        $products = collect($request->products)->transform(function($product) {
            $product['total'] = $product['qty'] * $product['price'];
            return new InvoiceProduct($product);
        });

        if($products->isEmpty()) {
            return response()
            ->json([
                'products_empty' => ['One or more Product is required.']
            ], 422);
        }

        $data = $request->except('products');
        $data['invoice_no'] = $invoice_no;
        $data['sales'] = Auth::id();
        $data['sub_total'] = $products->sum('total');
        $data['grand_total'] = $data['sub_total'] - $data['discount'];

        $invoice = Invoice::create($data);

        $invoice->products()->saveMany($products);

        return response()
            ->json([
                'created' => 'success',
                'id' => $invoice->id
            ]);
    }

    public function show($id)
    {
        $invoice = Invoice::with('products')->findOrFail($id);
        $customer = res_customer::orderBy('name', 'asc')->get();
        return view('invoices.show', compact('invoice','customer'));
    }

    public function edit($id)
    {
        $invoice = Invoice::where('id', $id)->with('products', 'products.product')->first();
        $invoices = InvoiceProduct::where('invoice_id', $id)->get();
        // dump($invoice);
        return view('invoices.edit', compact('invoice','invoices'));
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'invoice_no' => 'required|alpha_dash|unique:invoices,invoice_no,'.$id.',id',
            'client' => 'required|max:255',
            'invoice_date' => 'required|date_format:Y-m-d',
            'due_date' => 'required|date_format:Y-m-d',
            'discount' => 'required|numeric|min:0',
            'products.*.name' => 'required|max:255',
            'products.*.price' => 'required|numeric|min:1',
            'products.*.qty' => 'required|integer|min:1'
        ]);

        $invoice = Invoice::findOrFail($id);
        $old_total = $invoice->grand_total;

        $products = collect($request->products)->transform(function($product) {
            $product['total'] = $product['qty'] * $product['price'];
            return new InvoiceProduct($product);
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

        $invoice->update($data);

        InvoiceProduct::where('invoice_id', $invoice->id)->delete();

        $invoice->products()->saveMany($products);

        customer_dept::where('invoice_no',$request->invoice_no)->update([
            'total'=>$data['grand_total'],
        ]);

        $partner = res_customer::findOrFail($request->client);
        $oldbalance = $partner->debit_limit;
        $total = $oldbalance - $old_total;
        $new_balance = $data['grand_total'] + $total; 
        $partner->update([
            'debit_limit' => $new_balance,
        ]);

        return response()
            ->json([
                'updated' => "success",
                'id' => $invoice->id
            ]);
    }

    public function destroy($id)
    {
        $invoice = Invoice::findOrFail($id);

        InvoiceProduct::where('invoice_id', $invoice->id)
            ->delete();

        $invoice->delete();

        return redirect()
            ->route('invoices.index');
    }

    public function approved($id)
    {
        try{
            $invoice = invoice::findOrFail($id);
            $customer = res_customer::findOrFail($invoice->client);
            $journal = $customer->receivable_account;
            $balance = $customer->debit_limit;
            $new_balance = intval($balance) + intval($invoice->grand_total);
            customer_dept::insert([
                'invoice_no'=>$invoice->invoice_no,
                'journal'=>$journal,
                'customer_id'=>$invoice->client,
                'invoice_date'=>$invoice->invoice_date,
                'due_date'=>$invoice->due_date,
                'total'=>$invoice->grand_total,
            ]);
            $customer->update([
                'debit_limit' => $new_balance,
            ]);
            $invoice->update([
                'approved'=> True,
                'status'=>"Complete",
            ]);
            return redirect(route('accountmove.invoice',$id));
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
        $income=invoice::whereMonth('invoice_date', '=', $month)->whereYear('invoice_date', '=', $year)->sum('grand_total');
        $unpaid=invoice::where('paid','0')->whereMonth('invoice_date', '=', $month)->whereYear('invoice_date', '=', $year)->count();
        $notvalidate=invoice::where('approved','0')->whereMonth('invoice_date', '=', $month)->whereYear('invoice_date', '=', $year)->count();
        $invoices = Invoice::join('res_customers', 'invoices.client', '=', 'res_customers.id')
                            ->join('hr_employees', 'invoices.sales', '=', 'hr_employees.user_id')
                            ->join('customer_debt', 'invoices.invoice_no', '=', 'customer_debt.invoice_no')
                            ->select('invoices.*', 'customer_debt.payment','customer_debt.status','res_customers.name','hr_employees.employee_name')
                            ->orderBy('created_at', 'desc')
                            ->whereMonth('invoices.invoice_date', '=', $month)->whereYear('invoices.invoice_date', '=', $year)
                            ->paginate(10);
        return view('invoices.report', compact('income','unpaid','notvalidate','invoices'));
    }

    public function print_pdf($id)
    {
        $invoice = Invoice::with('products','customer')->findOrFail($id);
        // dd($invoice);
    	$pdf = PDF::setOptions(['dpi' => 150, 'defaultFont' => 'sans-serif'])
            ->loadview('reports.sales.invoice_pdf', compact('invoice'));
    	return $pdf->stream();
    }

    public function print_report(){
        $month = date('m');
        $year = date('Y');
        $monthName = date("F", mktime(0, 0, 0, $month, 10));
        $income=invoice::whereMonth('invoice_date', '=', $month)->whereYear('invoice_date', '=', $year)->sum('grand_total');
        $unpaid=invoice::where('paid','0')->whereMonth('invoice_date', '=', $month)->whereYear('invoice_date', '=', $year)->count();
        $notvalidate=invoice::where('approved','0')->whereMonth('invoice_date', '=', $month)->whereYear('invoice_date', '=', $year)->count();
        $invoices = Invoice::join('res_customers', 'invoices.client', '=', 'res_customers.id')
                            ->join('hr_employees', 'invoices.sales', '=', 'hr_employees.user_id')
                            ->join('customer_debt', 'invoices.invoice_no', '=', 'customer_debt.invoice_no')
                            ->select('invoices.*', 'customer_debt.payment','customer_debt.status','res_customers.name','hr_employees.employee_name')
                            ->orderBy('created_at', 'desc')
                            ->whereMonth('invoices.invoice_date', '=', $month)->whereYear('invoices.invoice_date', '=', $year)
                            ->paginate(10);
            $pdf = PDF::setOptions(['dpi' => 150, 'defaultFont' => 'sans-serif'])
            ->loadview('reports.sales.invoice_report_pdf', compact('monthName','year','invoices'));
        return $pdf->stream();
    }

    public function payment_date($value)
    {
        switch ($value) {
            case 1:
                returndate('Y-m-d H:i:s');
                break;
            case 2:
                $Date = date('Y-m-d H:i:s');
                return date('Y-m-d', strtotime($Date. ' + 15 days'));
                break;
            case 3:
                $Date = date('Y-m-d H:i:s');
                return date('Y-m-d', strtotime($Date. ' + 21 days'));
                break;
            case 4:
                $Date = date('Y-m-d H:i:s');
                return date('Y-m-d', strtotime($Date. ' + 30 days'));
                break;
            case 5:
                $Date = date('Y-m-d H:i:s');
                return date('Y-m-d', strtotime($Date. ' + 45 days'));
                break;
            case 6:
                $Date = date('Y-m-d H:i:s');
                return date('Y-m-d', strtotime($Date. ' + 2 Month'));
                break;
            case 7:
                $Date = date('Y-m-d H:i:s');
                return date("Y-m-t", strtotime($Date));
                break;
            default:
                return date('Y-m-d H:i:s');;
        }
    }

    public function wizard_create($id)
    {
        $invoice_no = $this->calculate_code();
        $orders = Sale::getSales($id);
        $orders_line = Sale::getSalesLine($id);
        $partner = res_customer::findOrFail($orders->customer);
        $address = "$partner->street,$partner->zip,$partner->city";
        $due_date = $this->payment_date($partner->payment_terms);
        $inv = Invoice::insertGetId([   
            'invoice_no'=>$invoice_no,
            'invoice_date'=>date('Y-m-d H:i:s'),
            'due_date'=>$due_date,
            'client'=>$orders->customer,
            'client_address'=>$address,
            'title'=>$orders->order_no,
            'sales'=>Auth::id(),
            'sub_total'=>$orders->sub_total,
            'discount'=>$orders->discount,
            'grand_total'=>$orders->grand_total,
            'created_at'=>date('Y-m-d H:i:s'),
            'updated_at'=>date('Y-m-d H:i:s'),
        ]);
        foreach($orders_line as $e => $data){
            $bill_line = InvoiceProduct::create([
                'invoice_id'=>$inv,
                'name'=>$data->name,
                'qty'=>$data->qty,
                'price'=>$data->price,
                'total'=>$data->total,
            ]);
        }
        echo $invoice_no;
        $orders->update([
            'invoice'=> True,
        ]);
        return redirect()->route('invoices.show',$inv);    
    }
}
 