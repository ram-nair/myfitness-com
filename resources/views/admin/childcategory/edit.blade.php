@extends('adminlte::page')

@section('title', 'Edit Child Category')

@section('content_header')
<div class="row mb-2">
    <div class="col-sm-6">
        <h1 class="m-0 text-dark">Edit Child Category(Level -3)</h1>
    </div><!-- /.col -->
</div><!-- /.row -->
@stop

@section('content')
{{ Form::model($childcategory, array('route' => array('admin.childcategories.update', $childcategory->id), 'enctype' => 'multipart/form-data', 'method' => 'PUT','class' => 'class-create')) }}
    @include ('admin.childcategory.form',['submitButtonText'=>'Update'])
{{ Form::close() }}
@stop
