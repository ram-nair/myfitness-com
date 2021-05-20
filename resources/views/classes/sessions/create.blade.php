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
@if(isset($errors) && $errors->any())
    <div class="alert alert-danger">
        @foreach ($errors->all() as $error)
            {{ $error }}
        @endforeach
    </div>
@endif
{{ Form::open(array('url' => "admin/generalclass/{$generalclass->id}/sessions",'enctype' => 'multipart/form-data','class' => 'class-create')) }}
<input type="hidden" value="{{ $type }}" name="type" />
@include ('classes.sessions.form')
{{ Form::close() }}
@endsection
