@extends('adminlte::page')

@section('title', 'Edit Sub Category')

@section('content_header')
<div class="row mb-2">
    <div class="col-sm-6">
        <h1 class="m-0 text-dark">Edit Sub Category</h1>
    </div><!-- /.col -->
</div><!-- /.row -->
@stop

@section('content')
{{ Form::model($subcategory, array('route' => array('admin.subcategories.update', $subcategory->id), 'enctype' => 'multipart/form-data', 'method' => 'PUT','class' => 'class-create')) }}
    @include ('admin.subcategory.form',['submitButtonText'=>'Update'])
{{ Form::close() }}
@stop
