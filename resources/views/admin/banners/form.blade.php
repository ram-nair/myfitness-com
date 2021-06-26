<div class="card card-outline card-info">
    <div class="overlay" style="display: none;">
        <i class="fas fa-2x fa-sync-alt fa-spin"></i>
    </div>
    <div class="card-body pad">
    <input type="hidden" name="type" id="types" value="{{$banner_type}}">
        
          <div class="row">
            <div class="form-group">
                <label for="exampleInputFile">Image (Recommended : {{$imageSize['aspectRatioW']}}x{{$imageSize['aspectRatioH']}})</label>
                <div class="input-group"><?php
                    if(!empty($banner->image)) {
                        $img = $banner->image;
                        ?>
                        <img class="img-preview-holder" src="{{asset('uploads/banner/images/'.$img)}}" alt="Preview Image" />
                       <?php  } 
                        else {
                           $img = url('/')."/images/no-image.jpg";
                        ?>
                         <img class="img-preview-holder" src="{{$img}}" alt="Preview Image" />
                        <?php }?>
                    <div class="custom-file">
                        <input type="file" name="images" data-rule-extension="jpg|png" @if(empty($banner)) required  @endif data-msg-extension="Please select jpg or png image" class="image img-preview form-control-file custom-file-input" id="exampleInputFile">
                        <label class="custom-file-label" for="exampleInputFile">Choose file</label>
                    </div>
                </div>
            </div>
            </div>
            
            <div class="row">
            
            <div class="form-group">
                {{ Form::label('url', 'URL (External OR Internal') }}
                <div class="input-group">
                <div class="input-group-prepend">                    
                    <span class="input-group-text"><i class="fas fa-link"></i></span>
                </div>
                {{ Form::text('url', null, array('class' => 'form-control'.($errors->has('url') ? ' is-invalid' : ''))) }}
                {!! $errors->first('url','<p class="text-danger"><strong>:message</strong></p>') !!}
             </div>
              <div class="form-group">
                    {{ Form::label('featured', 'Status') }}
                    <select name="status" class="form-control" id="status">
                            <option value="1" @if(!@empty($banner) && $banner->status==1) selected @endif>Enabled</option>
                            <option value="0" @if(!@empty($banner)&& $banner->status!=1) selected @endif>Disabled</option>
                    </select>
                    {!! $errors->first('featured','<p class="text-danger"><strong>:message</strong></p>') !!}
                </div>
                
        </div>
        </div>
        @if($banner_type==4)
      <div class="form-group">
            {{ Form::label('Description', 'Description') }}<br>
            {!! Form::textarea('description',null,['class'=>'form-control editor-medium', 'rows' => 2, 'cols' => 40]) !!}
            {!! $errors->first('description','<p class="text-danger"><strong>:message</strong></p>') !!}
        </div>
        <div class="form-group">
            {{ Form::label('Description', 'Button Name') }}<br>
            {{ Form::text('btn_text', null, array('class' => 'form-control'.($errors->has('name') ? ' is-invalid' : '' ))) }}
            {!! $errors->first('description','<p class="text-danger"><strong>:message</strong></p>') !!}
        </div>
        @endif
       
    </div>
    <div class="card-footer">
        {{ Form::submit(isset($submitButtonText) ? $submitButtonText : 'Create', array('class' => 'btn btn-info float-right')) }}
        <a href="{{ route('admin.banners.index') }}" class="btn btn-default">Cancel</a>
    </div>
</div>

