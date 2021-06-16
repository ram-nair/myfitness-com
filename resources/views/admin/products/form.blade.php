<div class="card card-outline card-info">
    <div class="overlay" style="display: none;">
        <i class="fas fa-2x fa-sync-alt fa-spin"></i>
    </div>
    <div class="card-body pad">
        <div class="row">
            <div class="col-md-4">
                <div class="form-group">
                    {{ Form::label('name', 'Product Name') }}
                    {{ Form::text('name', null, array('require','class' => 'form-control'.($errors->has('name') ? ' is-invalid' : '' ))) }}
                    {!! $errors->first('name','<p class="text-danger"><strong>:message</strong></p>') !!}
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    {{ Form::label('sku', 'SKU') }}
                    {{ Form::text('sku', null, array('required','class' => 'form-control'.($errors->has('sku') ? ' is-invalid' : '' ))) }}
                    {!! $errors->first('sku','<p class="text-danger"><strong>:message</strong></p>') !!}
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    {{ Form::label('brand', 'Brand') }}
                     <select name="brand_id"  class="form-control" style="width:100%" id="brand">
                        <option value="">Select Brand</option>
                        @foreach ($brands as $brand)
                            <option value="{{$brand->id}}" @if(!empty($product->brand) && ($brand->id == $product->brand->id)) selected @endif>{{$brand->name}}</option>
                        @endforeach
                    </select>
                    {!! $errors->first('brand_id','<p class="text-danger"><strong>:message</strong></p>') !!}
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    {{ Form::label('unit price', 'Product Price(AED)') }}
                    {{ Form::text('unit_price', null, array('required','class' => 'form-control'.($errors->has('unit_price') ? ' is-invalid' : '' ))) }}
                    {!! $errors->first('unit_price','<p class="text-danger"><strong>:message</strong></p>') !!}
                </div>
                @php
                if(!empty($product)){
                @endphp
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal-default">
                Advanced Pricing
                </button>
               
               @php
                }
               @endphp
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    {{ Form::label('quantity', 'Quantity') }}
                    {{ Form::number('quantity', null, array('required','class' => 'form-control'.($errors->has('quantity') ? ' is-invalid' : '' ))) }}
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
                  
                    <select name="sub_category_id" id="sub_category_id" class="select2 form-control" style="width:100%" required >
                        <option value="">Select Sub Category</option>
                    </select>
                    {!! $errors->first('subcat','<p class="text-danger"><strong>:message</strong></p>') !!}
                </div>

                <div class="form-group col">
                    {{ Form::label('subcat', 'Child Category') }}
                    <select name="child_category_id" class="select2 form-control" style="width:100%"  id="child_category_id">
                        <option value="">Select child Category</option>
                         </select>
                    {!! $errors->first('childcat','<p class="text-danger"><strong>:message</strong></p>') !!}
                </div>
               
                <div class="form-group col">
                    {{ Form::label('featured', 'Stock Status') }}
                    <select name="in_stock" class="form-control" style="width:100%"  id="in_stock">
                            <option value="1" @if(!empty($product) && ($product->in_stock==1)) selected @endif>In Stock</option>
                            <option value="0" @if(!empty($product) && ($product->in_stock==0)) selected @endif>Out of Stock</option>
                    </select>
                    {!! $errors->first('featured','<p class="text-danger"><strong>:message</strong></p>') !!}
                </div>
                </div>
                <div class="row">
                <div class="form-group col">
                    {{ Form::label('featured', 'Status') }}
                    <select name="status" class="form-control" style="width:100%"  id="status">
                            <option value="1" @if(!@empty($product) && $product->status==1) selected @endif>Enabled</option>
                            <option value="0" @if(!@empty($product)&& $product->status!=1) selected @endif>Disabled</option>
                    </select>
                    {!! $errors->first('featured','<p class="text-danger"><strong>:message</strong></p>') !!}
                </div>
                <div class="form-group col">
                    {{ Form::label('featured', 'Featured') }}
                    <select name="featured" class="form-control" style="width:100%"  id="featured">
                            <option value="1" @if(!@empty($product) && $product->featured==1) selected @endif>Enabled</option>
                            <option value="0" @if(!@empty($product)&& $product->featured!=1) selected @endif>Disabled</option>
                    </select>
                    {!! $errors->first('featured','<p class="text-danger"><strong>:message</strong></p>') !!}
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
</div>


@section('js')
<link href="{{ asset('css/bootstrap-datepicker.css')}}" id="theme" rel="stylesheet">
    <script src="{{ asset('js/bootstrap-datepicker.min.js')}}"></script>

    <script>
        $("#global_dates").datepicker({
            toggleActive: !0,
            format:'yyyy-mm-dd'
        });

$(function(){
    var sub_id = {{ $product->sub_category_id ?? "null" }};
    var child_id = {{ $product->child_category_id ?? "null" }};
      if(sub_id){
        getChild(sub_id,child_id);
      }
    var currentId = {{ $product->category_id ?? "null" }};
    if(currentId && sub_id){
        getSubCat(currentId,sub_id);
    }
   });
<?php 
if(!empty($childcategory)){?>
var catId = $('#categories').val();
var link ='{{ url('admin/loadsub/subcategories') }}/'+catId;
        if(link != "")
        {
          $('#sub_category_id').load(link);
          $('#sub_category_id').prop('disabled',false);
        }
<?php }
?>
$(document).on('change','#categories',function () {
      var catId = $('#categories').val();
        var link ='{{ url('admin/loadsub/subcategories') }}/'+catId;
        if(link != "")
        {
          $('#sub_category_id').load(link);
          $('#sub_category_id').prop('disabled',false);
        }
      });
    $(document).on('change','#sub_category_id',function () {
      var catId = $('#sub_category_id').val();
         getChild(catId,null);
      });
    function getChild(catId,subId){
        $.ajax({
            url: '{{ url('admin/loadChildcat') }}/'+catId+'/'+subId,
            success: function(resp) {
                $('#child_category_id').html(resp);
            }
        });
    }

    function getSubCat(catId,subId){
        $.ajax({
            url: '{{ url('admin/loadSubcat') }}/'+catId+'/'+subId,
            success: function(resp) {
                $('#sub_category_id').html(resp);
            }
        });
    }
</script>
@endsection


