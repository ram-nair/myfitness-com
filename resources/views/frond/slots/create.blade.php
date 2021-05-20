@extends('adminlte::page')

@section('title', 'Add slots')

@section('content_header')
<div class="row mb-2">
    <div class="col-sm-6">
        <h1 class="m-0 text-dark">Add Slots</h1>
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
{{ Form::open(array('url' => 'store/slots', 'class' => 'class-create')) }}
    @include ('frond.slots.form')
{{ Form::close() }}
@stop