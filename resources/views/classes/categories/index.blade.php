@extends('adminlte::page')

@section('title', 'Catalog Management')

@section('content_header')
<div class="row mb-2">
    <div class="col-sm-6">
        <h1 class="m-0 text-dark">Catalog Management</h1>
    </div><!-- /.col -->
</div><!-- /.row -->
@stop

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card card-outline card-info">
            <div class="card-header">
                <h3 class="card-title">&nbsp;</h3>
                <!-- tools box -->
                <div class="card-tools">
                    <?php $user = Auth::guard('admin')->user(); ?>
                    <div class="btn-group">
                        @if($user->hasRole('super-admin','admin') || $user->hasPermissionTo('category_create'))
                        <a href="{{ route('admin.class-categories.create') }}" class="btn btn-success btn-sm">New Category</a>
                        @endif
                    </div>
                </div>
                <!-- /. tools -->
            </div>
            <div class="card-body pad">
                <div class="form-group col-4">
                    <label>Filter By category</label>
                    <select name="parent_cat_id" id="category_filter" class="form-control">
                        <option value="">All Categories</option>
                        <option value="main">Main Categories</option>
                        @foreach($parentCats as $key => $categoryName)
                        <option value="{{ $key }}" @if($parent_cat_id && $key == $parent_cat_id) selected @endif>{{ $categoryName }}</option>
                        @endforeach
                    </select>
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
                url: "{!! url('admin/class-categories/dt') !!}",
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
            oTable.ajax.reload();
        });
    
        
    });
</script>
@stop
