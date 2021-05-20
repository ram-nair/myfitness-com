<div class="card card-outline card-info">
    <div class="overlay" style="display: none;">
        <i class="fas fa-2x fa-sync-alt fa-spin"></i>
    </div>
    <div class="card-body pad">
        <div class="row">
            <div class="col">
                <div class="form-group">
                    {{ Form::label('name', 'Name') }}
                    {{ Form::text('name', null, array('required','class' => 'form-control'.($errors->has('name') ? ' is-invalid' : ''))) }}
                    {!! $errors->first('name','<p class="text-danger"><strong>:message</strong></p>') !!}
                </div>

                <div class="form-group">
                    {{ Form::label('mobile', 'Mobile') }}
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fas fa-phone"></i></span>
                        </div>
                        {{ Form::number('mobile', null, array('required', 'minlength' => 7, 'maxlength' => 12, 'class' => 'form-control'.($errors->has('mobile') ? ' is-invalid' : ''))) }}
                        {!! $errors->first('mobile','<p class="text-danger"><strong>:message</strong></p>') !!}
                    </div>
                </div>

                <div class="form-group">
                    <label for="exampleInputFile">Image (Recommended : {{$imageSize['aspectRatioW']}}x{{$imageSize['aspectRatioH']}})</label>
                    <div class="input-group"><?php
                        if(!empty($vendor->image)) {
                            $img = $vendor->image;
                        } else {
                            $img = url('/')."/images/no-image.jpg";
                        }?>
                        <img class="img-preview-holder" src="{{$img}}" alt="Preview Image" />
                        <div class="custom-file">
                            <input type="file" name="images" data-rule-extension="jpg|png" data-msg-extension="Please select jpg or png image" class="image img-preview form-control-file custom-file-input" id="exampleInputFile">
                            <label class="custom-file-label" for="exampleInputFile">Choose file</label>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label class="" for="customSwitch3">Status</label>
                    <input type="checkbox" name="active" value="1" id="custdomSwitch3" @if (empty($vendor)) checked @endif @if (!empty($vendor) && $vendor->active == 1) checked @endif data-bootstrap-switch>
                </div>
            </div>

            <div class="col">
                <div class="form-group">
                    {{ Form::label('email', 'Email') }}
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                        </div>
                        {{ Form::email('email', null, array('required' ,'class' => 'form-control'.($errors->has('email') ? ' is-invalid' : ''))) }}
                        {!! $errors->first('email','<p class="text-danger"><strong>:message</strong></p>') !!}
                    </div>
                </div>

                <div class="form-group">
                    {{ Form::label('password', 'Password') }}<br>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fas fa-key"></i></span>
                        </div>
                        {{ Form::password('password', array('minlength' => 6, empty($vendor) ? "required" : "",'class' => 'form-control'.($errors->has('password') ? ' is-invalid' : ''))) }}
                        {!! $errors->first('password','<p class="text-danger"><strong>:message</strong></p>') !!}
                    </div>
                </div>

                <div class="form-group">
                    {{ Form::label('password', 'Confirm Password') }}<br>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fas fa-key"></i></span>
                        </div>
                        {{ Form::password('password_confirmation', array("data-rule-equalTo" => "#password", 'minlength' => 6, empty($vendor) ? "required" : "",'class' => 'form-control'.($errors->has('password_confirmation') ? ' is-invalid' : ''))) }}
                        {!! $errors->first('password_confirmation','<p class="text-danger"><strong>:message</strong></p>') !!}
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col form-group">
                {{ Form::label('Description', 'Description') }}<br>
                {!! Form::textarea('description',null,['class'=>'form-control editor-medium', 'rows' => 2, 'cols' => 40]) !!}
                {!! $errors->first('description','<p class="text-danger"><strong>:message</strong></p>') !!}
            </div>
        </div>
    </div>
    <div class="card-footer">
        {{ Form::submit(isset($submitButtonText) ? $submitButtonText : 'Create', array('class' => 'btn btn-info float-right')) }}
        <a href="{{ route('admin.vendors.index') }}" class="btn btn-default">Cancel</a>
    </div>
</div>

