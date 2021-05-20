@extends('adminlte::page')

@section('title', 'Permissions')

@section('content_header')
<div class="row mb-2">
    <div class="col-sm-6">
        <h1 class="m-0 text-dark">Available Permissions</h1>
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
                    @if($user->hasRole('super-admin','admin') || $user->hasPermissionTo('permission_create','admin'))
                    <a href="{{ URL::to('admin/permissions/create') }}" class="btn btn-block bg-gradient-success btn-sm">Add Permission</a>
                    @endif
                </div>
                <!-- /. tools -->
            </div>
            <!-- /.card-header -->
            <div class="card-body pad">
                <table class="table table-bordered" id="perm-table">
                    <thead>                  
                        <tr>
                            <th>Permissions</th>
                            <th>Operation</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($permissions as $permission)
                        <tr>
                            <td>{{ $permission->name }}</td> 
                            <td>
                                @if($user->hasRole('super-admin','admin') || $user->hasPermissionTo('permission_update','admin'))
                                <a href="{{ URL::to('admin/permissions/'.$permission->id.'/edit') }}" class="btn btn-outline-primary btn-xs" style="margin-right: 3px;">Edit</a>
                                @endif

                                @if($user->hasRole('super-admin','admin') || $user->hasPermissionTo('permission_delete','admin'))
                                <a href="{{ route('admin.permissions.destroy', $permission->id) }}" class="btn btn-outline-danger btn-xs destroy" style="margin-right: 3px;">Delete</a>
                                @endif

                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <!-- /.card-body -->
        </div>
    </div>
</div>
@stop