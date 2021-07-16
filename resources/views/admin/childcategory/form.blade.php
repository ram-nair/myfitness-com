<div class="card card-outline card-info">
    <div class="overlay" style="display: none;">
        <i class="fas fa-2x fa-sync-alt fa-spin"></i>
    </div>
    <div class="card-body pad">
        <div class="row">
            <div class="col-md-12">
               
        
                    <div class="form-group col-md-6">
                    {{ Form::label('parent_cat_id', 'Category') }}
 
                    <select id="cat_id" required class="form-control select2"> 
                                  <option value="">Select Category</option>
                                    @foreach($cats as $cat)
                                      <option value="{{ $cat->id }}"   @if(!empty($childcategory)) {{ $cat->id == $childcategory->parent->parent_cat_id ? "selected":"" }} @endif >{{ $cat->name }}</option>
                                    @endforeach
                                </select>

                        {!! $errors->first('business_type_category_id','<p class="text-danger"><strong>:message</strong></p>') !!}
                    </div>
                    <div class="form-group col-md-6">
                        {{ Form::label('subcategory_id', 'Sub Category') }}
                        <select class="select2 form-control" required name="parent_cat_id" id="subcategory_id"  value={{$category->parent_cat_id??''}}>
                            <option value="0">Select Sub Category</option>
                        </select>
                        {!! $errors->first('subcategory_id','<p class="text-danger"><strong>:message</strong></p>') !!}
                    </div>
                
                <div class="form-group col-md-6">
                    {{ Form::label('name', 'Child Category Name') }}
                    {{ Form::text('name', null, array('required','class' => 'form-control'.($errors->has('name') ? ' is-invalid' : '' ))) }}
                    {!! $errors->first('name','<p class="text-danger"><strong>:message</strong></p>') !!}
                </div>
            </div>
            <div class="row">
        <div class="form-group">
                    <label for="exampleInputFile">Image(Icon) (Recommended : {{$imageSize['aspectRatioW']}}x{{$imageSize['aspectRatioH']}})</label>
                    <div class="input-group"><?php
                        if(!empty($childcategory->image)) {
                            $img = $childcategory->image;
                            ?>
                             <img class="img-preview-holder" src="{{asset('uploads/category/images/'.$img)}}" alt="Preview Image" />
                       <?php  } else {
                             $img = url('/')."/images/no-image.jpg";
                            ?>
                            <img class="img-preview-holder" src="{{$img}}" alt="Preview Image" />
                       <?php  }?>
                       
                        <div class="custom-file">
                            <input type="file" name="image" data-rule-extension="jpg|png" data-msg-extension="Please select jpg or png image" class="image img-preview form-control-file custom-file-input" id="exampleInputFile">
                            <label class="custom-file-label" for="exampleInputFile">Choose file</label>
                        </div>
                    </div>
                </div>
            </div>

             <div class="form-group">
                    <label for="exampleInputFile">Sub Category Banner Image (Recommended : {{$imageSize['aspectRatioW']}}x{{$imageSize['aspectRatioH']}})</label>
                    <div class="input-group"><?php
                        if(!empty($childcategory->banner_image)) {
                            $img = $childcategory->banner_image;
                            ?>
                             <img class="img-preview-holder" src="{{asset('uploads/category/images/'.$img)}}" alt="Preview Image" />
                       <?php  } else {
                             $img = url('/')."/images/no-image.jpg";
                            ?>
                            <img class="img-preview-holder" src="{{$img}}" alt="Preview Image" />
                       <?php  }?>
                       
                        <div class="custom-file">
                            <input type="file" name="banner_images" data-rule-extension="jpg|png|jpeg" data-msg-extension="Please select jpg or png image" class="image img-preview form-control-file custom-file-input" id="exampleInputFile">
                            <label class="custom-file-label" for="exampleInputFile">Choose file</label>
                        </div>
                    </div>
                </div>
        </div>
  
            
    </div>
    <div class="card-footer">
        <a href="{{ route('admin.childcategories.index') }}" class="btn btn-default">Cancel</a>
        {{ Form::submit(isset($submitButtonText) ? $submitButtonText : 'Create', array('class' => 'btn btn-info float-right')) }}
    </div>
</div>

@section('js')
<script>
$(function(){
    var editId = {{ $category->parent_cat_id ?? "null" }};
    var currentId = {{ $category->id ?? "null" }};
    $('#business_type_category_id').on('change',function(){
        $('.overlay').show();
        var id = $(this).val();
        $.post('{{route('get-sub-categories')}}',{id:id},function(data){
            $('#parent_cat_id').html('');
            var options = "<option value=''>Main Category</option><optgroup label='Sub Categories'>";
            $('#parent_cat_id').append(options);
            $.each( data.data, function( key, value ) {
                var sel = key == editId ? 'selected' : '';                
                var dis = key == currentId ? 'disabled' : '';                
                $('#parent_cat_id').append("<option value='"+ key + "' "+sel+" "+dis+">"+ value + "</option");
            });
            $('#parent_cat_id').append("</optgroup>");
            // $('#parent_id').val($('#parent_id').attr('value'));
            $('.overlay').hide();
        });
    });

});
<?php
if(!empty($childcategory)){?>
 getSubcat(<?php echo $childcategory->parent_cat_id;?>,'<?php echo $childcategory->id;?>');
<?php }
?>
$(document).on('change','#cat_id',function () {
      var catId = $('#cat_id').val();
         getSubcat(catId,null)
      });

function getSubcat(catId,subId){
    $.ajax({
        url: '{{ url('admin/loadSubcat') }}/'+catId+'/'+subId,
        success: function(resp) {
            $('#subcategory_id').html(resp);
                    
            }
    });
 }
</script>
@endsection
