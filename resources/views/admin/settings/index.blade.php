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
<div class="card card-primary">
    <div class="card-body">
        <div class="row">
            <div class="col-5 col-sm-3">
                <div class="nav flex-column nav-tabs h-100">
                    @foreach($settings as $setting)
                        <a class="nav-link {{ $loop->first ? 'active' : ''}}" id="vert-tabs-home-tab" data-toggle="pill"data-toggle="pill" href="#{{$setting->key}}" role="tab" aria-controls="vert-tabs-vat" aria-selected="true">{{ucwords(str_replace("_"," ",$setting->key))}}</a>
                    @endforeach
                </div>
            </div>
            <div class="col-7 col-sm-9">
                <div class="tab-content">
                    @foreach($settings as $setting)
                        <div class="tab-pane {{ $loop->first ? 'active' : ''}}" id="{{$setting->key}}">
                            <div class="tile">
                                {{ Form::open(array('url' => route('admin.settings.update'),'class' => 'class-create','enctype' => 'multipart/form-data',)) }}
                                    <div class="card card-outline card-info">
                                        <div class="overlay" style="display: none;">
                                            <i class="fas fa-2x fa-sync-alt fa-spin"></i>
                                        </div>
                                        <div class="card-body pad">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        {{ Form::label($setting->key, ucwords(str_replace("_"," ",$setting->key))) }}
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                    @if($setting->key=="site_logo")
                                                        <?php
                                                            if(!empty($setting->key="site_logo")) {
                                                                $img = $setting->value;
                                                            } else {
                                                                $img = url('/')."/images/no-image.jpg";
                                                            }?>
                                                        <img class="img-preview-holder" src="{{$img}}" alt="Preview Image" />
                                                    <div class="custom-file">
                                                        <input type="file" name="<?php echo $setting->key;?>" data-rule-extension="jpg|png" data-msg-extension="Please select jpg or png image" class="image img-preview form-control-file custom-file-input">
                                                        <label class="custom-file-label" for="exampleInputFile">Choose file</label>

                                                    @else
                                                    {{ Form::text($setting->key, config('settings.'.$setting->key), array('required'=>'','class' => 'form-control'.($errors->has($setting->key) ? ' is-invalid' : '' ))) }}
                                                    @endif
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    {{ Form::submit(isset($submitButtonText) ? $submitButtonText : 'Update', array('class' => 'btn btn-info float-left')) }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                {{ Form::close() }}
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@stop
