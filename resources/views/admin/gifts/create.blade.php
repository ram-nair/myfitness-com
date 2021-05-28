@extends('adminlte::page')

@section('title', 'Add Gift Cards')

@section('content_header')
<div class="row mb-2">
    <div class="col-sm-6">
        <h1 class="m-0 text-dark">Add Gift Cards</h1>
    </div><!-- /.col -->
</div><!-- /.row -->
@stop

@section('content')
@if(isset($errors) && $errors->any())
    <div class="alert alert-danger">
        @foreach ($errors->all() as $error)
            {{ $error }}
        @endforeach
    </div>
@endif
{{ Form::open(array('url' => 'admin/gifts', 'class' => 'class-create')) }}
    @include ('admin.gifts.form')
{{ Form::close() }}
@stop