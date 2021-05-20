@extends('adminlte::page')

@section('title', 'Add new Store')

@section('content_header')
<div class="row mb-2">
    <div class="col-sm-6">
        <h1 class="m-0 text-dark">Add Slots</h1>
    </div><!-- /.col -->
</div><!-- /.row -->
@stop

@section('content')
{{ Form::open(array('url' => 'store/drops', 'class' => 'class-create')) }}
    @include ('frond.drops.form')
{{ Form::close() }}
@stop