<?php

namespace App\Http\Controllers;

use App\access_right;
use App\User;
use App\Models\Merchandises\Purchase;
use App\Models\Merchandises\receipt_product;
use App\Models\Merchandises\return_purchase;
use App\Models\Product\Product;
use App\Models\Product\stock_move;
use App\Models\Product\stock_valuation;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReceiptProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $access=access_right::where('user_id',Auth::id())->first();
        $group=user::find(Auth::id());
        $receipt = receipt_product::orderBy('created_at','DESC')->paginate(25);
        return view('receipt.index', compact('access','group','receipt'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function return($id)
    {
        $access=access_right::where('user_id',Auth::id())->first();
        $group=user::find(Auth::id());
        $purchase = purchase::where('purchase_no', $id)->with('products','vendor', 'products.product')->first();
        $receipt = receipt_product::where('purchase_no', $id)->first();
        // dump($purchase);
        return view('return-po.return', compact('access','group','purchase','receipt'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store($id)
    {
        try {
            $year=date("Y");
            $prefixcode = "WH-IN-$year-";
            $count = receipt_product::all()->count();
            if ($count==0){
                $receipt_no= "$prefixcode"."000001";
            }else {
                $latestPo = receipt_product::orderBy('id','DESC')->first();
                $receipt_no = $prefixcode.str_pad($latestPo->id + 1, 6, "0", STR_PAD_LEFT);
            }
            $purchase = purchase::findOrFail($id);
            receipt_product::insert([
                'receipt_no'=>$receipt_no,
                'purchase_no'=>$purchase->purchase_no, 
                'receipt_date'=>date('Y-m-d H:i:s'),
                'created_at'=>date('Y-m-d H:i:s'),
                'updated_at'=>date('Y-m-d H:i:s'),
            ]);

            // insert  Stock Move
            $receipt_product = receipt_product::where('receipt_no',$receipt_no)->first();
            foreach ($receipt_product->po->products as $data){
                $product = Product::find($data->name);
                stock_move::insert([
                    'product_id'=>$data->name,
                    'quantity'=>$data->qty,
                    'location_id'=>$id, 
                    'location_destination'=>$product->location,
                    'partner_id'=>$purchase->client,
                    'type'=>'Purchase',
                    'reference'=>$receipt_no,
                    'create_uid'=>Auth::id(),
                    'created_at'=>date('Y-m-d H:i:s'),
                ]);
            }
            $purchase->update([
                'receipt'=> True,
            ]);
            return redirect(route('receipt.show',$purchase->purchase_no));
        } catch (\Exception $e) {
            Toastr::error($e->getMessage(),'Something Wrong');
            // Toastr::error('Check In Error!','Something Wrong');
            return redirect()->back();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\receipt_product  $receipt_product
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $access=access_right::where('user_id',Auth::id())->first();
        $group=user::find(Auth::id());
        $receipt = receipt_product::with('po')->where('purchase_no',$id)->first();
        $return_po= return_purchase::where('purchase_no',$id)->count();
        return view('receipt.show', compact('access','group','receipt','return_po'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\receipt_product  $receipt_product
     * @return \Illuminate\Http\Response
     */
    public function validation($id)
    {
        try {
            $receipt_product = receipt_product::findOrFail($id);
            $receipt_product->update([
                'validate'=> True,
            ]);
            $purchase = purchase::where('purchase_no',$receipt_product->purchase_no)->first();
            $purchase->update([
                'receipt_validate'=> True,
            ]);
            foreach ($receipt_product->po->products as $data){
                $qty = $data->qty;
                $price = $data->price;
                $new_valuation = $qty * $price;
                $product_id = $data->name;
                
                
                $product = Product::find($product_id);
                $oldstock = $product->stock;
                $old_valuation = $oldstock * $product->cost;
                $newstock = $oldstock + $qty;
                $new_cost = ceil(($new_valuation + $old_valuation)/$newstock);
                
                Product::where('id',$product_id)->update([
                    'stock' => $newstock,
                    'cost' => $new_cost,
                    ]);

                // insert stock Valuation
                $stock_move_id = stock_move::where([['location_id',$purchase->id],['type','Purchase'],['product_id',$product_id]])->first();
                stock_valuation::insert([
                    'product_id'=>$data->name,
                    'quantity'=>$data->qty,
                    'unit_cost'=>$new_cost,
                    'value'=>$data->qty*$new_cost,
                    'description'=>" $receipt_product->receipt_no - $product->name",
                    'stock_move_id'=>$stock_move_id->id,
                    'create_uid'=>Auth::id(),
                    'created_at'=>date('Y-m-d H:i:s'),
                ]);
            }
            stock_move::where([['location_id',$purchase->id],['type','Purchase']])->update([
                'state'=>"Done"
            ]);
            Toastr::success('Receipt Product Validation Successfully','Success');
            return redirect(route('product'));
        } catch (\Exception $e) {
            Toastr::error($e->getMessage(),'Something Wrong');
            // Toastr::error('Check In Error!','Something Wrong');
            return redirect()->back();
        }
    }
}
