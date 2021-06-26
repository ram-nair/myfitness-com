<div class="card card-outline card-info">
    <div class="overlay" style="display: none;">
        <i class="fas fa-2x fa-sync-alt fa-spin"></i>
    </div>
    <div class="card-body pad">
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    {{ Form::label('name', 'Name') }}
                    {{ Form::text('name', null, array('required'=>'','class' => 'form-control'.($errors->has('name') ? ' is-invalid' : '' ))) }}
                    {!! $errors->first('name','<p class="text-danger"><strong>:message</strong></p>') !!}
                </div>
                
            </div>
            <div class="col-md-6">
                {{-- <div class="form-group ">
                    {{ Form::label('show_disclaimer', 'Show Disclaimer') }}
                    <div class="">
                        <input data-bootstrap-switch type="checkbox" name="show_disclaimer" id="show_disclaimer" value="1" {{isset($category)&&$category->show_disclaimer==1?'checked':''}} />
                    </div>
                    {!! $errors->first('show_disclaimer','<p class="text-danger"><strong>:message</strong></p>') !!}
                </div>--}}

                <div class="form-group" id="form_disclaimer" {!! isset($category) && $category->show_disclaimer == 1 ? '' : 'style="display:none;"' !!}>
                    {{ Form::label('disclaimer', 'Disclaimer') }}
                    {{ Form::textarea('disclaimer', null, array('class' => 'form-control editor-medium '.($errors->has('disclaimer') ? ' is-invalid' : ''))) }}
                    {!! $errors->first('disclaimer','<p class="text-danger"><strong>:message</strong></p>') !!}
                </div>

                <div class="form-group">
                    <label for="exampleInputFile">Image (Recommended : {{$imageSize['aspectRatioW']}}x{{$imageSize['aspectRatioH']}})</label>
                    <div class="input-group"><?php
                        if(!empty($category->image)) {
                            $img = $category->image;
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
            <div class="col-md-12">
                <div class="form-group">
                    {{ Form::label('description', 'Description') }}
                    {{ Form::textarea('description', null, array('class' => 'form-control editor-medium '.($errors->has('description') ? ' is-invalid' : ''))) }}
                    {!! $errors->first('description','<p class="text-danger"><strong>:message</strong></p>') !!}
                </div>
                <div class="form-group col">
                    {{ Form::label('featured', 'Featured') }}
                    <select name="featured" class="form-control" style="width:100%"  id="featured">
                            <option value="1" @if(!@empty($category) && $category->featured==1) selected @endif>Enabled</option>
                            <option value="0" @if(!@empty($category)&& $category->featured!=1) selected @endif>Disabled</option>
                    </select>
                    {!! $errors->first('featured','<p class="text-danger"><strong>:message</strong></p>') !!}
                </div>
                <div class="form-group col">
                    {{ Form::label('featured', 'Status') }}
                    <select name="status" class="form-control" style="width:100%"  id="status">
                            <option value="1" @if(!@empty($category) && $category->status==1) selected @endif>Enabled</option>
                            <option value="0" @if(!@empty($category)&& $category->status!=1) selected @endif>Disabled</option>
                    </select>
                    {!! $errors->first('featured','<p class="text-danger"><strong>:message</strong></p>') !!}
                </div>
            </div>
        </div>
    </div>
    <div class="card-footer">
        <a href="{{ route('admin.categories.index') }}" class="btn btn-default">Cancel</a>
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


    $("#business_type_category_id").trigger('change');
    $('#is_service').on('switchChange.bootstrapSwitch', function (event, state) {
        if(state)
            $("#form_service_type").show();
        else
            $("#form_service_type").hide();
    });

    $('#show_disclaimer').on('switchChange.bootstrapSwitch', function (event, state) {
        if(state)
            $("#form_disclaimer").show();
        else
            $("#form_disclaimer").hide();
    });
});
</script>
@endsection
