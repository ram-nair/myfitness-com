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
{{ Form::model($category, array('route' => array('admin.categories.update', $category->id), 'enctype' => 'multipart/form-data', 'method' => 'PUT','class' => 'class-create')) }}
    @include ('admin.categories.form',['submitButtonText'=>'Update'])
{{ Form::close() }}
@stop
