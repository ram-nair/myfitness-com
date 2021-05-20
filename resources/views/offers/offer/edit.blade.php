@extends('adminlte::page')

@section('title', $pageTitle)

@section('content_header')
<div class="row mb-2">
    <div class="col-sm-6">
        <h1 class="m-0 text-dark">{{$pageTitle}}</h1>
    </div><!-- /.col -->
</div><!-- /.row -->
@stop
@section('content')

{{ Form::model($offer, array('route' => array('admin.offers.update', $offer->id), 'method' => 'POST', 'enctype' => 'multipart/form-data', 'class' => 'class-create')) }}
{{ method_field('PATCH') }}
<input type="hidden" name="id" value="{{ $offer->id }}">
@include ('offers.offer.form',['submitButtonText'=>'Update'])
{{ Form::close() }}
@stop




