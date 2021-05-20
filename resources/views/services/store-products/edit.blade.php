@extends('adminlte::page')

@section('title', $pageTitle)

@section('content_header')
<div class="row mb-2">
    <div class="col-sm-6">
        <h1 class="m-0 text-dark">{{$pageTitle}}</h1>
    </div><!-- /.col -->
</div><!-- /.row -->
@stop

@section('content')
{{ Form::model($product, array('route' => array($guard_name.'.service-store-products.update', $product->id),'class' => 'class-create', 'method' => 'POST', 'enctype' => 'multipart/form-data')) }}
    <input type="hidden" name="id" value="{{ $product->id}}" />
    <input type="hidden" name="sid" value="{{ $req_id }}" />
    {{ method_field('PATCH') }}
    @include ('services.store-products.form', ['submitButtonText' => 'Update'])
{{ Form::close() }}
@stop