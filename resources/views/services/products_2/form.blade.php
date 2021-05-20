<div class="card card-outline card-info">
    <div class="overlay" style="display: none;">
        <i class="fas fa-2x fa-sync-alt fa-spin"></i>
    </div>
    <div class="card-body pad">
        <div class="row">
            <div class="form-group col">
             <input type="hidden" name="service_type" value="{{$service_type}}">
                {{ Form::label('name', 'Name') }}
                {{ Form::text('name', null, array('required'=>'','class' => 'form-control'.($errors->has('name') ? ' is-invalid' : '' ))) }}
                {!! $errors->first('name','<p class="text-danger"><strong>:message</strong></p>') !!}
            </div>
            <div class="form-group col">
              @error('categories')
                <p>{{ $message }}</p>
                @enderror
                {{ Form::label('categories', 'Categories') }}
                
                <select name="category_id" class="select2 form-control" style="width:100%" required  id="categories">
                    <option value="">Select Category</option>
                    @foreach($categories as $catId => $category)
                        @php 
                        $dis="";
                        if(!in_array($catId,$subCats)){
                            $dis="disabled";
                        }
                        $check = "";
                        if(isset($product)) {
                            $check = $catId == $product->category_id ? 'selected' : '';
                        }
                        @endphp
                        <option value="{{ $catId }}" {{ $check }} {{$dis}}>{{ $category }}</option>
                    @endforeach
                </select>
                {!! $errors->first('categories','<p class="text-danger"><strong>:message</strong></p>') !!}
            </div>
            <div class="col-md-2">
                <div class="form-group">
                    {{ Form::label('unit price', 'Unit Price') }}
                    {{ Form::text('unit_price', null, array('required'=>'','class' => 'form-control'.($errors->has('unit_price') ? ' is-invalid' : '' ))) }}
                    {!! $errors->first('unit_price','<p class="text-danger"><strong>:message</strong></p>') !!}
                </div>
            </div>
            {{-- <div class="col">
                <div class="form-group text-center">
                    {{ Form::label('featured', 'Featured') }}
                    <div>
                        <input data-bootstrap-switch type="checkbox" name="featured" id="featured" value="1" {{isset($product)&&$product->featured == 1 ? 'checked' : ''}} />
                    </div>
                    {!! $errors->first('featured','<p class="text-danger"><strong>:message</strong></p>') !!}
                </div>
            </div> --}}
            <div class="col">
                <div class="form-group">
                    <label for="exampleInputFile">Image (Recommended : {{$imageSize['aspectRatioW']}}x{{$imageSize['aspectRatioH']}})</label>
                    <div class="input-group"><?php
                        if(!empty($product->image)) {
                            $img = $product->image;
                        } else {
                            $img = url('/')."/images/no-image.jpg";
                        }?>
                        <img class="img-preview-holder" src="{{$img}}" alt="Preview Image" />
                        <div class="custom-file">
                            <input type="file" name="image" data-rule-extension="jpg|png" data-msg-extension="Please select jpg or png image" class="image img-preview form-control-file custom-file-input" id="exampleInputFile">
                            <label class="custom-file-label" for="exampleInputFile">Choose file</label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <div class="form-group">
                    {{ Form::label('description', 'Description') }}
                    {{ Form::textarea('description', null, ['class'=>'form-control editor-medium', 'rows' => 2, 'cols' => 40]) }}
                    {!! $errors->first('description','<p class="text-danger"><strong>:message</strong></p>') !!}
                </div>
            </div>            
        </div>
    </div>
    <div class="card-footer">
        <a href="{{ route('admin.service-products.index') }}" class="btn btn-default">Cancel</a>
        {{ Form::submit(isset($submitButtonText) ? $submitButtonText : 'Create', array('class' => 'btn btn-info float-right')) }}
    </div>
</div>