@extends('adminlte::page')

@section('title', 'Add new Banner ')

@section('content_header')
<div class="row mb-2">
    <div class="col-sm-6">
        <h1 class="m-0 text-dark">Add Banner </h1>
    </div><!-- /.col -->
</div><!-- /.row -->
@stop

@section('content')
@if(isset($errors) && $errors->any())
    <div class="alert alert-danger alert-dismissible">
        @foreach ($errors->all() as $error)
            {{ $error }}
        @endforeach
    </div>
@endif
{{ Form::open(array('url' => 'admin/service-banner','enctype' => 'multipart/form-data', 'class' => 'class-create')) }}
@include ('admin.service-banner.form')
{{ Form::close() }}
@stop
