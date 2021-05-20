@extends('adminlte::page')

@section('title', 'Service Catalog Management')

@section('content_header')
<div class="row mb-2">
    <div class="col-sm-6">
        <h1 class="m-0 text-dark">Service Catalog Management</h1>
    </div><!-- /.col -->
</div><!-- /.row -->
@stop

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card card-outline card-info">
        <div class="overlay" style="display: none;">
                <i class="fas fa-2x fa-sync-alt fa-spin"></i>
            </div>
            <div class="card-header">
                <h3 class="card-title">&nbsp;</h3>
                <!-- tools box -->
                <div class="card-tools">
                    <?php $user = Auth::guard('admin')->user(); ?>
                    <div class="btn-group">
                        @if($user->hasRole('super-admin','admin') || $user->hasPermissionTo('category_create','admin'))
                        <a href="{{ route('admin.service-categories.create') }}" class="btn btn-success btn-sm">New Category</a>

                        &nbsp;<a href="{{ route('admin.audits.index','service-categories') }}" class="btn btn-success btn-sm">View Audit Log</a>
                       
                        @endif
                    </div>
                </div>
                <!-- /. tools -->
            </div>
            <div class="card-body pad">
            <div class="row">
            <div class="form-group col-4">
                    <label>Service Type </label>
                    <select class="form-control select2" name="service_type" id="service_type" >
                        <option value="" >Select Service Type</option>
                        <option value="service_type_1">Service Type 1</option>
                        <option value="service_type_2">Service Type 2</option>
                    </select>
                    
                </div>
                <div class="form-group col-4">
                    <label>Filter By category</label>
                    <select name="parent_cat_id" id="category_filter" class="form-control">
                        <option value="">All Categories</option>
                    </select>
                </div>
                </div>
                <table class="table table-striped table-bordered dt-responsive nowrap" style="width:100%" id="categories-table">
                    <thead>
                        <tr>
                            <th>SL.No</th>
                            <th>Category</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>
@stop
@section('js')
<script type='text/javascript'>
    $(function () {
        
        var oTable = $('#categories-table').DataTable({
            processing: true,
            serverSide: true,
            ordering: false,
            //autoWidth: false,
            ajax: {
               
                url: "{!! url('admin/service-categories/dt') !!}",
                type: 'post',
                headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'},
                data: function ( d ) {
                    if( $('#category_filter').val() != "") {
                        d.parent_cat_id = $('#category_filter').val();
                        
                    }
                    // if($('#category_filter').val()=="" && $('#parent_cat_id').val()!="")
                    // {
                    //     d.parent_cat_id = $('#parent_cat_id').val();
                    // }
                }
            },
            columns: [
                {
                    render: function (data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1;
                    }
                },
                {data: 'name', name: 'name'},
                {data: 'actions', name: 'actions', searchable: false}
            ]
        });
        
        $(document).on("change", "#category_filter", function() {
          $('.overlay').show();
            if(oTable.ajax.reload())
            {
                $('.overlay').hide();
            }
            
        });
    
        
    });
</script>
<script>
$(function(){

    $('#service_type').on('change',function(){
        $('.overlay').show();
        var id = $(this).val();
        $.post('{{route('get-typecategories')}}',{id:id},function(data){
            $('#category_filter').html('');
            var options = "<option value=''>Root Level</option>";
            $('#category_filter').append(options);
            $.each( data.data, function( key, value ) {              
                $('#category_filter').append("<option value='"+ key + "'>"+ value + "</option");
            });
            // $('#parent_id').val($('#parent_id').attr('value'));
            $('.overlay').hide();
        });
    });

   });
</script>

@stop
