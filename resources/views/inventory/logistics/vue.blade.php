@extends('layouts.admin')
@section('title','Inventory - Logistics')
@section('css')
<link href="{{asset('css/web.assets_common.css')}}" rel="stylesheet">
<link href="{{asset('css/web.assets_backend.css')}}" rel="stylesheet">
@endsection
@section('content')
    <div id="app"></div>
    <script src="{{ mix('js/app.js') }}"></script>
@endsection