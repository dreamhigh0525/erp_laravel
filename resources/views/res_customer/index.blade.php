@extends('layouts.admin')
@section('title','SK - New Partner')
@section('content')
<div class="container">
    @if (session('status'))
        <div class="alert alert-success">
        {{ session('status') }}
        </div>
    @endif
    <!-- header -->
    <div class="row">
        <div class="col-12 col-md-7">
            <h3>Customer List</h3>
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
    <!-- header button -->
    <div class="row">
        <div class="col-3">
            <a href="{{url('/customer/new')}}" class="btn btn-primary">Create</a>
        </div>
    </div>

    <ul class="list-group mt-5">
        <li class="list-group-item d-flex justify-content-between align-items-center">
        </li>
    </ul>
</div>
@endsection
