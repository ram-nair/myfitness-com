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
{{ Form::model($session, array('route' => array('admin.sessions.update', ['generalclass' => $generalclass->id, 'session' => $session->id]), 'method' => 'POST', 'class' => 'class-create')) }}
    {{ method_field('PATCH') }}
    <input type="hidden" name="id" value="{{ $session->id }}">
    <input type="hidden" name="type" value="{{ $type }}">
    <div class="card card-outline card-info">
        <div class="overlay" style="display: none;">
            <i class="fas fa-2x fa-sync-alt fa-spin"></i>
        </div>
        <div class="card-body pad">
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        {{ Form::label('date range', 'Slot Date') }}
                        <div class="input-group">
                            {!! Form::text('slot_date',null, array("data-inputmask-inputformat" => "dd/mm/yyyy",  'id'=>'slot_date', 'class' => 'form-control'.($errors->has('slot_date') ? ' is-invalid' : ''))) !!}
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        {{ Form::label('start', "Start Time") }}<br>
                        <div class='input-group date' >
                            {{ Form::text("start_at", null, array("autocomplete" => "off", 'class' => 'timepicker form-control '.($errors->has("start_at") ? ' is-invalid' : ''))) }}
                        </div>
                        {!! $errors->first("start_at", '<p class="text-danger"><strong>:message</strong></p>') !!}
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        {{ Form::label('end', "End Time") }}<br>
                        <div class='input-group date' >
                            {{ Form::text("end_at", null, array("autocomplete" => "off", 'class' => 'timepicker form-control '.($errors->has("end_at") ? ' is-invalid' : ''))) }}
                        </div>
                        {!! $errors->first("end_at", '<p class="text-danger"><strong>:message</strong></p>') !!}
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        {{ Form::label('capacity', "Capacity") }}<br>
                        <div class='input-group capacity'>
                            {{ Form::text("capacity", null, array("autocomplete" => "off", 'class' => 'form-control '.($errors->has("capacity") ? ' is-invalid' : ''))) }}
                        </div>
                        {!! $errors->first("capacity", '<p class="text-danger"><strong>:message</strong></p>') !!}
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        {{ Form::label('online link', "Session Link") }}<br>
                        <div class='input-group capacity'>
                            {{ Form::text("online_link", null, array("autocomplete" => "off", 'class' => 'form-control '.($errors->has("online_link") ? ' is-invalid' : ''))) }}
                        </div>
                        {!! $errors->first("online_link", '<p class="text-danger"><strong>:message</strong></p>') !!}
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                    {{ Form::label('status', 'Status') }}
                        <div class='input-group capacity'>
                            {{ Form::checkbox('status', 1, null, ['data-bootstrap-switch', 'id'=>'status', 'class' => 'custom-control'] ) }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer">
            <a href="{{ route('admin.sessions.index', ['generalclass' => $generalclass->id, 'type' => $type]) }}" class="btn btn-default">Cancel</a>
            {{ Form::submit(isset($submitButtonText) ? $submitButtonText : 'Create', array('class' => 'btn btn-info float-right')) }}
        </div>
    </div>
{{ Form::close() }}
@stop
@section('js')
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/inputmask/4.0.9/jquery.inputmask.bundle.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.standalone.min.css" />
<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.5/jquery.timepicker.min.css">
<script src="//cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.5/jquery.timepicker.min.js"></script>
<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key={{config('settings.google_api_key')}}&libraries=places&language=en&callback=initMap" async defer></script>
<script type="text/javascript">
    $(function () {
        $('.timepicker').timepicker({
            timeFormat: 'h:mm p',
            interval: 15,
            dynamic: false,
            dropdown: true,
            scrollbar: true
        });
        $('#slot_date').datepicker({
            format: 'dd/mm/yyyy',
        });
        <?php if(!empty($session) && !empty($session->slot_date)){?>
            $("#slot_date").datepicker("setDate", "{{$session->slot_date->format('d/m/Y')}}");
        <?php }?>
    });
</script>
@stop