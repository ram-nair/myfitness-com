@extends('adminlte::page')

@section('title', $pageTitle)

@section('content_header')
<div class="row mb-2">
    <div class="col-sm-6">
        <h1 class="m-0 text-dark">{{$pageTitle}}</h1>
    </div><!-- /.col -->
</div><!-- /.row -->
@stop
@section('content')
@if(isset($errors) && $errors->any())
    <div class="alert alert-danger">
        @foreach ($errors->all() as $error)
            {{ $error }}
        @endforeach
    </div>
@endif
@if((session())->has('failures'))
    <table class="table table-danger">
        <tr>
            <th>Row</th>
            <th>Product</th>
            <th>Attribute</th>
            <th>Errors</th>
            <th>Value</th>
        </tr>
        @foreach (session()->get('failures') as $validation)
            <tr>
                <td>{{ $validation->row()   }}</td>
                <td>{{ $validation->values()['product_name'] }}</td>
                <td>{{ $validation->attribute() }}</td>
                <td>
                    <ul>
                        @foreach ($validation->errors() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </td>
                <td>{{ $validation->values()['product_name'] }}</td>
            </tr>
        @endforeach
    </table>
@endif
@if(session()->has('success') && session()->has('success') > 0)
    <div class="alert alert-success alert-dismissible">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        <h5><i class="icon fas fa-check"></i>Import Success</h5>
        {{ session()->get('success') }} products imported
      </div>
@endif
<div class="row">
    <div class="col-md-12">
        <div class="card card-outline card-info">
            <div class="overlay" style="display: none;">
                <i class="fas fa-2x fa-sync-alt fa-spin"></i>
            </div>
            <div class="card-header">
                <h3 class="card-title">&nbsp;</h3>
                <!-- tools box -->
                <?php $user = Auth::user(); ?>
                @if(Helper::get_guard() == "admin")
                <div class="card-tools">
                    <div class="btn-group">
                        @if($user->hasRole('super-admin') || $user->hasPermissionTo('storeproduct_create'))
                            <a href="{{ asset('/imports/store_products.xlsx') }}" download class="btn btn-success btn-sm"><i class="fa fa-download" aria-hidden="true"></i>&nbsp;Download Sample Data</a>
                            <span aria-hidden="true">&nbsp;&nbsp;</span>
                            <a href="#" data-toggle="modal" data-target="#importModal" class="btn btn-success btn-sm">Import Products</a>
                        @endif
                    </div>
                </div>
                @endif
                <!-- /. tools -->
            </div>
            <div class="card-body pad">
                @if($guard_name == 'admin')
                <div class="row">
                    <div class="form-group col-4">
                        <label>Filter By Store</label>
                        <select name="store_id" id="store_filter" class="form-control select2">
                            <option value="">Select Store</option>
                            @foreach($stores as $store)
                            <option value="{{ $store->id }}">{{ $store->store_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-4">
                        <label>Filter By Approval</label>
                        <select name="price_approval" id="price_approval" class="form-control select2">
                            <option value="">All</option>
                            <option value="1">Pending</option>
                            <option value="2">Rejected</option>
                        </select>
                    </div>
                </div>
                @endif
                <table class="table table-striped table-bordered dt-responsive nowrap" style="width:100%" id="store-products-table">
                    <thead>
                        <tr>
                            <th class="no-sort">SL.No</th>
                            <th>Name</th>
                            @if($guard_name == 'admin')
                            <th>Store</th>
                            @endif
                            <th class="no-sort">Categories</th>
                            <th class="no-sort">Stock</th>
                            <th class="no-sort">Unit Price</th>
                            <th class="no-sort">Ask Price</th>
                            <th class="no-sort">Price Approval</th>
                            <th>Date/Time Added</th>
                            <th class="no-sort">Actions</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>
{{-- Import Modal --}}
<div class="modal fade" id="importModal" tabindex="-1" role="dialog" aria-labelledby="importModal">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="importModalLabel">Import Products</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                {{ Form::open(array('route' => array('admin.store-products.import'), 'method' => 'POST', 'class' => 'class-create', 'enctype' => 'multipart/form-data')) }}
                    <div class="card card-outline card-info">
                        <div class="overlay" style="display: none;">
                            <i class="fas fa-2x fa-sync-alt fa-spin"></i>
                        </div>
                        <div class="card-body pad">
                            <div class="row">
                                <div class="col-12">
                                    <div class="form-group">
                                        {{ Form::label('store', 'Store') }}
                                        <div class="row">
                                            <div class="col-12">
                                                <div class="form-group">
                                                    <input type="checkbox" name="store_id[]" value="all" id="checkAll">
                                                    <label for="all" style="margin-right: 10px;">All</label>
                                                    <br/><b>OR</b><br/>
                                                    <select class="form-control select2" name="store_id[]" id="store_id" multiple style="width:100%">
                                                        @foreach($stores as $store)
                                                            <option value="{{ $store->id }}">{{ $store->store_name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-group">
                                        <label for="exampleInputFile">Products</label><br/>
                                        <div class="input-group">
                                            <div class="custom-file">
                                                <input type="file" name="products_csv" class="form-control-file custom-file-input" id="exampleInputFile" required style="width:100%">
                                                <label class="custom-file-label" for="exampleInputFile"></label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <a href="{{ route('admin.store-products.index') }}" data-dismiss="modal" class="btn btn-default">Cancel</a>
                            {{ Form::submit(isset($submitButtonText) ? $submitButtonText : 'Import', array('class' => 'btn btn-info float-right')) }}
                        </div>
                    </div>
                {{ Form::close() }}
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="ajaxModal" tabindex="-1" role="dialog" aria-hidden="true"></div>
@stop
@section('js')
<?php
    $guardname = Helper::get_guard();
    $url = url($guardname.'/store-products/dt');
?>
<script type='text/javascript'>
    $(function () {
        var oTable = $('#store-products-table').DataTable({
            processing: true,
            serverSide: true,
            ordering: true,
            columnDefs: [
                {
                    orderable: false,
                    targets: "no-sort"
                },
                { "width": "10px", "targets": 0 },
                { "width": "50px", "targets": 8 },
            ],
            order: [[ @if($guard_name == 'admin') 8 @else 7 @endif, "desc" ]],
            //autoWidth: false,
            ajax: {
                url: "{!! $url !!}",
                type: 'post',
                headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'},
                data: function ( d ) {
                    if( $('#store_filter').val() != undefined) {
                        d.store_id = $('#store_filter').val();
                    }
                    if( $('#price_approval').val() != undefined) {
                        d.price_approval = $('#price_approval').val();
                    }
                }
            },
            columns: [
                {
                    render: function (data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1;
                    }
                },
                {data: 'product.name', name: 'product.name', searchable: true},
                @if($guard_name == 'admin')
                    {data: 'store.store_fullname', name: 'store.store_fullname'},
                @endif
                {
                    render: function(data, type, row, meta) {
                        return "<span class='badge badge-info'>" + row.categories + "</span>"
                    }
                },
                {
                    data: 'stock',
                    name: 'stock',
                    render: function(data, type, row, meta) {
                        var st = row.stock;
                        if(row.stock < 1 || row.out_of_stock){
                            st += ' <i class="fa fa-times text-danger" title="Out of Stock" aria-hidden="true"></i>';
                        }
                        return st;
                    }
                },
                {data: 'unit_price', name: 'unit_price'},
                {data: 'ask_price', name: 'ask_price'},
                {
                    render: function(data, type, row, meta){
                        if(row.price_approved==2){
                            return "<span class='badge badge-danger'>Rejected</span>";
                        }
                        else if(row.price_approved==1){
                            return "<span class='badge badge-danger'>Pending</span>";
                        }
                        else{
                            return "";
                        }
                    }
                },
                {data: 'created_at', name: 'created_at', searchable: true},
                {data: 'actions', name: 'actions', searchable: false}
            ]
        });
        $(document).on("change", "#store_filter, #price_approval", function() {
            oTable.ajax.reload();
        });

    });
</script>
@stop
