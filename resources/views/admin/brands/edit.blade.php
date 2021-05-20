@extends('adminlte::page')

@section('title', 'Edit Brand')

@section('content_header')
<div class="row mb-2">
    <div class="col-sm-6">
        <h1 class="m-0 text-dark">Edit Brand</h1>
    </div><!-- /.col -->
</div><!-- /.row -->
@stop

@section('content')
{{ Form::model($brand, array('route' => array('admin.brands.update', $brand->id),'enctype' => 'multipart/form-data', 'method' => 'PUT','class' => 'class-create')) }}
@include ('admin.brands.form',['submitButtonText'=>'Update'])
{{ Form::close() }}
@stop
