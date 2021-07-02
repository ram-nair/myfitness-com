<div class="card card-outline card-info">
<div class="overlay" style="display: none;">
        <i class="fas fa-2x fa-sync-alt fa-spin"></i>
    </div>
    <div class="card-body pad">
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    {{ Form::label('name', 'Name') }}
                    {{ Form::text('name', null, array('required','class' => 'form-control'.($errors->has('name') ? ' is-invalid' : ''))) }}
                    {!! $errors->first('name','<p class="text-danger"><strong>:message</strong></p>') !!}
                </div>

                <div class="form-group">
                    {{ Form::label('description', 'Description') }}
                    {{ Form::textarea('description', null, array('class' => 'form-control'.($errors->has('description') ? ' is-invalid' : ''))) }}
                    {!! $errors->first('description','<p class="text-danger"><strong>:message</strong></p>') !!}
                </div>
            </div>
            <div class="row">
            <div class="form-group">
                    <label for="exampleInputFile">Image (Recommended : {{$imageSize['aspectRatioW']}}x{{$imageSize['aspectRatioH']}})</label>
                    <div class="input-group"><?php
                        if(!empty($brand->image)){
                            $img = $brand->image;
                        ?>
                        <img class="img-preview-holder" src="{{asset('uploads/brand/images/'.$img)}}" alt="Preview Image" />
                        <?php } else {
                          $img = url('/')."/images/no-image.jpg";?>
                        <img class="img-preview-holder" src="{{$img}}" alt="Preview Image" />
                       <?php  }?>
                        <div class="custom-file">
                            <input type="file" name="image" data-rule-extension="jpg|png" data-msg-extension="Please select jpg or png image" class="image img-preview form-control-file custom-file-input" id="exampleInputFile">
                            <label class="custom-file-label" for="exampleInputFile">Choose file</label>
                        </div>
                    </div>
                </div>
                
         </div>
         </div><div class="row">
         <div class="form-group">
                    {{ Form::label('featured', 'Status') }}
                    <select name="status" class="form-control" id="status">
                            <option value="1" @if(!@empty($brand) && $brand->status==1) selected @endif>Enabled</option>
                            <option value="0" @if(!@empty($brand)&& $brand->status!=1) selected @endif>Disabled</option>
                    </select>
                    {!! $errors->first('featured','<p class="text-danger"><strong>:message</strong></p>') !!}
                </div>
                </div>
        
        </div>
    </div>
    <div class="card-footer">

        <a href="{{ route('admin.brands.index') }}" class="btn btn-default">Cancel</a>
        {{ Form::submit(isset($submitButtonText) ? $submitButtonText : 'Create', array('class' => 'btn btn-info float-right')) }}
    </div>
</div>

