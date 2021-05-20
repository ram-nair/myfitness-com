<div class="card card-outline card-info">
    <div class="overlay" style="display: none;">
        <i class="fas fa-2x fa-sync-alt fa-spin"></i>
    </div>
    <div class="card-body pad">
        <div class="row">
            <div class="col-md-3">
                <div class="form-group">
                    {{ Form::label('vendor_name', 'Vender Name') }}
                    {{ Form::text('vendor_name', null, array('required'=>'','class' => 'form-control'.($errors->has('vendor_name') ? ' is-invalid' : '' ))) }}
                    {!! $errors->first('vendor_name','<p class="text-danger"><strong>:message</strong></p>') !!}
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    {{ Form::label('email', 'Email') }}
                    {{ Form::email('email', null, array('required'=>'','class' => 'form-control'.($errors->has('email') ? ' is-invalid' : '' ))) }}
                    {!! $errors->first('email','<p class="text-danger"><strong>:message</strong></p>') !!}
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    {{ Form::label('phone_number', 'Phone Number') }}
                    {{ Form::text('phone_number', null, array('required'=>'','class' => 'form-control'.($errors->has('phone_number') ? ' is-invalid' : '' ))) }}
                    {!! $errors->first('phone_number','<p class="text-danger"><strong>:message</strong></p>') !!}
                </div>
            </div>
            <div class="col-md-3">
            <div class="form-group">
                {{ Form::label('Status', 'Status') }}<br>
                <select class="form-control" name="status" id="status" >
                    <option value="1" @if(isset($blogAuthor) && $blogAuthor->status == 1) selected @endif>Enable</option>
                    <option value="0" @if(isset($blogAuthor) && $blogAuthor->status == 0) selected @endif>Disable</option>
                </select>
                {!! $errors->first('status','<p class="text-danger"><strong>:message</strong></p>') !!}
            </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="exampleInputFile">Profile Image (Recommended : {{$imageSize['aspectRatioW']}}x{{$imageSize['aspectRatioH']}})</label>
                    <div class="input-group">
                        <?php
                        if(!empty($blogAuthor->profile_image)) {
                            $img = $blogAuthor->profile_image;
                        } else {
                            $img = url('/')."/images/no-image.jpg";
                        }
                        ?>
                        <img class="img-preview-holder" src="{{$img}}" alt="Preview Image" />
                        <div class="custom-file">
                            <input type="file" name="profile_image" data-rule-extension="jpg|png" data-msg-extension="Please select jpg or png image" class="image img-preview form-control-file custom-file-input" id="exampleInputFile">
                            <label class="custom-file-label" for="exampleInputFile">Choose file</label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="exampleInputFile">Cover Image (Recommended : {{$imageSize['aspectRatioW']}}x{{$imageSize['aspectRatioH']}})</label>
                    <div class="input-group">
                        <?php
                        if(!empty($blogAuthor->cover_image)) {
                            $img = $blogAuthor->cover_image;
                        } else {
                            $img = url('/')."/images/no-image.jpg";
                        }
                        ?>
                        <img class="img-preview-holder" src="{{$img}}" alt="Preview Image" />
                        <div class="custom-file">
                            <input type="file" name="cover_image" data-rule-extension="jpg|png" data-msg-extension="Please select jpg or png image" class="image img-preview form-control-file custom-file-input" id="exampleInputFile">
                            <label class="custom-file-label" for="exampleInputFile">Choose file</label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    {{ Form::label('description', 'Description') }}
                    {{ Form::textarea('description', null, array('class' => 'form-control editor-medium')) }}
                </div>
            </div>
        </div>
    </div>
    <div class="card-footer">
        <a href="{{ route('admin.blog-author.index') }}" class="btn btn-default">Cancel</a>
        {{ Form::submit(isset($submitButtonText) ? $submitButtonText : 'Create', array('class' => 'btn btn-info float-right')) }}
    </div>
</div>
