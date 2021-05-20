@extends('adminlte::page')

@section('title', 'Edit Service Banner')

@section('content_header')
<div class="row mb-2">
    <div class="col-sm-6">
        <h1 class="m-0 text-dark">Edit Service Banner </h1>
    </div><!-- /.col -->
</div><!-- /.row -->
@stop

@section('content')
{{ Form::model($serviceBanner, array('route' => array('admin.service-banner.update', $serviceBanner->id),'class' => 'class-create', 'method' => 'POST', 'enctype' => 'multipart/form-data')) }}
    {{ method_field('PATCH') }}
    @include ('admin.service-banner.form', ['submitButtonText' => 'Update'])
{{ Form::close() }}
@stop
@section('css')
<link rel="stylesheet" href="{{asset('vendor/icheck-bootstrap/icheck-bootstrap.min.css')}}">
@stop