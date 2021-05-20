@extends('adminlte::page')

@section('title', 'Add New Permission')

@section('content_header')
<div class="row mb-2">
    <div class="col-sm-6">
        <h1 class="m-0 text-dark">Add Permissions</h1>
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
                </div>
                <!-- /. tools -->
            </div>
            <!-- /.card-header -->
            {{ Form::open(array('url' => 'admin/permissions','class'=>'form-horizontal','role'=>'form')) }}
            <div class="card-body pad">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group {{ $errors->has('name') ? 'has-error' : '' }}">
                            {{ Form::label('name', 'Name') }}
                            {{ Form::text('name', '', array('class' => 'form-control')) }}
                            {!! $errors->first('name','<p class="text-danger"><strong>:message</strong></p>') !!}
                        </div>
                    </div>
                    <div class="col-md-6">
                        {{ Form::label('name', 'Actions') }}
                        <div class="form-group clearfix">
                            <div class="icheck-primary d-inline">
                                {{ Form::checkbox('permissions[]','create',null,['id'=>'perm1'] ) }}
                                <label for="perm1">Create</label>
                            </div>
                            <div class="icheck-primary d-inline">
                                {{ Form::checkbox('permissions[]','read',null,['id'=>'perm2'] ) }}
                                <label for="perm2">Read</label>
                            </div>
                            <div class="icheck-primary d-inline">
                                {{ Form::checkbox('permissions[]','update',null,['id'=>'perm3'] ) }}
                                <label for="perm3">Update</label>
                            </div>
                            <div class="icheck-primary d-inline">
                                {{ Form::checkbox('permissions[]','delete',null,['id'=>'perm4'] ) }}
                                <label for="perm4">Delete</label>
                            </div>
                        </div> 
                    </div>
                </div>
                @if(!$roles->isEmpty())
                <div class="row">
                    <div class="col-md-12">
                        <h4>Assign Permission to Roles</h4>

                        @foreach ($roles as $role) 
                        <div class="icheck-primary d-inline">
                            {{ Form::checkbox('roles[]',  $role->id,null, ['id'=>$role->name] ) }}
                            {{ Form::label($role->name, ucfirst($role->name)) }}
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>
            <div class="card-footer">
                {{ Form::submit('submit', array('class' => 'btn btn-info')) }}
                <a href="{{ route('admin.permissions.index') }}" class="btn btn-default float-right">Cancel</a>
            </div>
            {{ Form::close() }}
        </div>
    </div>
</div>
@stop
@section('css')
<link rel="stylesheet" href="{{asset('vendor/icheck-bootstrap/icheck-bootstrap.min.css')}}">
@stop