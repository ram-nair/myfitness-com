@extends('adminlte::page')

@section('title', 'Edit Customer')

@section('content_header')
<div class="row mb-2">
    <div class="col-sm-6">
        <h1 class="m-0 text-dark">Edit Customer</h1>
    </div><!-- /.col -->
</div><!-- /.row -->
@stop

@section('content')
{{ Form::model($user, array('route' => array('admin.users.update', $user->id), 'method' => 'PUT')) }}
@include ('admin.user.form',['submitButtonText'=>'Update'])
{{ Form::close() }}
@stop
@section('css')
<link rel="stylesheet" href="{{asset('vendor/icheck-bootstrap/icheck-bootstrap.min.css')}}">
@stop