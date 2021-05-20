@extends('adminlte::page')

@section('title', 'Roles')

@section('content_header')
<div class="row mb-2">
    <div class="col-sm-6">
        <h1 class="m-0 text-dark">Available Roles</h1>
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
                    <a href="{{ URL::to('admin/roles/create') }}" class="btn btn-block bg-gradient-success btn-sm">Add Role</a>
                    @endif
                </div>
                <!-- /. tools -->
            </div>
            <!-- /.card-header -->
            <div class="card-body pad">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover" id="role-table">
                        <thead>
                            <tr>
                                <th>Role</th>
                                <th>Permissions</th>
                                <th>Operation</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($roles as $role)
                            <tr>

                                <td>{{ $role->name }}</td>

                                <td style="width: 70%;">
                                    {!! '<span class="label label-outlined">'.implode('</span> <span class="label label-outlined">', $role->permissions()->pluck('name')->toArray()).'</span>' !!}
                                </td>
                                <td style="width: 10%;">
                                    @if($user->hasRole('super-admin','admin') || $user->hasPermissionTo('role_update','admin'))
                                    <a href="{{ URL::to('admin/roles/'.$role->id.'/edit') }}" class="btn btn-outline-primary btn-xs" style="margin-right: 3px;">Edit</a>
                                    @endif
                                    @if($user->hasRole('super-admin','admin') || $user->hasPermissionTo('role_delete','admin'))
                                    <a href="{{ route('admin.roles.destroy', $role->id) }}" class="btn btn-outline-danger btn-xs destroy" style="margin-right: 3px;">Delete</a>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@stop