<div class="card card-outline card-info">
    <div class="overlay" style="display: none;">
        <i class="fas fa-2x fa-sync-alt fa-spin"></i>
    </div>
    <div class="card-body pad">
        <div class="row">
            <div class="form-group col">
                {{ Form::label('name', 'Name') }}
                {{ Form::text('name', null, array('required'=>'','class' => 'form-control'.($errors->has('name') ? ' is-invalid' : '' ))) }}
                {!! $errors->first('name','<p class="text-danger"><strong>:message</strong></p>') !!}
            </div>
            <div class="form-group col">
                <label>Service Type </label>
                <select class="form-control select2" name="service_type" id="service_type" >
                    <option value="" >Select Service Type</option>
                    <option value="service_type_1" @if((!empty($service_category)) && $service_category->service_type=='service_type_1') selected @endif>Service Type 1</option>
                    <option value="service_type_2" @if((!empty($service_category)) && $service_category->service_type=='service_type_2') selected @endif>Service Type 2</option>
                </select>
            </div>               
            <div class="form-group col" id="category_1">
                {{ Form::label('business_type_category_id', 'Business Type Category') }}
                <select name="business_type_category_id" id="business_type_category_id" required class="form-control">
                    <option value="">All Categories</option>
                </select>
            </div>
            <div class="form-group col" id="category_2" style="display:none;"> 
                {{ Form::label('business_type_category_id', 'Product Category or Sub Category') }}
                <select name="parent_cat_id" class="select2 form-control" style="width:100%" required  id="sub-categories">
                </select>
            </div>
        </div>
        <div class="row">
            <div class="form-group col">
                <label for="exampleInputFile">Image (Recommended : {{$imageSize['aspectRatioW']}}x{{$imageSize['aspectRatioH']}})</label>
                <div class="input-group"><?php
                    if(!empty($service_category->image)) {
                        $img = $service_category->image;
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
            <div class="form-group col">
                {{ Form::label('description', 'Description') }}
                {{ Form::textarea('description', null, array('class' => 'form-control editor-medium '.($errors->has('description') ? ' is-invalid' : ''))) }}
                {!! $errors->first('description','<p class="text-danger"><strong>:message</strong></p>') !!}
            </div>
        </div>
    </div>
    <div class="card-footer">
        <a href="{{ route('admin.service-products.index') }}" class="btn btn-default">Cancel</a>
        {{ Form::submit(isset($submitButtonText) ? $submitButtonText : 'Create', array('class' => 'btn btn-info float-right')) }}
    </div>
</div>

@section('js')
<script>
$(function(){
    $('#is_service').on('switchChange.bootstrapSwitch', function (event, state) {
        if(state)
            $("#form_service_type").show();
        else
            $("#form_service_type").hide();
    });

    var typtId = {{ $service_category->business_type_category_id ?? "null" }};
    var typeCatId = {{ $service_category->parent_cat_id ?? "null" }};
    var currentItem = {{ $service_category->id ?? "null" }};
    
    $('#service_type').on('change',function(){
        $('.overlay').show();
        var id = $(this).val();
        if(id == 'service_type_2'){
            $('#category_2').show();
        }else{
            $('#category_2').hide();
        }
        $.post('{{route('get-typecategories')}}',{id:id},function(data){
            $('#business_type_category_id').html('');
            var options = "<option value=''>Select a category</option>";
            $('#business_type_category_id').append(options);
            $.each( data.data, function( key, value ) {       
                var sel = key == typtId ? 'selected' : '';
                $('#business_type_category_id').append("<option value='"+ key + "' "+sel+">"+ value + "</option");
            });
            $('.overlay').hide();
            $("#business_type_category_id").trigger('change');
        });
    });
    $('#business_type_category_id').on('change',function(){
        $('.overlay').show();
        var businessTypeCatId = $(this).val();
        getServiceSubCat(businessTypeCatId);
    });
    function getServiceSubCat(catId){
        $.post('{{route('getServiceSubCategories')}}',{id:catId},function(data){
            $('#sub-categories').html('');
            var options = "<option value='0'>Main Category</option><optgroup label='Sub Categories'>";
            $('#sub-categories').append(options);
            $.each( data.data, function( key, value ) {
                var sel2 = key == typeCatId ? 'selected' : '';          
                var sel3 = key == currentItem ? 'disabled' : '';          
                $('#sub-categories').append("<option value='"+ key + "' "+ sel2 +" "+ sel3 +">"+ value + "</option");
            });
            $('#sub-categories').append("</optgroup>");
            $('.overlay').hide();
        });
    }
    if(typtId) {
        $("#service_type").trigger('change');
    }
});
</script>
@endsection
