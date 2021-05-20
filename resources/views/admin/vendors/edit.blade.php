@extends('adminlte::page')

@section('title', 'Edit Vendor')

@section('content_header')
<div class="row mb-2">
    <div class="col-sm-6">
        <h1 class="m-0 text-dark">Edit Vendor</h1>
    </div><!-- /.col -->
</div><!-- /.row -->
@stop

@section('content')
{{ Form::model($vendor, array('route' => array('admin.vendors.update', $vendor->id),'class' => 'class-create', 'method' => 'POST', 'enctype' => 'multipart/form-data')) }}
    {{ method_field('PATCH') }}
    @include ('admin.vendors.form', ['submitButtonText' => 'Update'])
{{ Form::close() }}
@stop
@section('css')
<link rel="stylesheet" href="{{asset('vendor/icheck-bootstrap/icheck-bootstrap.min.css')}}">
@stop