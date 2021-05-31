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
                <td>{{ $validation->values()['product_name']   }}</td>
                <td>{{ $validation->attribute() }}</td>
                <td>
                    <ul>
                        @foreach ($validation->errors() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </td>
                <td>{{ $validation->values()[$validation->attribute()] }}</td>
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
                <h3 class="card-title"></h3>
                <!-- tools box -->
                <div class="card-tools">
                    <?php $user = Auth::user(); ?>
                    <div class="btn-group">
                        @if($user->hasRole('super-admin') || $user->hasPermissionTo('ecomproduct_create'))
                        <a href="{{ asset('/imports/products.xlsx') }}" download class="btn btn-success btn-sm"><i class="fa fa-download" aria-hidden="true"></i>&nbsp;Download Sample Data</a>
                        <span aria-hidden="true">&nbsp;&nbsp;</span>
                        <a href="#" data-toggle="modal" data-target="#importModal" class="btn btn-success btn-sm">Import Products</a>
                        <span aria-hidden="true">&nbsp;&nbsp;</span>
                        <a href="{{ route('admin.products.create') }}" class="btn btn-success btn-sm">New Product</a>
                        &nbsp;
                         @endif
                    </div>
                </div>
                <!-- /. tools -->
            </div>
            <div class="card-body pad">
            <div class="row">
            <div class="form-group col-4">
                    <label>Filter By category</label>
                    <select name="category_id" id="categories" class="form-control select2">
                        <option value="">Select Category</option>
                        @foreach($categories as $key => $categoryName)
                        <option value="{{ $key }}">{{ $categoryName }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group col-4">
                    <label>Filter By sub category</label>
                    <select name="sub_categories" id="sub_categories" class="form-control select2">
                    <option value="">Select Sub Category</option>
                    </select>
                </div>

                <div class="form-group col-4">
                    <label>Filter By Child category</label>
                    <select name="child_categories" id="child_categories" class="form-control select2">
                    <option value="">Select Child Category</option>
                    </select>
                </div>
                </div>
                <table class="table table-striped table-bordered dt-responsive nowrap" style="width:100%" id="products-table">
                    <thead>
                        <tr>
                            <th class="no-sort">SL.No</th>
                            <th>Name</th>
                            <th class="no-sort">SKU</th>
                            <th class="no-sort">Brand</th>
                            <th class="no-sort">Categories</th>
                            <th class="no-sort">Unit Price</th>
                            <th class="no-sort">Qty</th>
                            <th>Status</th>
                            <th>Show in Home/Main List</th>
                            <th>Date Added</th>
                            <th class="no-sort">Actions</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>
<!-- Modal -->
<div class="modal fade" id="storeModal" tabindex="-1" role="dialog" aria-labelledby="storeModal">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title" id="storeModalLabel">Add Product to Store</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        </div>
        <div class="modal-body">
            <h5 id="product_name"></h5>
            {{ Form::open(array('route' => array('admin.products.store-save'), 'method' => 'POST', 'class' => 'class-create')) }}
                <input type="hidden" name="id" id="product_id">
                <div class="card card-outline card-info">
                    <div class="overlay" style="display: none;">
                        <i class="fas fa-2x fa-sync-alt fa-spin"></i>
                    </div>
                    <div class="card-body pad">
                        <div class="row">
                            <div class="col">
                                <div class="form-group">
                                    {{ Form::label('store', 'Store') }}
                                   
                                </div>
                                <div class="form-group">
                                    {{ Form::label('stock', 'Stock') }}
                                    {{ Form::text('stock', null, array('required','class' => 'form-control'.($errors->has('stock') ? ' is-invalid' : '' ))) }}
                                    {!! $errors->first('stock','<p class="text-danger"><strong>:message</strong></p>') !!}
                                </div>
                                <div class="form-group">
                                    {{ Form::label('quantity-per-person', 'Quantity Person') }}
                                    {{ Form::text('quantity_per_person', null, array('required','class' => 'form-control'.($errors->has('quantity_per_person') ? ' is-invalid' : '' ))) }}
                                    {!! $errors->first('quantity_per_person','<p class="text-danger"><strong>:message</strong></p>') !!}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <a href="{{ route('admin.products.index') }}" data-dismiss="modal" class="btn btn-default">Cancel</a>
                        {{ Form::submit(isset($submitButtonText) ? $submitButtonText : 'Save Changes', array('class' => 'btn btn-info float-right')) }}
                    </div>
                </div>
            {{ Form::close() }}
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
                {{ Form::open(array('route' => array('admin.products.import'), 'method' => 'POST', 'class' => 'class-create', 'enctype' => 'multipart/form-data')) }}
                    <div class="card card-outline card-info">
                        <div class="overlay" style="display: none;">
                            <i class="fas fa-2x fa-sync-alt fa-spin"></i>
                        </div>
                        <div class="card-body pad">
                            <div class="row">
                                <div class="col">
                                    <div class="form-group">
                                        <label for="exampleInputFile">Products</label>
                                        <div class="input-group">
                                            <div class="custom-file">
                                                <input type="file" name="products_csv" class="form-control-file custom-file-input" id="exampleInputFile" required>
                                                <label class="custom-file-label" for="exampleInputFile"></label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <a href="{{ route('admin.products.index') }}" data-dismiss="modal" class="btn btn-default">Cancel</a>
                            {{ Form::submit(isset($submitButtonText) ? $submitButtonText : 'Import', array('class' => 'btn btn-info float-right')) }}
                        </div>
                    </div>
                {{ Form::close() }}
            </div>
        </div>
    </div>
</div>
@stop

@section('js')
<script type='text/javascript'>
$(function () {
    oTable = $('#products-table').DataTable({
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
        order: [[ 7, "dsc" ]],
        ajax: {
            url: "{!! url('admin/products/dt') !!}",
            type: 'post',
            headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'},
            data: function ( d ) {
                if( $('#sub_categories').val() != undefined) {
                    d.sub_cat_id = $('#sub_categories').val();
                }
                if( $('#categories').val() != undefined) {
                    d.cat_id = $('#categories').val();
                }
                if( $('#child_categories').val() != undefined) {
                    d.child_id = $('#child_categories').val();
                }
            }
        },
        columns: [
            {
                render: function (data, type, row, meta) {
                    return meta.row + meta.settings._iDisplayStart + 1;
                }
            },
            {data: 'name', name: 'name'},
            {data: 'sku', name: 'sku'},
            {data: 'brand_id', name: 'brand_id'},
            {data: 'categories', name: 'categories',
                render: function(data, type, row, meta) {
                    return "<span class='badge badge-info'>" + row.categories + "</span>";
                }
            },
            {data: 'unit_price', name: 'unit_price',
                render: function(data, type, row, meta) {
                    return "AED " + row.unit_price ;
                }
            },
            
            {data: 'quantity', name: 'quantity'},
            {data: 'status', name: 'status',
                render: function(data, type, row, meta) {
                    if(row.status){
                        return "Enabled";
                    }else{
                        return "Disabled";
                    }
                    
                }
            },
            {data: 'hot_sale', name: 'hot_sale',
                render: function(data, type, row, meta) {
                    if(row.hot_sale){
                        return "<input type='checkbox' value="+row.id+" name='hot_deal'  checked class='hot_deal form-control'>";
                    }else{
                        return "<input type='checkbox' value="+row.id+" name='hot_deal' class='hot_deal form-control'>";
                    }
                    
                }
            },
            
            {data: 'created_at', name: 'created_at', searchable: false},
            {data: 'actions', name: 'actions', searchable: false}
        ]
    });
   

    $("#tab1 #checkAll").click(function () {
        if ($("#tab1 #checkAll").is(':checked')) {
            $("#tab1 input[type=checkbox]").each(function () {
                $(this).prop("checked", true);
            });
        } else {
            $("#tab1 input[type=checkbox]").each(function () {
                $(this).prop("checked", false);
            });
        }
    });

    var table = $('#products-table').DataTable();
    table.on('click', '.hot_deal', function(){
        var Product_id = $(this).val();
        $.ajax({
            url: "{{ url('admin/products/inhome') }}/"+Product_id,
            beforeSend: function(){
                $('.overlay').show();
            }
        }).done(function(result) {
            // var data = table.row($tr).data();
            // console.log(data)
            table.ajax.reload();
            $('.overlay').hide();
        });
    });

    var editId = {{ $product->category_id ?? "null" }};
    $('#categories').on('change',function(){
        $('.overlay').show();
        var catId = $(this).val();
        var subId =null;
        $.ajax({
        url: '{{ url('admin/loadSubcat') }}/'+catId+'/'+subId,
        success: function(resp) {
            $('#sub_categories').html(resp);
            $('.overlay').hide();     
            }
           
    });
    });

    $(document).on("change", "#sub_categories, #categories,#child_categories", function() {
        oTable.ajax.reload();
    });
});
$(document).on('change','#sub_categories',function () {
      var catId = $('#sub_categories').val();
         getChild(catId,null)
      });
      function getChild(catId,subId){
    $.ajax({
        url: '{{ url('admin/loadChildcat') }}/'+catId+'/'+subId,
        success: function(resp) {
            $('#child_categories').html(resp);
         }
    });
 }
</script>
@stop
