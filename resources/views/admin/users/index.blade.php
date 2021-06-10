@extends('adminlte::page')

@section('title', 'Admin Users')

@section('content_header')
<div class="row mb-2">
    <div class="col-sm-6">
        <h1 class="m-0 text-dark">Admin Users</h1>
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
                        @if($user->hasRole('super-admin','admin') || $user->hasPermissionTo('role_read','admin'))
                        <a href="{{ route('admin.roles.index') }}" class="btn btn-success btn-sm">Roles</a>
                        @endif
                        @if($user->hasRole('super-admin','admin') || $user->hasPermissionTo('permission_read','admin'))
                        <a href="{{ route('admin.permissions.index') }}" class="btn btn-success btn-sm">Permissions</a>
                        @endif
                        @if($user->hasRole('super-admin','admin') || $user->hasPermissionTo('user_create','admin'))
                        <a href="{{ route('admin.adminusers.create') }}" class="btn btn-success btn-sm">New Admin User</a>
                        @endif
                    </div>
                </div>
                <!-- /. tools -->
            </div>
            <div class="card-body pad">
                <table class="table table-striped table-bordered dt-responsive nowrap" style="width:100%" id="users-table">
                    <thead>
                        <tr>
                            <th>SL.No</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Date/Time Added</th>
                            <th>User Roles</th>
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
        oTable = $('#users-table').DataTable({
            processing: true,
            serverSide: true,
            ordering: false,
            //autoWidth: false,
            ajax: {
                url: "{!! route('admin.adminusers.datatable') !!}",
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
                {data: 'created_at', name: 'created_at', searchable: false},
                {data: 'roles', name: 'roles', searchable: false},
                {data: 'actions', name: 'actions', searchable: false}
            ]
        });

    });
</script>    
@stop