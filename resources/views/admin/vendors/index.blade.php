@extends('adminlte::page')

@section('title', 'Vendor Management')

@section('content_header')
<div class="row mb-2">
    <div class="col-sm-6">
        <h1 class="m-0 text-dark">Vendor Management</h1>
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
                       
                        @if($user->hasRole('super-admin','admin') || $user->hasPermissionTo('vendor_create','admin'))
                        <a href="{{ route('admin.vendors.create') }}" class="btn btn-success btn-sm">New Vendor</a>
                        &nbsp;<a href="{{ route('admin.audits.activityLog','vendor') }}" class="btn btn-success btn-sm">View Audit Log</a>
                        @endif
                    </div>
                </div>
                <!-- /. tools -->
            </div>
            <div class="card-body pad">
                <table class="table table-striped table-bordered dt-responsive nowrap" style="width:100%" id="users-table">
                    <thead>
                        <tr>
                            <th class="no-sort">SL.No</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th class="no-sort">Mobile</th>
                            <th class="no-sort">Status</th>
                            <th>Date/Time Added</th>
                            <th class="no-sort">Actions</th>
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
        oTable = $('#users-table').DataTable({
            processing: true,
            serverSide: true,
            ordering: true,
            //autoWidth: false,
            columnDefs: [
                {
                    orderable: false,
                    targets: "no-sort"
                },
                { "width": "10px", "targets": 0 },
                { "width": "100px", "targets": 5 },
                { "width": "50px", "targets": 6 },
            ],
            order: [[ 5, "asc" ]],
            ajax: {
                url: "{!! url('admin/vendors/dt') !!}",
                type: 'post',
                headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'}
            },
            columns: [
                {
                    render: function (data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1;
                    }
                },
                {data: 'name', name: 'name'},
                {data: 'email', name: 'email'},
                {data: 'mobile', name: 'mobile'},
                {data: 'active', name: 'active'},
                {data: 'created_at', name: 'created_at', searchable: false},
                {data: 'actions', name: 'actions', searchable: false}
            ]
        });

    });
</script>    
@stop