@extends('adminlte::page')

@section('title', 'Add new Home Banner ')

@section('content_header')
<div class="row mb-2">
    <div class="col-sm-6">
        <h1 class="m-0 text-dark">Add Home Banner </h1>
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
{{ Form::open(array('url' => 'admin/banners', 'enctype' => 'multipart/form-data', 'class' => 'class-create')) }}
@include ('admin.banners.form')
{{ Form::close() }}
@stop
