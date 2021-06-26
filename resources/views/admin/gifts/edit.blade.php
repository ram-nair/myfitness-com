@extends('adminlte::page')

@section('title', 'Edit Gift Cards')

@section('content_header')
<div class="row mb-2">
    <div class="col-sm-6">
        <h1 class="m-0 text-dark">Edit Gift Cards</h1>
    </div><!-- /.col -->
</div><!-- /.row -->
@stop

@section('content')
{{ Form::model($gift, array('route' => array('admin.gifts.update', $gift->id),'enctype' => 'multipart/form-data', 'method' => 'PUT','class' => 'class-create')) }}
    @include ('admin.gifts.form',['submitButtonText'=>'Update'])
{{ Form::close() }}
@stop
