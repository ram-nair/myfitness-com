@extends('adminlte::page')

@section('title', 'Edit Product')

@section('content_header')
<div class="row mb-2">
    <div class="col-sm-6">
        <h1 class="m-0 text-dark">Edit Product</h1>
    </div><!-- /.col -->
</div><!-- /.row -->
@stop
@section('content')
    {{ Form::model($product, array('route' => array('admin.service-products.update', $product->id), 'enctype' => 'multipart/form-data','method' => 'POST', 'class' => 'class-create')) }}
    {{ method_field('PATCH') }}
    <input type="hidden" name="id" value="{{ $product->id }}">
    @include ('services.products.form', ['submitButtonText' => 'Update'])
    {{ Form::close() }}
@stop
