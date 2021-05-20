@extends('adminlte::page')

@section('title', 'Add new vendor')

@section('content_header')
<div class="row mb-2">
    <div class="col-sm-6">
        <h1 class="m-0 text-dark">Add vendor</h1>
    </div><!-- /.col -->
</div><!-- /.row -->
@stop

@section('content')
{{ Form::open(array('url' => 'admin/vendors','enctype' => 'multipart/form-data', 'class' => 'class-create')) }}
@include ('admin.vendors.form')
{{ Form::close() }}
@stop
