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
        <ul>
            @foreach ($errors->all() as $error)
                <li>
                    {{ $error }}
                </li>
            @endforeach
        </ul>
    </div>
@endif
{{ Form::open(array('url' => 'admin/generalclass','enctype' => 'multipart/form-data','class' => 'class-create')) }}
    <input type="hidden" value="{{ $type }}" name="type" />
    @include ('classes.online.form')
{{ Form::close() }}
@endsection
