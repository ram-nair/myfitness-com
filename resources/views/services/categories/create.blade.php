@extends('adminlte::page')

@section('title', 'Add new Category')

@section('content_header')
<div class="row mb-2">
    <div class="col-sm-6">
        <h1 class="m-0 text-dark">Add Category</h1>
    </div><!-- /.col -->
</div><!-- /.row -->
@endsection

@section('content')
{{ Form::open(array('route' => 'admin.service-categories.store','enctype' => 'multipart/form-data','class' => 'class-create')) }}
@include ('services.categories.form')
{{ Form::close() }}
@endsection
