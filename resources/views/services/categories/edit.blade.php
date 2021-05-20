@extends('adminlte::page')

@section('title', 'Edit Category')

@section('content_header')
<div class="row mb-2">
    <div class="col-sm-6">
        <h1 class="m-0 text-dark">Edit Category</h1>
    </div><!-- /.col -->
</div><!-- /.row -->
@stop

@section('content')
{{ Form::model($service_category, array('route' => array('admin.service-categories.update', $service_category->id), 'enctype' => 'multipart/form-data', 'method' => 'PUT','class' => 'class-create')) }}
    @include ('services.categories.form',['submitButtonText'=>'Update'])
{{ Form::close() }}
@stop
