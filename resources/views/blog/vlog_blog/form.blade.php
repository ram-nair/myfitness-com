<div class="card card-outline card-info">
    <div class="overlay" style="display: none;">
        <i class="fas fa-2x fa-sync-alt fa-spin"></i>
    </div>
    <div class="card-body pad">
        <div class="row">

                
                <div class="col-md-3">
                    <div class="form-group">
                        {{ Form::label('category_id', 'Category') }}<br>
                        <select class='form-control select2' required name="category_id" id="category_id" >
                            <option value="">Select Category</option>
                            @foreach($categories as $category)
                                <option value="{{$category->id}}" @if(isset($vlogBlog) && $vlogBlog->category_id == $category->id) selected @endif>{{$category->name}}</option>
                            @endforeach
                        </select>
                        {!! $errors->first('category_id','<p class="text-danger"><strong>:message</strong></p>') !!}
                    </div>
                </div>
                
               

                 <div class="col-md-9">
                <div class="form-group">
                    {{ Form::label('title', 'Title') }}
                    {{ Form::text('title', null, array('required'=>'','class' => 'form-control'.($errors->has('title') ? ' is-invalid' : '' ))) }}
                    {!! $errors->first('title','<p class="text-danger"><strong>:message</strong></p>') !!}
                </div>
                 </div>

                 <div class="form-group">
                    <label for="exampleInputFile">Image (Recommended : {{$imageSize['aspectRatioW']}}x{{$imageSize['aspectRatioH']}})</label>
                    <div class="input-group"><?php
                        if(!empty($vlogBlog->image)) {
                            $img = $vlogBlog->image;?>
                            <img class="img-preview-holder" src="{{asset('uploads/vlogBlog/images/'.$img)}}" alt="Preview Image" />
                          <?php } else {
                            $img = url('/')."/images/no-image.jpg";?>
                            <img class="img-preview-holder" src="{{$img}}" alt="Preview Image" />
                         <?php }?>
                        <div class="custom-file">
                        <input type="file"  name="blog_image" data-rule-extension="jpg|png|jpeg" data-msg-extension="Please select jpg or png image" class="image img-preview form-control-file custom-file-input" id="exampleInputFile">
                         <label class="custom-file-label" for="exampleInputFile">Choose file</label>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="exampleInputFile">Blog Banner Image (Recommended : {{$imageSize['aspectRatioW']}}x{{$imageSize['aspectRatioH']}})</label>
                    <div class="input-group">
                     <?php
                        if(!empty($vlogBlog->banner_image)) {
                            $img = $vlogBlog->banner_image;
                            ?>
                             <img class="img-preview-holder" src="{{asset('uploads/vlogBlog/images/'.$img)}}" alt="Preview Image" />
                       <?php  } else {
                             $img = url('/')."/images/no-image.jpg";
                            ?>
                            <img class="img-preview-holder" src="{{$img}}" alt="Preview Image" />
                       <?php  }?>
                       
                        <div class="custom-file">
                            <input type="file" name="banner_image" data-rule-extension="jpg|png|jpeg" data-msg-extension="Please select jpg or png image" class="image img-preview form-control-file custom-file-input" id="exampleInputFile">
                            <label class="custom-file-label" for="exampleInputFile">Choose file</label>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="form-group">
                    {{ Form::label('Status', 'Status') }}<br>
                    <select class="form-control" name="status" id="status" >
                        <option value="1" @if(isset($vlogBlog) && $vlogBlog->status == 1) selected @endif>Enable</option>
                        <option value="0" @if(isset($vlogBlog) && $vlogBlog->status == 0) selected @endif>Disable</option>
                    </select>
                    {!! $errors->first('status','<p class="text-danger"><strong>:message</strong></p>') !!}
                </div>
            </div>
                <div class="col-md-12">
                <div class="form-group">
                    {{ Form::label('description', 'Description') }}
                    {{ Form::textarea('description', null, array('required'=>'','class' => 'form-control editor-medium '.($errors->has('description') ? ' is-invalid' : ''))) }}
                    {!! $errors->first('description','<p class="text-danger"><strong>:message</strong></p>') !!}
                </div>
                </div>
            </div>
        </div>

    <div class="card-footer">
        <a href="{{ route('admin.blog.index') }}" class="btn btn-default">Cancel</a>
        {{ Form::submit(isset($submitButtonText) ? $submitButtonText : 'Create', array('class' => 'btn btn-info float-right')) }}
    </div>
</div>
