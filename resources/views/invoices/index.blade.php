@extends('layouts.admin')
@section('title','SK - Employee')
@section('content')
    <div class="panel panel-default">
        <div class="row">
            <div class="col-12 col-md-7">
                <div class="ml-auto text-right">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a></li>
                            <li class="breadcrumb-item active" aria-current="page"><a href="{{route('invoices')}}">Invoices</a></li>
                        </ol>
                    </nav>
                </div>
                <h3>Invoices List</h3>
            </div>
            <div class="col-12 col-md-5 text-right">
                <form action="{{ route('invoices.filter') }}" method="get" >
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <select class="input-group-text bg-primary text-white" name="filter">
                                    <option value="" selected>Filter By</option>
                                    <option value="invoice_no">Invoice No</option>
                                    <option value="name">Customer Name</option>
                                    <option value="due_date">Due Date</option>
                            </select>
                        </div>
                        <input type="text" class="form-control" placeholder="Search...." name="value">
                        <div class="input-group-append">
                            <button class="btn btn-outline-success" type="submit"><i class="fa fa-search" aria-hidden="true"> Search</i></button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="row">
            <div class="col-3">
            <a href="{{route('invoices.create')}}" class="btn btn-success">Create</a>
            </div>
        </div>
        <div class="panel-body mt-3">
            @if($invoices->count())
            <table class="table">
                <thead class="table table-sm">
                    <tr>
                        <th scope="col">Invoice No.</th>
                        <th scope="col">Grand Total</th>
                        <th scope="col">Client</th>
                        <th scope="col">Invoice Date</th> 
                        <th scope="col">Due Date</th>
                        <th scope="col" colspan="2">Created At</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($invoices as $invoice)
                        <tr>
                            <td>{{$invoice->invoice_no}}</td>
                            <td>Rp. {{ number_format($invoice->grand_total)}}</td>
                            <td>{{$invoice->name}}</td>
                            <td>{{$invoice->invoice_date}}</td>
                            <td>{{$invoice->due_date}}</td>
                            <td>{{$invoice->created_at->diffForHumans()}}</td>
                            <td class="text-right">
                                <a href="{{route('invoices.show', $invoice)}}" class="btn btn-primary btn-sm">View</a>
                                <a href="{{route('invoices.edit', $invoice)}}" class="btn btn-warning btn-sm">Retur</a>
                                <!-- <form class="form-inline" method="post"
                                    action="{{route('invoices.destroy', $invoice)}}"
                                    onsubmit="return confirm('Are you sure?')"
                                >
                                    <input type="hidden" name="_method" value="delete">
                                    <input type="hidden" name="_token" value="{{csrf_token()}}">
                                    <input type="submit" value="Delete" class="btn btn-danger btn-sm">
                                </form> -->
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            {!! $invoices->render() !!}
            @else
                <div class="invoice-empty">
                    <p class="invoice-empty-title">
                        No Invoices were created.
                        <a href="{{route('invoices.create')}}">Create Now!</a>
                    </p>
                </div>
            @endif
        </div>
    </div>
@endsection
@section('js')
<script>
    $('a#invoices').addClass('mm-active');
</script>
@endsection