@extends('layouts.admin')
@section('title','SK - Employee')
@section('css')
<link href="{{asset('css/web.assets_common.css')}}" rel="stylesheet">
<link href="{{asset('css/web.assets_backend.css')}}" rel="stylesheet">
@endsection
@section('content')
<div class="app-page-title bg-white">
    <div class="o_control_panel">
        <div>
            <ol class="breadcrumb" role="navigation">
                <li class="breadcrumb-item" accesskey="b"><a href="{{route('product')}}">Products</a></li>
            </ol>
            <div class="o_cp_searchview" role="search">
                <div class="o_searchview" role="search" aria-autocomplete="list">
                    <form action="{{ route('product.filter') }}" method="get" >
                        <button class="o_searchview_more fa fa-search-minus" title="Advanced Search..." role="img"
                            aria-label="Advanced Search..." type="submit"></button>

                        <div class="o_searchview_input_container">
                            <input type="text" class="o_searchview_input" accesskey="Q" placeholder="Search..."
                                role="searchbox" aria-haspopup="true" name="value">
                            <input type="hidden" class="o_searchview_input" accesskey="Q" placeholder="key"
                            role="searchbox" aria-haspopup="true" name="filter">
                            <div class="dropdown-menu o_searchview_autocomplete" role="menu"></div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div>
            <div class="o_cp_left">
                <div class="o_cp_buttons" role="toolbar" aria-label="Control panel toolbar">
                    <div>
                        <a type="button" class="btn btn-primary o-kanban-button-new" accesskey="c" href="{{route('product.create')}}">
                            Create
                        </a>

                        <button type="button" class="btn btn-secondary">
                            Import
                        </button>
                    </div>
                </div>
            </div>
            <div class="o_cp_right">
                <div class="btn-group o_search_options position-static" role="search">
                    <div>
                        <div class="btn-group o_dropdown">
                            <select
                                class=" o_filters_menu_button o_dropdown_toggler_btn btn btn-secondary dropdown-toggle "
                                data-toggle="dropdown" aria-expanded="false" tabindex="-1" data-flip="false"
                                data-boundary="viewport" name="key" id="key">
                                <option value="" data-icon="fa fa-filter">Filters</option>
                                <option value="name">Name</option>
                                <!-- <span class="fa fa-filter"></span> Filters -->
                            </select>
                        </div>
                    </div>
                </div>
                <nav class="o_cp_pager" role="search" aria-label="Pager">
                    <div class="o_pager">
                        <span class="o_pager_counter">
                            <span class="o_pager_value">{{$products->total()}}</span> / <span class="o_pager_limit">{{$products->perPage()}}</span>
                        </span>
                        <span class="btn-group d-none" aria-atomic="true">
                            <button type="button" class="fa fa-chevron-left btn btn-secondary o_pager_previous"
                                accesskey="p" aria-label="Previous" title="Previous" tabindex="-1"></button>
                            <button type="button" class="fa fa-chevron-right btn btn-secondary o_pager_next"
                                accesskey="n" aria-label="Next" title="Next" tabindex="-1"></button>
                        </span>
                    </div>
                </nav>
                <nav class="btn-group o_cp_switch_buttons" role="toolbar" aria-label="View switcher">
                    <button type="button" accesskey="l" class="btn btn-secondary fa fa-lg fa-list-ul o_cp_switch_list active"
                        aria-label="View list" data-view-type="list" title="" tabindex="-1"
                        data-original-title="View list"></button>
                    <button type="button" accesskey="k" class="btn btn-secondary fa fa-lg fa-th-large o_cp_switch_kanban" 
                        aria-label="View kanban" data-view-type="kanban" title="" tabindex="-1" 
                        data-original-title="View kanban"></button>
                </nav>
            </div>
        </div>
    </div>
    <div class="o-content">
        <div class="panel-body ml-2">
            @if($products->count())
            <div class="table-responsive mb-3">
                <table class="table table-hover">
                    <thead class="table table-sm">
                        <tr>
                            <th scope="col">Image</th>
                            <th scope="col">Product Name</th>
                            <th scope="col">On Hands</th>
                            <th scope="col">Price</th>
                            <th scope="col">Category</th>
                            <th scope="col">Last Update</th>
                            <th scope="col">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($products as $row)
                        <tr>
                            <td>
                                @if (!empty($row->photo))
                                    <img src="{{ asset('uploads/product/' . $row->photo) }}" 
                                        alt="{{ $row->name }}" width="50px" height="50px">
                                @else
                                    <img src="http://via.placeholder.com/50x50" alt="{{ $row->name }}">
                                @endif
                            </td>
                            <td>
                                <sup class="label label-success">({{ $row->code }})</sup>
                                <strong>{{ ucfirst($row->name) }}</strong>
                            </td>
                            <td>{{ $row->stock }}</td>
                            <td>Rp {{ number_format($row->price) }}</td>
                            <td>{{ $row->category->name }}</td>
                            <td>{{ $row->updated_at }}</td>
                            <td>
                                <form id="delete-form-{{ $row->id }}" action="{{route('product.destroy', $row->id)}}" method="put">
                                    @csrf
                                    @method('DELETE')
                                        <a href="{{route('product.edit', $row->id)}}" class="btn btn-sm btn-success"><i class="fa fa-edit">  View Detail</i></a>
                                    <!-- <button type="button" onclick="deletePost({{ $row->id }})" class="btn btn-sm btn-danger"><i class="fa fa-trash">  Delete</i></button> -->
                                    <!-- {{--onclick="return confirm('Are you sure?')"--}} -->
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <div class="o_nocontent_help">
                <p class="o_view_nocontent_smiling_face">
                    <img src="{{asset('images/icons/smiling_face.svg')}}" alt=""><br>
                    Create a new Products and Start your trading
                </p>
                <p>
                    You must define a product for everything you sell or purchase,
                    whether it's a storable product, a consumable or a service.
                </p>
            </div>
            @endif
            </div>
        </div>
    <div class="row mx-4">
        {!! $products->render() !!}
    </div>
</div>
@endsection
@section('js')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@7.28.8/dist/sweetalert2.all.min.js"></script>
<script type="text/javascript">
$('a#product').addClass('mm-active');
$("#key").change(function() {
    var value = $("#key").val();
    $("input[name='filter']").val(value);
});
</script>
@endsection