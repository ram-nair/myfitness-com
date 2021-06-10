@extends('adminlte::page')

@section('title', 'Add New Admin User')

@section('content_header')
<div class="row mb-2">
    <div class="col-sm-6">
        <h1 class="m-0 text-dark">Add New Admin User</h1>
    </div><!-- /.col -->
</div><!-- /.row -->
@stop

@section('content')
{{ Form::open(array('url' => 'admin/adminusers')) }}
@include ('admin.users.form')
{{ Form::close() }}
@stop
@section('css')
<link rel="stylesheet" href="{{asset('vendor/icheck-bootstrap/icheck-bootstrap.min.css')}}">
@stop