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
                            <li class="breadcrumb-item active" aria-current="page"><a href="{{route('purchases')}}">Purchases</a></li>
                        </ol>
                    </nav>
                </div>
                <h3>Purchases List</h3>
            </div>
            <div class="col-12 col-md-5 text-right">
                <div class="input-group mb-3">
                    <input type="text" class="form-control" placeholder="Search...." aria-label="Recipient's username" aria-describedby="basic-addon2">
                    <div class="input-group-append">
                        <button class="btn btn-outline-secondary" type="submit">Search</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-3">
            <a href="{{route('purchases.create')}}" class="btn btn-success">Create</a>
            </div>
        </div>
        <div class="panel-body mt-3">
            @if($purchases->count())
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Purchase No.</th>
                        <th>Grand Total</th>
                        <th>Client</th>
                        <th>Purchase Date</th>
                        <th>Due Date</th>
                        <th colspan="2">Created At</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($purchases as $purchase)
                        <tr>
                            <td>{{$purchase->purchase_no}}</td>
                            <td>Rp. {{ number_format($purchase->grand_total)}}</td>
                            <td>{{$purchase->partner_name}}</td>
                            <td>{{$purchase->purchase_date}}</td>
                            <td>{{$purchase->due_date}}</td>
                            <td>{{$purchase->created_at->diffForHumans()}}</td>
                            <td class="text-right">
                                <a href="{{route('purchases.show', $purchase)}}" class="btn btn-primary btn-sm">View</a>
                                <a href="{{route('purchases.edit', $purchase)}}" class="btn btn-warning btn-sm">Retur</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            {!! $purchases->render() !!}
            @else
                <div class="puchase-empty">
                    <p class="puchase-empty-title">
                        No Purchases were created.
                        <a href="{{route('purchases.create')}}">Create Now!</a>
                    </p>
                </div>
            @endif
        </div>
    </div>
@endsection