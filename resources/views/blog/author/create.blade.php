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
{{ Form::open(array('url' => route('admin.blog-author.store'),'class' => 'class-create','method' => 'POST', 'enctype' => 'multipart/form-data',)) }}
    @include ('blog.author.form')
{{ Form::close() }}
@stop

@section('js')

<script>
$(function(){



    $('#show_disclaimer').on('switchChange.bootstrapSwitch', function (event, state) {
        if(state)
            $("#form_disclaimer").show();
        else
            $("#form_disclaimer").hide();
    });



});
</script>
@stop
