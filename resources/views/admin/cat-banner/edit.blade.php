@extends('adminlte::page')

@section('title', 'Edit Home Banner')

@section('content_header')
<div class="row mb-2">
    <div class="col-sm-6">
        <h1 class="m-0 text-dark">Edit Home Banner </h1>
    </div><!-- /.col -->
</div><!-- /.row -->
@stop

@section('content')
{{ Form::model($ecommerceBanner, array('route' => array('admin.cat-banners.update', $ecommerceBanner->id),'class' => 'class-create', 'method' => 'POST', 'enctype' => 'multipart/form-data')) }}
    {{ method_field('PATCH') }}
    @include ('admin.cat-banner.form', ['submitButtonText' => 'Update'])
{{ Form::close() }}
@stop
@section('css')
<link rel="stylesheet" href="{{asset('vendor/icheck-bootstrap/icheck-bootstrap.min.css')}}">
@stop