@extends('reports.layouts.report')
@section('title')
<title>Purchase Order {{$purchase->purchase_no}}</title>
@endsection
@section('content')
    <div class="customer">
        <ul style="list-style-type:none;" class="pull-right">
            <li><span class="text">{{$purchase->vendor->parent_id}} , {{$purchase->vendor->partner_name}}</span></li>
            <li><span class="text">{{$purchase->vendor->address}}</span></li>
            <li><span class="text">{{$purchase->vendor->city}}, {{$purchase->vendor->street}} </span></li>
            <li><span class="text">{{$purchase->vendor->phone}}</span></li>
            <li><span class="text">{{$purchase->vendor->email}}</span></li>
        </ul>
    </div>
    <table>s
    <tr>
        <td colspan="2" style="font-size:30px;">
            <b>Bill No {{$purchase->purchase_no}}</b>
        </td>
    </tr>
    <tr>
        <td class="mt-3 mr-4">
            <b>Purchase Date :</b>
        </td>
        <td class="mt-3 mr-4">
            <b>Due Date :</b>
        </td>
    </tr>
    <tr>
        <td>
            {{$purchase->purchase_date}}
        </td>
        <td>
            {{$purchase->due_date}}
        </td>
    </tr>
    </table>
    <table class="table mt-3">
        <thead>
            <tr>
            <th scope="col">No</th>
            <th scope="col">Product Name</th>
            <th scope="col">Quantity</th>
            <th scope="col">Unit Price</th>
            <th scope="col">Amount</th>
            </tr>
        </thead>
        <tbody>
            @foreach($purchase->products as $product)
                <tr>
                    <td scope="row">{{$loop->iteration}}
                    <td id="product" class="table-name">{{$product->name}}</td>
                    <td class="table-price">Rp. {{number_format($product->price)}}</td>
                    <td class="table-qty">{{$product->qty}}</td>
                    <td class="table-total">Rp. {{number_format($product->qty * $product->price)}}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td class="table-empty" colspan="3"></td>
                <td class="table-label">Sub Total</td>
                <td class="table-amount">Rp. {{number_format($purchase->sub_total)}}</td>
            </tr>
            <tr>
                <td class="table-empty" colspan="3" style="border:0;"></td>
                <td class="table-label">Discount</td>
                <td class="table-amount">Rp. {{number_format($purchase->discount)}}</td>
            </tr>
            <tr>
                <td class="table-empty" colspan="3" style="border:0;"></td>
                <td class="table-label">Grand Total</td>
                <td class="table-amount">Rp. {{number_format($purchase->grand_total)}}</td>
            </tr>
        </tfoot>
    </table>
    <footer>
        <div>
        Memo: {{$purchase->title}}
@endsection
@section('type','Bill')