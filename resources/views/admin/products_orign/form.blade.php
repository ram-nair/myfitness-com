<div class="card card-outline card-info">
    <div class="overlay" style="display: none;">
        <i class="fas fa-2x fa-sync-alt fa-spin"></i>
    </div>
    <div class="card-body pad">
        <div class="row">
            <div class="col-md-4">
                <div class="form-group">
                    {{ Form::label('name', 'Name') }}
                    {{ Form::text('name', null, array('required'=>'','class' => 'form-control'.($errors->has('name') ? ' is-invalid' : '' ))) }}
                    {!! $errors->first('name','<p class="text-danger"><strong>:message</strong></p>') !!}
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    {{ Form::label('sku', 'SKU') }}
                    {{ Form::text('sku', null, array('required'=>'','class' => 'form-control'.($errors->has('sku') ? ' is-invalid' : '' ))) }}
                    {!! $errors->first('sku','<p class="text-danger"><strong>:message</strong></p>') !!}
                </div>
            </div>
            <div class="col-md-4">
               <!-- <div class="form-group">
                    {{ Form::label('brand', 'Brand') }}
                    <select name="brand_id"  class="form-control" style="width:100%" id="brand">
                        <option value="">Select Brand</option>
                        @foreach ($brands as $brand)
                            <option value="{{$brand->id}}" @if(!empty($product->brand) && ($brand->id == $product->brand->id)) selected @endif>{{$brand->name}}</option>
                        @endforeach
                    </select>
                    {!! $errors->first('brand_id','<p class="text-danger"><strong>:message</strong></p>') !!}
                </div>-->
            </div>
            <div class="col-md-2">
                <div class="form-group">
                    {{ Form::label('unit price', 'Unit Price') }}
                    {{ Form::text('unit_price', null, array('required'=>'','class' => 'form-control'.($errors->has('unit_price') ? ' is-invalid' : '' ))) }}
                    {!! $errors->first('unit_price','<p class="text-danger"><strong>:message</strong></p>') !!}
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-group">
                    {{ Form::label('quantity', 'Quantity') }}
                    {{ Form::number('quantity', null, array('required'=>'','class' => 'form-control'.($errors->has('quantity') ? ' is-invalid' : '' ))) }}
                    {!! $errors->first('quantity','<p class="text-danger"><strong>:message</strong></p>') !!}
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="row">
                <div class="form-group col">
                    {{ Form::label('categories', 'Categories') }}
                    <select name="category_id" class="select2 form-control" style="width:100%" required  id="categories">
                        <option value="">Select Category</option>
                        @foreach($categories as $catId => $category)
                            @php 
                            $check = "";
                            if(isset($product)) {
                                $check = $catId == $product->category_id ? 'selected' : '';
                            }
                            @endphp
                            <option value="{{ $catId }}" {{ $check }}>{{ $category }}</option>
                        @endforeach
                    </select>
                    {!! $errors->first('categories','<p class="text-danger"><strong>:message</strong></p>') !!}
                </div>
                <div class="form-group col">
                    {{ Form::label('subcat', 'Sub Category') }}
                  
                    <select name="sub_category_id" id="subcat" class="select2 form-control" style="width:100%" required >
                        <option value="">Select Sub Category</option>
                    </select>
                    {!! $errors->first('subcat','<p class="text-danger"><strong>:message</strong></p>') !!}
                </div>

                <div class="form-group col">
                    {{ Form::label('subcat', 'Child Category') }}
                    <select name="child_category_id" class="select2 form-control" style="width:100%"  id="childcat">
                        <option value="">Select child Category</option>
                         </select>
                    {!! $errors->first('childcat','<p class="text-danger"><strong>:message</strong></p>') !!}
                </div>
               
                {{-- <div class="form-group text-center">
                    {{ Form::label('featured', 'Featured') }}
                    <div>
                        <input data-bootstrap-switch type="checkbox" name="featured" id="featured" value="1" {{isset($product)&&$product->featured == 1 ? 'checked' : ''}} />
                    </div>
                    {!! $errors->first('featured','<p class="text-danger"><strong>:message</strong></p>') !!}
                </div> --}}
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
        <a href="{{ route('admin.products.index') }}" class="btn btn-default">Cancel</a>
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
var catId = $('#cat_id').val();
var link ='{{ url('admin/loadsub/subcategories') }}/'+catId;
        if(link != "")
        {
          $('#subcategory_id').load(link);
          $('#subcategory_id').prop('disabled',false);
        }
<?php }
?>
$(document).on('change','#cat_id',function () {
      var catId = $('#cat_id').val();
        var link ='{{ url('admin/loadsub/subcategories') }}/'+catId;
        if(link != "")
        {
          $('#subcategory_id').load(link);
          $('#subcategory_id').prop('disabled',false);
        }
      });

</script>
@endsection


