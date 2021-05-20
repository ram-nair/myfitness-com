@extends('adminlte::page')

@section('title', 'Edit Store')

@section('content_header')
<div class="row mb-2">
    <div class="col-sm-6">
        <h1 class="m-0 text-dark">Edit Store</h1>
    </div><!-- /.col -->
</div><!-- /.row -->
@stop

@section('content')
{{ Form::model($store, array('route' => array('admin.stores.update', $store->id),'class' => 'class-create', 'method' => 'POST', 'enctype' => 'multipart/form-data')) }}
    {{ method_field('PATCH') }}
    @include ('admin.stores.form',['submitButtonText'=>'Update'])
{{ Form::close() }}
@stop