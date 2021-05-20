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
{{ Form::open(array('url' => 'admin/cat-banners','enctype' => 'multipart/form-data', 'class' => 'class-create')) }}
@include ('admin.cat-banner.form')
{{ Form::close() }}
@stop
