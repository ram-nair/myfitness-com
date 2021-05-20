@extends('adminlte::page')

@section('title', 'Add new Child Category')

@section('content_header')
<div class="row mb-2">
    <div class="col-sm-6">
        <h1 class="m-0 text-dark">Add Child Category</h1>
    </div><!-- /.col -->
</div><!-- /.row -->
@endsection

@section('content')
{{ Form::open(array('route' => 'admin.childcategories.store','enctype' => 'multipart/form-data','class' => 'class-create')) }}
@include ('admin.childcategory.form')
{{ Form::close() }}
@endsection
