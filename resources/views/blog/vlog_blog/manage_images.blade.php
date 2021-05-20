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
        <div class="card-header">

            <div class="card-tools">
                <div class="btn-group">
                    <a href="{{ route('admin.blog.index') }}" class="btn btn-danger">Cancel</a>
                </div>
            </div>
            <!-- /. tools -->
        </div>

        <div class="card-body">

            <div class="row">
        <div class="col-5 col-sm-3">
            <div class="nav flex-column nav-tabs h-100">
                <a class="nav-link" id="vert-tabs-home-tab" data-toggle="pill" href="#images" role="tab" aria-controls="vert-tabs-vat" aria-selected="false">Images</a>
                <a class="nav-link" id="vert-tabs-home-tab" data-toggle="pill" href="#videos" role="tab" aria-controls="vert-tabs-vat" aria-selected="false">Videos</a>
                <a class="nav-link" id="vert-tabs-home-tab" data-toggle="pill" href="#video_url" role="tab" aria-controls="vert-tabs-vat" aria-selected="false">Video URL</a>
               </div>
        </div>
        <div class="col-7 col-sm-9">
            <div class="tab-content">
                <div class="tab-pane active" id="images">
                    <div class="tile">
                        {{ Form::open(array('url' => route('admin.blog.post-images'),'class' => 'class-create','method' => 'POST', 'enctype' => 'multipart/form-data',)) }}
                         <input type="hidden" name="action_type" value="image">
                         <input type="hidden" name="blog_id" value="{{$blog_id}}">
                        <div class="card card-outline card-info">
                            <div class="overlay" style="display: none;">
                                <i class="fas fa-2x fa-sync-alt fa-spin"></i>
                            </div>
                            <div class="card-body pad">
                                <div class="row">
                                    <div class="col-md-8">
                                        <div class="form-group">
                                            <label for="exampleInputFile">Blog Image (Recommended : {{$imageSize['aspectRatioW']}}x{{$imageSize['aspectRatioH']}})</label>
                                            <div class="input-group">
                                                <div class="custom-file">
                                                    <input type="file" required name="blog_image" data-rule-extension="jpg|png" data-msg-extension="Please select jpg or png image" class="image img-preview form-control-file custom-file-input" id="exampleInputFile">
                                                    <label class="custom-file-label" for="exampleInputFile">Choose file</label>
                                                </div>
                                                {{ Form::submit(isset($submitButtonText) ? $submitButtonText : 'Upload', array('class' => 'btn btn-info')) }}

                                            </div>
                                            <?php
                                            $img = url('/')."/images/no-image.jpg";
                                            ?>
                                            <img class="img-preview-holder" src="{{$img}}" alt="Preview Image" />
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                        {{ Form::close() }}
                    </div>
                </div>
                <div class="tab-pane" id="videos">
                    <div class="tile">
                        {{ Form::open(array('url' => route('admin.blog.post-images'),'class' => 'class-create','method' => 'POST', 'enctype' => 'multipart/form-data',)) }}
                        <input type="hidden" name="action_type" value="video">
                        <input type="hidden" name="blog_id" value="{{$blog_id}}">
                        <div class="card card-outline card-info">
                            <div class="overlay" style="display: none;">
                                <i class="fas fa-2x fa-sync-alt fa-spin"></i>
                            </div>
                            <div class="card-body pad">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="exampleInputFile">Blog Video</label>
                                            <div class="input-group">
                                                <div class="custom-file">
                                                    <input type="file" required name="blog_video" data-rule-extension="flv|mp4|3gp|mov|avi|wmv" data-msg-extension="Please select flv,mp4,3gp,mov,avi,wmv video" class="image img-preview form-control-file custom-file-input" id="exampleInputFile">
                                                    <label class="custom-file-label" for="exampleInputFile">Choose file</label>
                                                </div>

                                            </div>
                                           </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="exampleInputFile">Blog Video Thumbnail (Recommended : {{$imageSize['aspectRatioW']}}x{{$imageSize['aspectRatioH']}})</label>
                                            <div class="input-group">
                                                <div class="custom-file">
                                                    <input type="file" required name="blog_video_thumbnail" data-rule-extension="jpg|png" data-msg-extension="Please select jpg or png image" class="image img-preview form-control-file custom-file-input" id="exampleInputFile">
                                                    <label class="custom-file-label" for="exampleInputFile">Choose file</label>
                                                </div>
                                                {{ Form::submit(isset($submitButtonText) ? $submitButtonText : 'Upload', array('class' => 'btn btn-info')) }}

                                            </div>
                                            <?php
                                            $img = url('/')."/images/no-image.jpg";
                                            ?>
                                            <img class="img-preview-holder" src="{{$img}}" alt="Preview Image" />
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                        {{ Form::close() }}
                    </div>
                </div>
                <div class="tab-pane" id="video_url">
                    <div class="tile">
                        {{ Form::open(array('url' => route('admin.blog.post-images'),'class' => 'class-create','method' => 'POST', 'enctype' => 'multipart/form-data',)) }}
                        <input type="hidden" name="action_type" value="video_url">
                        <input type="hidden" name="blog_id" value="{{$blog_id}}">
                        <div class="card card-outline card-info">
                            <div class="overlay" style="display: none;">
                                <i class="fas fa-2x fa-sync-alt fa-spin"></i>
                            </div>
                            <div class="card-body pad">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            {{ Form::label('video_url', 'Blog Video URL') }}
                                            {{ Form::text('video_url', null, array('required'=>'','class' => 'form-control'.($errors->has('video_url') ? ' is-invalid' : '' ))) }}
                                            {!! $errors->first('video_url','<p class="text-danger"><strong>:message</strong></p>') !!}
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="exampleInputFile">Video URL Thumbnail (Recommended : {{$imageSize['aspectRatioW']}}x{{$imageSize['aspectRatioH']}})</label>
                                            <div class="input-group">
                                                <div class="custom-file">
                                                    <input type="file" required name="blog_video_url_thumbnail" data-rule-extension="jpg|png" data-msg-extension="Please select jpg or png image" class="image img-preview form-control-file custom-file-input" id="exampleInputFile">
                                                    <label class="custom-file-label" for="exampleInputFile">Choose file</label>
                                                </div>
                                                {{ Form::submit(isset($submitButtonText) ? $submitButtonText : 'Upload', array('class' => 'btn btn-info')) }}

                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>

                        </div>
                        {{ Form::close() }}                    </div>
                </div>
            </div>
        </div>
    </div>
        </div>
    </div>
@stop
@section('js')

@stop
