@extends('adminlte::page')

@section('title', $pageTitle)

@section('content_header')
<div class="row mb-2">
    <div class="col-sm-6">
        <h1 class="m-0 text-dark">{{$pageTitle}} Of {{$product->name}}</h1>
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
                <h3 class="card-title"><a href="{{ route('admin.products.edit',$product->id) }}">{{$product->name}}</a></h3>
            </div>
            <div class="card-body pad">
                <table class="table table-striped table-bordered dt-responsive nowrap" style="width:100%" id="products-table">
                    <thead>
                        <tr>
                            <th class="no-sort">SL.No</th>
                           
                            <th class="no-sort">Title</th>
                            <th class="no-sort">Email</th>
                            <th class="no-sort">Message</th>
                            <th>Status</th>
                            <th>Date Added</th>
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
<div class="modal fade" id="stocksModal" tabindex="-1" role="dialog" aria-labelledby="importModal">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="importModalLabel">Update Stock</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
            <form method="post">
                    <div class="card card-outline card-info">
                        <div class="card-body pad">
                            <div class="row">
                                <div class="col">
                                    <div class="form-group">
                                        <label for="exampleInputFile">Update Product Stock</label>
                                        <div class="input-group">
                                                <input type="hidden" name="productId" id="productId">
                                                <input type="text" name="quantity" class="form-control" id="update_stock" required>
                                                <label class="custom-label" for="update_stock"></label>
                                            
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                                 <button type="button" class="btn btn-info float-right update-stockbtn">Submit</button>
                        </div>
                    </div>
                    </form>
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
        ajax: {
            url: "{!! url('admin/products/reviewdt') !!}",
          // url: "{!! url('admin/products/dt') !!}",
            type: 'post',
            headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'},
            data: function ( d ) {
                d.id ='{{$id}}';
                }
        },
        columns: [
            {
                render: function (data, type, row, meta) {
                    return meta.row + meta.settings._iDisplayStart + 1;
                }
            },
            {data: 'title', name: 'title'},
            {data: 'email', name: 'email'},
            {data: 'message', name: 'message'},
            {data: 'status', name: 'status',
                render: function(data, type, row, meta) {
                    if(row.status){
                        return "Enabled  <input type='checkbox' value="+row.id+" name='status'  checked class='hot_deal'>";
                    }else{
                        return "Disabled  <input type='checkbox' value="+row.id+" name='status' class='hot_deal'>";
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
            url: "{{ url('admin/products/rstatus') }}/"+Product_id,
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
    $(document).on("change", "#sub_categories, #categories,#child_categories", function() {
        oTable.ajax.reload();
    });
}); 
 $(document).on('click','.update-stockbtn',function () {
        var pId = $('#productId').val();
        var stock = $('#update_stock').val();
        var token = $("input[name='_token']").val();
        if(stock==""){
            alert('update stock please');
            return false;
        }
       $("#stocksModal").modal("hide"); 
       $.ajax({
        url: '{{ route('admin.products.updateStock') }}',
        data :{'pid':pId,'stock':stock,
            _token:token},
        method:'POST',
        success: function(resp) {
            oTable.ajax.reload();
            $('#update_stock').val("");
            $('.overlay').hide();     
            }
           
    });
         return false;
      });
</script>
@stop
