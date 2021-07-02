@extends('adminlte::page')

@section('title', 'Child Category Management')

@section('content_header')
<div class="row mb-2">
    <div class="col-sm-6">
        <h1 class="m-0 text-dark">Child Category Management</h1>
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
                        <a href="{{ route('admin.childcategories.create') }}" class="btn btn-success btn-sm">New Child Category</a>
                       @endif
                    </div>
                </div>
                <!-- /. tools -->
            </div>
            <div class="card-body pad">
            <div class="row card-body pad">
                <div class="form-group col-4">
                    <label>Filter By category</label>
                    <select name="parent_cat_id" id="category_filter" class="form-control">
                        <option value="">All Categories</option>
                        @foreach($cats as $key => $categoryName)
                         <option value="{{ $key }}">{{ $categoryName }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group col-4">
                    <label>Filter By Sub category</label>
                    <select name="sub_cat_id" id="sub_cat_id" class="form-control">
                        <option value="">All Sub Categories</option>
                       
                    </select>
                </div>
                </div>
                <table class="table table-striped table-bordered dt-responsive nowrap" style="width:100%" id="categories-table">
                    <thead>
                        <tr>
                            <th>SL.No</th>
                            <th>Sub Category Name</th>
							<th>child Category Name</th>
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
                url: "{!! url('admin/childcategories/dt') !!}",
                type: 'post',
                headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'},
                data: function ( d ) {
                    d.parent_cat_id = {{$parent_id}};
                    if( $('#sub_cat_id').val() != "") {
                        d.sub_cat_id = $('#sub_cat_id').val();
                    }
                   
                    if($('#category_filter').val()!="")
                     {
                         d.parent_cat_id = $('#category_filter').val();
                     }
                }
            },
            columns: [
                {
                    render: function (data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1;
                    }
                },
                { data: 'category', searchable: false, orderable: false},
                {data: 'name', name: 'name'},
                {data: 'actions', name: 'actions', searchable: false}
            ]
        });
        
        $(document).on("change", "#sub_cat_id","#category_filter", function() {
            oTable.ajax.reload();
        });
    
        
    });
$('#category_filter').on('change', function(){
   var category_id = $(this).val();
    getSubcat(category_id,null);
});
function getSubcat(catId,subId){
    $.ajax({
        url: '{{ url('admin/loadSubcat') }}/'+catId+'/'+subId,
        success: function(resp) {
            $('#sub_cat_id').html(resp);
                    
            }
    });
 }
</script>
@stop
