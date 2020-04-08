@extends('layouts.admin')
@section('title','SK - Employee')
@section('content')
    <div class="panel panel-default">
        <div class="panel-heading">
            <div class="clearfix">
                <span class="panel-title">Purchase</span>
                <div class="pull-right">
                    <a href="{{route('purchases')}}" class="btn btn-default">Back</a>
                    <a href="{{route('purchases.edit', $purchases)}}" class="btn btn-primary">Edit</a>
                    <!-- <form class="form-inline" method="post"
                        action="{{route('purchases.destroy', $purchases)}}"
                        onsubmit="return confirm('Are you sure?')"
                    >
                        <input type="hidden" name="_method" value="delete">
                        <input type="hidden" name="_token" value="{{csrf_token()}}">
                        <input type="submit" value="Delete" class="btn btn-danger">
                    </form> -->
                </div>
            </div>
        </div>
        <div class="panel-body">
            <div class="row">
                <div class="col-sm-4">
                    <div class="form-group">
                        <label>Purchase No.</label>
                        <p>{{$purchases->purchase_no}}</p>
                    </div>
                    <div class="form-group">
                        <label>Grand Total</label>
                        <p>Rp. {{ number_format($purchases->grand_total)}}</p>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="form-group">
                        <label>Supplier</label>
                        <p id="client">{{$purchases->client}}</p>
                    </div>
                    <div class="form-group">
                        <label>Supplier Address</label>
                        <pre class="pre">{{$purchases->client_address}}</pre>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="form-group">
                        <label>Title</label>
                        <p>{{$purchases->title}}</p>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <label>purchase Date</label>
                            <p>{{$purchases->purchase_date}}</p>
                        </div>
                        <div class="col-sm-6">
                            <label>Due Date</label>
                            <p>{{$purchases->due_date}}</p>
                        </div>
                    </div>
                </div>
            </div>
            <hr>
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Product Name</th>
                        <th>Price</th>
                        <th>Qty</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($purchases->products as $product)
                        <tr>
                            <td id="product" class="table-name">{{$product->name}}</td>
                            <td class="table-price">Rp. {{ number_format($product->price)}}</td>
                            <td class="table-qty">{{$product->qty}}</td>
                            <td class="table-total text-right">Rp. {{ number_format($product->qty * $product->price)}}</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <td class="table-empty" colspan="2"></td>
                        <td class="table-label">Sub Total</td>
                        <td class="table-amount">Rp. {{ number_format($purchases->sub_total)}}</td>
                    </tr>
                    <tr>
                        <td class="table-empty" colspan="2"></td>
                        <td class="table-label">Discount</td>
                        <td class="table-amount">Rp. {{ number_format($purchases->discount)}}</td>
                    </tr>
                    <tr>
                        <td class="table-empty" colspan="2"></td>
                        <td class="table-label">Grand Total</td>
                        <td class="table-amount">Rp. {{ number_format($purchases->grand_total)}}</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
@endsection
@section('js')
<script>
$('a#purchases').addClass('mm-active');
$.ajax  ({
    url: "{{asset('api/partner/search')}}",
    type: 'post',
    dataType: 'json',
    data :{
        'id': "{{$purchases->client}}"
    },
    success: function (result) {
        $("#client").html(result.data.partner_name);
    }
})
$.ajax  ({
    url: "{{asset('api/product/search')}}",
    type: 'post',
    dataType: 'json',
    data :{
        'id': "{{$product->name}}"
    },
    success: function (result) {
        console.log(result.data.price);
        $("#product").html(result.data.name);
    }
})
</script>
@endsection