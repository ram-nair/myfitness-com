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
{{ Form::open(array('url' => route('admin.pages.store'),'class' => 'class-create','method' => 'POST', 'enctype' => 'multipart/form-data',)) }}
    @include ('admin.pages.form')
{{ Form::close() }}
@stop

@section('js')

<script>
$(function(){

    $('#business_type_id').on('change', function () {
        if($(this).val()==3)//service
            $("#form_service_type").show();
        else{
            $("#form_service_type").hide();
            $("#service_type").val('');
        }

    });

    $('#show_disclaimer').on('switchChange.bootstrapSwitch', function (event, state) {
        if(state)
            $("#form_disclaimer").show();
        else
            $("#form_disclaimer").hide();
    });



});
</script>
@stop
