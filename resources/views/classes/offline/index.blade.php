@extends('adminlte::page')

@section('title', 'Offline Classes')

@section('content_header')
<div class="row mb-2">
    <div class="col-sm-6">
        <h1 class="m-0 text-dark">Offline Classes</h1>
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
            <div class="card-body pad">
                <table class="table table-striped table-bordered dt-responsive nowrap" style="width:100%" id="categories-table">
                    <thead>
                        <tr>
                            <th>SL.No</th>
                            <th>Name</th>
                            <th>Location</th>
                            <th>Community Name</th>
                            <th>Max Members</th>
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
            ajax: {               
                url: "{!! route('admin.offlineclass.datatable') !!}",
                type: 'post',
                headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'},
                data: function ( d ) {
                    // if( $('#category_filter').val() != "") {
                    //   d.parent_cat_id = $('#category_filter').val();
                    // }
                }
            },
            columns: [
                {
                    render: function (data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1;
                    }
                },
                {data: 'assets_name', name: 'assets_name'},
                {data: 'location', name: 'location', searchable: false},
                {data: 'community_name', name: 'community_name', searchable: false},
                {data: 'max_members', name: 'max_members', searchable: false},
                {data: 'actions', name: 'actions', searchable: false}
            ]
        });
    });
</script>
@stop
