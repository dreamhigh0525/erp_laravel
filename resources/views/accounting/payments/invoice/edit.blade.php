@extends('layouts.admin')
@section('title','Payment Invoice')
@section('css')
<link href="{{asset('css/web.assets_common.css')}}" rel="stylesheet">
<link href="{{asset('css/web.assets_backend.css')}}" rel="stylesheet">
@endsection
@section('content')
<form action="{{ route('payment.update') }}" method="post" enctype="multipart/form-data">
    @csrf
    <div class="app-page-title bg-white">
        <div class="o_control_panel">
            <div>
                <ol class="breadcrumb" role="navigation">
                    <li class="breadcrumb-item" accesskey="b"><a href="{{route('payment_invoices.index')}}">Payments</a></li>
                    <li class="breadcrumb-item active">{{$data->name}}</li>
                </ol>
            </div>
            <div>
                <div class="o_cp_left">
                    <div class="o_cp_buttons" role="toolbar" aria-label="Control panel toolbar">
                        <div>
                            <button class="btn btn-primary my-2" @click="create" :disabled="isProcessing">Save</button>
                            <a href="{{route('purchases')}}" class="btn btn-secondary mby-2">Discard</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row o-content">
        <div class="col-12 my-4">
            <div class="o_form_view o_form_editable">
                <div class="clearfix position-relative o_form_sheet">
                    <div class="o_not_full oe_button_box">
                        <button type="button" class="btn oe_stat_button o_invisible_modifier">
                            <i class="fa fa-fw o_button_icon fa-dollar"></i>
                            <span>Payment Matching</span>
                        </button>
                    </div>
                    <div class="oe_title ml-3 mt-5">
                        <h1>
                            <input type="hidden" name="id" value="{{$data->id}}">
                            <span class="o_field_char o_field_widget o_readonly_modifier">{{$data->name}}</span>
                        </h1>
                    </div>
                    <div class="o_group">
                        <div class="row">
                            <div class="col-12 col-md-6"> 
                                <table class="o_group o_inner_group ml-3">
                                    <tbody>
                                        <tr>
                                            <td class="o_td_label">
                                                <label class="o_form_label o_required_modifier">Payment Type</label>
                                            </td>
                                            <td style="width: 100%;">
                                                <div class="row ml-3">
                                                    <input class="form-check-input" type="radio" id="payment_type" name="payment_type" value="inbound" @if($data->payment_type== "inbound" ) checked @endif>
                                                    <label class="o_form_label">Send Money</label>
                                                </div>
                                                <div class="row ml-3 mb-2">
                                                    <input class="form-check-input" type="radio" id="payment_type" name="payment_type" value="outbound" @if($data->payment_type== "outbound" ) checked @endif>
                                                    <label class="o_form_label">Receive Money</label>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="o_td_label">
                                                <label for="" name="partner_type" class="col-form-label"><b>Partner Type </b></label>
                                            </td>
                                            <td style="width: 100%;">
                                                <div class="form-group">
                                                    <select id="partner_type" required name="partner_type" class="form-control o_input o_field_widget o_required_modifier">
                                                        <option value="customer">Customer</option>
                                                    </select>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="o_td_label">
                                                <label for="" name="partner" class="col-form-label"><b>Partner</b></label>
                                            </td>
                                            <td style="width: 100%;">
                                                <div class="form-group">
                                                    <select id="partner_id" required name="partner_id" class="form-control o_input o_field_widget o_required_modifier">
                                                        <option value=""></option>
                                                        @foreach ($partner as $row)
                                                            <option value="{{ $row->id }}" {{ $row->id == $data->partner_id ? 'selected':'' }}>{{ ucfirst($row->name) }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="o_td_label">
                                                <label class="o_form_label o_readonly_modifier o_required_modifier">Company</label>
                                            </td>
                                            <td style="width: 100%;">
                                                <a class="o_form_uri o_field_widget o_readonly_modifier o_required_modifier"
                                                href="#id=1&amp;model=res.company" name="company_id"><span>{{$data->company->company_name}}</span></a>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="col-12 col-md-6">
                                <table class="o_group o_inner_group">
                                    <tbody>
                                        <tr>
                                            <td class="o_td_label">
                                                <label class="o_form_label o_required_modifier">Journal</label>
                                            </td>
                                            <td style="width: 100%;">
                                                <select class="o_input o_field_widget o_required_modifier" required name="journal_id">
                                                    <option value=""></option>
                                                    @foreach ($journal as $row)
                                                        <option value="{{ $row->id }}" {{ $row->id == $data->journal_id ? 'selected':'' }}>{{ ucfirst($row->name) }} ({{ ucfirst($row->currency->currency_name) }})</option>
                                                    @endforeach
                                                </select>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="o_td_label">
                                                <label class="o_form_label o_required_modifier">Payment Method</label></td>
                                            <td style="width: 100%;">
                                                <div class="row ml-3">
                                                    <input class="form-check-input" type="radio" id="payment_method" name="payment_method" value="Manual" @if($data->payment_method_id== "Manual" ) checked @endif>
                                                    <label class="o_form_label">Manual</label>
                                                </div>
                                                <div class="row ml-3">
                                                    <input class="form-check-input" type="radio" id="payment_method" name="payment_method" value="Check" @if($data->payment_method_id== "Check" ) checked @endif>
                                                    <label class="o_form_label">Check</label>
                                                </div>
                                                <div class="row ml-3 mb-2">
                                                    <input class="form-check-input" type="radio" id="payment_method" name="payment_method" value="PDC" @if($data->payment_method_id== "PDC" ) checked @endif>
                                                    <label class="o_form_label">PDC</label>
                                                </div>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12 col-md-6"> 
                                <table class="o_group o_inner_group ml-3">
                                    <tbody>
                                        <tr>
                                            <td class="o_td_label">
                                                <label class="o_form_label">Amount</label>
                                            </td>
                                            <td style="width: 100%;">
                                                <div name="amount_div" class="o_row">
                                                    <input type="text" class="o_input o_field_widget o_required_modifier" name="amount" value="{{ $data->amount }}">
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="o_td_label">
                                                <label class="o_form_label o_required_modifier">Date</label>
                                            </td>
                                            <td style="width: 100%;">
                                                <input type="date" class="o_input o_field_widget o_required_modifier" name="payment_date" value="{{ $data->payment_date}}">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="o_td_label">
                                                <label class="o_form_label">Bank Reference</label>
                                            </td>
                                            <td style="width: 100%;">
                                                <input class="o_field_char o_field_widget o_input" name="bank_reference" value="{{$data->bank_reference}}">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="o_td_label">
                                                <label class="o_form_label">Cheque Reference</label>
                                            </td>
                                            <td style="width: 100%;">
                                                <input class="o_field_char o_field_widget o_input" name="cheque_reference" value="{{$data->cheque_reference}}">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="o_td_label">
                                                <label class="o_form_label">Memo</label>
                                            </td>
                                            <td style="width: 100%;">
                                                <input class="o_field_char o_field_widget o_input" name="communication" value="{{$data->communication}}">
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection
@section('js')
<script src="{{asset('js/asset_common/payment.js')}}"></script>
@endsection