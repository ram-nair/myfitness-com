@extends('adminlte::page')

@section('title', 'Edit Permission')

@section('content_header')
<div class="row mb-2">
    <div class="col-sm-6">
        <h1 class="m-0 text-dark">Edit Permissions</h1>
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
            {{ Form::model($permission, array('route' => array('admin.permissions.update', $permission->id), 'method' => 'PUT')) }}{{-- Form model binding to automatically populate our fields with permission data --}}
            <div class="card-body pad">
                <div class="form-group {{ $errors->has('name') ? 'has-error' : '' }}">
                    {{ Form::label('name', 'Permission Name') }}
                    {{ Form::text('name', null, array('class' => 'form-control')) }}
                    {!! $errors->first('name','<p class="text-danger"><strong>:message</strong></p>') !!}
                </div>
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