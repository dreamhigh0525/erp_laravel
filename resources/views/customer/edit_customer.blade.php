@extends('layouts.admin')
@section('title',"Partner - $res_customer->display_name")
@section('css')
<link href="{{asset('css/web.assets_backend.css')}}" rel="stylesheet">
@endsection
@section('content')
<form action="{{ url('customer/update') }}" method="post" enctype="multipart/form-data">
    @csrf
    <div class="app-page-title bg-white">
        <div class="o_control_panel">
            <div>
                <ol class="breadcrumb" role="navigation">
                    <li class="breadcrumb-item" accesskey="b"><a href="{{route('customer')}}">Customers</a></li>
                    <li class="breadcrumb-item active">{{$res_customer->display_name}}</li>
                </ol>
            </div>
            <div>
                <div class="o_cp_left">
                    <div class="o_cp_buttons" role="toolbar" aria-label="Control panel toolbar">
                        <div>
                            <button class="btn btn-primary my-2" @click="create" :disabled="isProcessing">Save</button>
                            <a href="{{route('customer')}}" class="btn btn-secondary mby-2">Discard</a>
                        </div>
                    </div>
                    <aside class="o_cp_sidebar">
                        <div class="btn-group">
                            <div class="btn-group o_dropdown" style="display: none;">
                                <button class="o_dropdown_toggler_btn btn btn-secondary dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                                    Print
                                </button>
                                
                                <div class="dropdown-menu o_dropdown_menu" role="menu">
                                        
                                </div>
                            </div>

                            <div class="btn-group o_dropdown">
                                <button class="o_dropdown_toggler_btn btn btn-secondary dropdown-toggle" data-toggle="dropdown" aria-expanded="false"> 
                                    Action
                                </button>
                                
                                <div class="dropdown-menu o_dropdown_menu" role="menu" x-placement="bottom-start" style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(0px, 30px, 0px);">
                                    <button type="button" class="dropdown-item undefined" data-toggle="modal" data-target="#exampleModal">
                                        <i class="fa fa-trash"> Delete Record</i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </aside>
                </div>
                <div class="o_cp_right">
                    <div class="btn-group o_search_options position-static" role="search"></div>
                    <nav class="o_cp_pager" role="search" aria-label="Pager">
                        <div class="o_pager">
                            <span class="o_pager_counter">
                                <span class="o_pager_value">1</span> / <span class="o_pager_limit">1</span>
                            </span>
                            <span class="btn-group" aria-atomic="true">
                                <button type="button" class="fa fa-chevron-left btn btn-secondary o_pager_previous"
                                    accesskey="p" aria-label="Previous" title="Previous" tabindex="-1" disabled=""></button>
                                <button type="button" class="fa fa-chevron-right btn btn-secondary o_pager_next"
                                    accesskey="n" aria-label="Next" title="Next" tabindex="-1" disabled=""></button>
                            </span>
                        </div>
                    </nav>
                    <nav class="btn-group o_cp_switch_buttons" role="toolbar" aria-label="View switcher"></nav>
                </div>
            </div>
        </div>
    </div>
    <div class="o_content">
        <div class="o_form_view o_form_editable">
            <div class="o_form_sheet_bg">
                <div class="clearfix o_form_sheet">
                    <div class="o_not_full oe_button_box mx-0">
                        <button type="button" class="btn oe_stat_button" name="457" width="200px">
                            <i class="fa fa-fw o_button_icon fa-usd"></i>
                            <div name="sale_order_count" class="o_field_widget o_stat_info o_readonly_modifier"
                                data-original-title="" title="">
                                @if ($res_customer->currency->position == "before")
                                <span class="o_stat_value">{{$res_customer->currency->symbol}}. {{ number_format($res_customer->credit_limit)}}</span>
                                @else
                                <span class="o_stat_value">{{ number_format($res_customer->credit_limit)}} {{$res_customer->currency->symbol}}</span>
                                @endif
                                <span class="o_stat_text">Saldo</span>
                            </div>
                        </button>
                        <a type="button" href="{{ route('CustomerDebt.show', $res_customer->id) }}" class="btn oe_stat_button" name="457" width="200px">
                            <i class="fa fa-fw o_button_icon fa-usd"></i>
                            <div name="sale_order_count" class="o_field_widget o_stat_info o_readonly_modifier"
                                data-original-title="" title="">
                                @if ($res_customer->currency->position == "before")
                                    <span class="o_stat_value">{{$res_customer->currency->symbol}}. {{ number_format($res_customer->debit_limit)}}</span>
                                @else
                                    <span class="o_stat_value">{{ number_format($res_customer->debit_limit)}} {{$res_customer->currency->symbol}}</span>
                                @endif
                                <span class="o_stat_text">Sales</span>
                            </div>
                        </a>
                        <a type="button" href="{{ url('invoices/filter?value='.$res_customer ->name.'&filter=name') }}" class="btn oe_stat_button" name="action_view_partner_invoices"
                            context="{'default_partner_id': active_id}">
                            <i class="fa fa-fw o_button_icon fa-pencil-square-o"></i>
                            <div class="o_form_field o_stat_info">
                                <span class="o_stat_value">{{$invoice}}</span>
                                <span class="o_stat_text">Invoiced</span>
                            </div>
                        </a>
                    </div>
                    <div class="row">
                        <div class="col-12 col-md-10">
                            <div class="row">
                                <div class="col-9">
                                    <h1>
                                        <div class="o_field_partner_autocomplete dropdown open wrap-input-required o_required_modifier" name="name"
                                            placeholder="Name" data-original-title="" title="">
                                            <input class="input200  @error('name') is-invalid @enderror" placeholder="Name" type="text" id="name" name="name" value="{{ $res_customer -> name }}" required>
                                            <input type="hidden" name="id" value="{{$res_customer -> id}}">
                                        </div>
                                    </h1>
                                    <div class="o_row">
                                        <div class="wrap-input200 " aria-atomic="true" name="Parent_id" placeholder="Company" data-original-title="" title="">
                                            <input type="text" class="input200" placeholder="Company" autocomplete="off" value="{{ $res_customer -> parent_id }}" name="Parent_id" id="Parent_id" required>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-md-2">
                            <div class="form-group">
                                <img id='img-upload' src="{{asset('uploads/customers/'.$res_customer->logo)}}" width="50px"/>
                                <div class="input-group">
                                    <span class="input-group-btn">
                                        <span class="btn btn-default btn-file bg-primary text-white">
                                            Browse… <input type="file" id="imgInp" name="photo">
                                        </span>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="o_group">
                        <div class="row">
                            <div class="col-12 col-md-6">
                                <table class="o_group o_inner_group">
                                    <tbody>
                                        <tr>
                                            <td class="o_td_label"><label class="col-form-label" for="o_field_input_566"
                                                    data-original-title="" title=""><b>Address type</b></label></td>
                                            <td style="width: 100%;">
                                            <div class="wrap-input-required">
                                                <select class="input200" name="type" id="type" style="border:none;" required>
                                                    <option value="" style=""></option>
                                                    <option value="contact" @if($res_customer->address == "contact" ) selected="selected" @endif>Contact</option>
                                                    <option value="invoice" @if($res_customer->address == "invoice" ) selected="selected" @endif>Invoice Address</option>
                                                    <option value="delivery" @if($res_customer->address == "delivery" ) selected="selected" @endif>Delivery Address</option>
                                                    <option value="other" @if($res_customer->address == "other" ) selected="selected" @endif>Other Address</option>
                                                    <option value="private" @if($res_customer->address == "private" ) selected="selected" @endif>Private Address</option>
                                                </select>
                                            </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="o_td_label">
                                                <label for="" name="address_name" class="col-form-label"><b>Address</b></label>
                                            </td>
                                            <td>
                                                <div class="o_address_format">
                                                    <div class="wrap-input-required">
                                                        <input class="input200 " name="street1" required value="{{ $res_customer -> street }}" 
                                                            placeholder="Street..." type="text" id="street1" >
                                                    </div>
                                                    <div class="wrap-input200">
                                                        <input class="input200" value="{{ $res_customer -> street2 }}"
                                                            name="street2" placeholder="Street 2..." type="text" id="street2">
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-6">
                                                            <div class="wrap-input-required">
                                                                <input class="input200" required value="{{ $res_customer -> city }}"
                                                                    name="city" placeholder="City..." type="text" id="city">
                                                            </div>
                                                        </div>
                                                        <div class="col-6">
                                                            <div class="wrap-input-required">
                                                                <input class="input200" required value="{{ $res_customer -> zip }}"
                                                                    name="zip" placeholder="ZIP" type="text" id="zip">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="wrap-input-required">
                                                        <select id="country" name="country" class="input200" required style="border:none;">
                                                            <option value="">country</option>
                                                            @foreach (ResCountry::country() as $row)
                                                                <option value="{{ $row->id }}" {{ $row->id == $res_customer -> country_id ? 'selected':'' }}>
                                                                    {{ ucfirst($row->country_name) }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="wrap-input-required">
                                                        <select id="state" name="state" class="input200" style="border:none;">
                                                            <option value="">State</option>
                                                            @foreach (ResCountry::state()  as $row)
                                                                <option value="{{ $row->id }}" {{ $row->id == $res_customer -> state_id ? 'selected':'' }}>
                                                                    {{ ucfirst($row->state_name) }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="o_td_label">
                                                <label for="" name="industry" class="col-form-label"><b>industry</b></label>
                                            </td>
                                            <td style="width: 100%;">
                                                <div class="wrap-input-required">
                                                    <select id="industry_id" required name="industry_id" class="input200" style="border:none;">
                                                        <option value="">Industry</option>
                                                        @foreach (ResPartner::industry() as $row)
                                                            <option value="{{ $row->id }}" {{ $row->id == $res_customer -> industry_id ? 'selected':'' }}>
                                                                {{ ucfirst($row->industry_name) }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
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
                                                <label for="" name="email" class="col-form-label"><b>Email</b></label>
                                            </td>
                                            <td style="width: 100%;">
                                                <div class="wrap-input-required">
                                                    <input class="input200 " name="email" required value="{{ $res_customer -> email  }}" 
                                                        placeholder="email@example.com" type="text" id="email" >
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="o_td_label">
                                                <label for="" name="phone" class="col-form-label"><b>Phone</b></label>
                                            </td>
                                            <td style="width: 100%;">
                                                <div class="wrap-input-required">
                                                    <input class="input200 " name="phone" required value="{{ $res_customer -> phone  }}" type="text" id="phone" >
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="o_td_label">
                                                <label for="" name="mobile" class="col-form-label"><b>mobile</b></label>
                                            </td>
                                            <td style="width: 100%;">
                                                <div class="wrap-input200">
                                                    <input class="input200 " name="mobile" value="{{ $res_customer -> mobile  }}" type="text" id="mobile" >
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="o_td_label">
                                                <label for="" name="website" class="col-form-label"><b>Website Link</b></label>
                                            </td>
                                            <td style="width: 100%;">
                                                <div class="wrap-input200">
                                                    <input class="input200 " name="website" placeholder="https://www.kltech-intl.technology" value="{{ $res_customer -> website  }}" type="text" id="website" >
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="o_td_label">
                                                <label for="" name="reference" class="col-form-label"><b>Reference</b></label>
                                            </td>
                                            <td style="width: 100%;">
                                                <div class="wrap-input200">
                                                    <input class="input200 " name="reference" value="{{ $res_customer -> ref  }}" type="text" id="reference" >
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="o_td_label">
                                                <label for="" name="lag" class="col-form-label"><b>Language</b></label>
                                            </td>
                                            <td style="width: 100%;">
                                                <div class="wrap-input-required">
                                                    <select id="lang" name="lag" class="input200" required style="border:none;">
                                                        <option value="">Language</option>
                                                        @foreach (Language::lang() as $row)
                                                            <option value="{{ $row->id }}" {{ $row->id == $res_customer -> lag ? 'selected':'' }}>
                                                                {{ ucfirst($row->lang_name) }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="o_td_label">
                                                <label for="" name="tz/cr" class="col-form-label"><b>Timezone / Currency</b></label>
                                            </td>
                                            <td style="width: 100%;">
                                                <div class="row">
                                                    <div class="col-6">
                                                        <div class="wrap-input-required">
                                                            <select id="tz" name="tz" class="input200" required style="border:none;">
                                                                <option value="">Timezone</option>
                                                                @foreach (TimeZone::timezone() as $row)
                                                                    <option value="{{ $row->timezone }}" {{ $row->timezone == $res_customer -> tz ? 'selected':'' }}>
                                                                        {{ ucfirst($row->timezone) }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-6">
                                                        <div class="wrap-input-required">
                                                            <select id="currency" name="currency_id" required class="input200" style="border:none;">
                                                                <option value="">Currency</option>
                                                                @foreach (ResCurrency::currency() as $row)
                                                                    <option value="{{ $row->id }}" {{ $row->id == $res_customer -> currency_id ? 'selected':'' }}>
                                                                        {{ ucfirst($row->currency_name) }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="o_notebook">
                        <ul class="nav nav-tabs">
                            <li class="nav-item"><a data-toggle="tab" disable_anchor="true" href="#notebook_page_581"
                                    class="nav-link active" role="tab" aria-selected="true">Sales &amp; Purchase</a></li>
                            @if (Addons::cek_install_modules("Accounting") == True)
                            <li class="nav-item"><a data-toggle="tab" disable_anchor="true" href="#notebook_page_591"
                                    class="nav-link" role="tab">Accounting</a></li>
                            @endif
                            <li class="nav-item o_invisible_modifier"><a data-toggle="tab" disable_anchor="true"
                                    href="#notebook_page_595" class="nav-link" role="tab">Invoicing</a></li>
                            <li class="nav-item"><a data-toggle="tab" disable_anchor="true" href="#notebook_page_596"
                                    class="nav-link" role="tab">Internal Notes</a></li>
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane active" id="notebook_page_581">
                                <div class="o_group">
                                    <table class="o_group o_inner_group o_group_col_6">
                                        <tbody>
                                            <tr>
                                                <td colspan="2" style="width: 100%;">
                                                    <div class="o_horizontal_separator">Sales</div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="o_td_label">
                                                    <label for="" name="sales" class="col-form-label"><b>Sales Person</b></label>
                                                </td>
                                                <td style="width: 100%;">
                                                    <div class="wrap-input-required">
                                                        <select id="sales" name="sales" class="input200" required style="border:none;">
                                                            <option value=""></option>
                                                            @foreach (HumanResource::employee() as $row)
                                                                <option value="{{ $row->id }}"{{ $row->id == $res_customer -> sales ? 'selected':'' }}>{{ ucfirst($row->employee_name) }} </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="o_td_label">
                                                    <label for="" name="payment" class="col-form-label"><b>Payment Terms</b></label>
                                                </td>
                                                <td style="width: 100%;">
                                                    <div class="wrap-input200">
                                                        <select id="payment_terms" name="payment_terms" class="input200" style="border:none;">
                                                            <option value=""></option>
                                                            <option value="1" @if($res_customer->payment_terms == "1" ) selected="selected" @endif>Immediate Payment</option>
                                                            <option value="2" @if($res_customer->payment_terms == "2" ) selected="selected" @endif>15 Days</option>
                                                            <option value="3" @if($res_customer->payment_terms == "3" ) selected="selected" @endif>21 Days</option>
                                                            <option value="4" @if($res_customer->payment_terms == "4" ) selected="selected" @endif>30 Days</option>
                                                            <option value="5" @if($res_customer->payment_terms == "5" ) selected="selected" @endif>45 Days</option>
                                                            <option value="6" @if($res_customer->payment_terms == "6" ) selected="selected" @endif>2 Months</option>
                                                            <option value="7" @if($res_customer->payment_terms == "7" ) selected="selected" @endif>End of Following Month</option>
                                                            <option value="8" @if($res_customer->payment_terms == "8" ) selected="selected" @endif>30% Now, Balance 60 Days</option>
                                                        </select>
                                                    </div>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            @if (Addons::cek_install_modules("Accounting") == True)
                                <div class="tab-pane" id="notebook_page_591">
                                    <div class="o_group">
                                        <table class="o_group o_inner_group o_group_col_6">
                                            <tbody>
                                                <tr>
                                                    <td colspan="2" style="width: 100%;">
                                                        <div class="o_horizontal_separator">Accounting Entries</div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="o_td_label">
                                                        <label for="" name="journal" class="col-form-label"><b>Invoice Journal </b></label>
                                                    </td>
                                                    <td style="width: 100%;">
                                                        <div class="wrap-input-required">
                                                            <select id="journal" required name="journal" class="input200" style="border:none;">
                                                                <option value=""></option>
                                                                @foreach (Accounting::account_journal() as $row)
                                                                    <option value="{{ $row->id }}" {{ $row->id == $res_customer -> journal ? 'selected':'' }}>{{ ucfirst($row->name) }} | {{ ucfirst($row->code) }} </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="o_td_label">
                                                        <label for="" name="receivable_account" class="col-form-label"><b>Account Receivable</b></label>
                                                    </td>
                                                    <td style="width: 100%;">
                                                        <div class="wrap-input-required">
                                                            <select id="receivable_account" required name="receivable_account" class="input200" style="border:none;">
                                                                <option value=""></option>
                                                                @foreach (Accounting::account_account() as $row)
                                                                    <option value="{{ $row->id }}"{{ $row->id == $res_customer -> receivable_account ? 'selected':'' }}>{{ ucfirst($row->name) }} | {{ ucfirst($row->code) }} </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td colspan="2" style="width: 100%;">
                                                        <div class="o_horizontal_separator">Bank Accounts</div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="o_td_label">
                                                        <label for="" name="bank_account" class="col-form-label"><b>Bank Account</b></label>
                                                    </td>
                                                    <td style="width: 100%;">
                                                        <div class="wrap-input200">
                                                            <input class="input200" name="bank_account" value="{{ $res_customer->bank_account }}" type="text" id="bank_account" >
                                                        </div>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            @endif
                            <div class="tab-pane" id="notebook_page_596">
                                <div class="wrap-input200">
                                    <textarea class="input200"
                                        name="note" placeholder="Internal note..." type="text" 
                                        style="overflow-y: hidden; height: 50px; resize: none;" data-original-title=""
                                        title="" value="{{$res_customer->note}}"></textarea>
                                </div>
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
<script src="{{asset('js/asset_common/customer.js')}}"></script>
@endsection
@section('modal')
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Are you sure?</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body text-center">
                <img src="{{asset('images/icons/warning.png')}}" alt=""><br>
                <p class="mb-0">Are you sure to delete the customer's record ( {{$res_customer->name}} )</p>
                <p class="mb-0">You won't be able to revert this!</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <a class="btn btn-warning" role="menuitem" data-section="other" data-index="2" href="{{ route('customer.destroy', $res_customer -> id) }}" >
                    <i class="fa fa-trash"> Delete Record</i>
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
