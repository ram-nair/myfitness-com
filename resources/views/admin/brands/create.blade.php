@extends('adminlte::page')

@section('title', 'Add new Brand')

@section('content_header')
<div class="row mb-2">
    <div class="col-sm-6">
        <h1 class="m-0 text-dark">Add Brand</h1>
    </div><!-- /.col -->
</div><!-- /.row -->
@stop

@section('content')
{{ Form::open(array('route' => 'admin.brands.store','enctype' => 'multipart/form-data','method' => 'POST','class' => 'class-create')) }}
@include ('admin.brands.form')
{{ Form::close() }}
@stop

