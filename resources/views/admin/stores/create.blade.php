@extends('adminlte::page')

@section('title', 'Add new Store')

@section('content_header')
<div class="row mb-2">
    <div class="col-sm-6">
        <h1 class="m-0 text-dark">Add Store</h1>
    </div><!-- /.col -->
</div><!-- /.row -->
@stop

@section('content')
{{-- @if(isset($errors) && $errors->any())
    <div class="alert alert-danger alert-dismissible">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif --}}
{{ Form::open(array('url' => 'admin/stores', 'enctype' => 'multipart/form-data', 'class' => 'class-create')) }}
    @include ('admin.stores.form')
{{ Form::close() }}
@stop