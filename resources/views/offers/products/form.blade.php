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
            <div class="col-md-2">
                <div class="form-group">
                    {{ Form::label('unit', 'Unit (GMs, KGs, LTs, MLs)') }}
                    {{ Form::text('unit', null, array('required'=>'','class' => 'form-control'.($errors->has('unit') ? ' is-invalid' : '' ))) }}
                    {!! $errors->first('unit','<p class="text-danger"><strong>:message</strong></p>') !!}
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
                    <select name="sub_category_id" class="select2 form-control" style="width:100%" required  id="sub_categories">
                        <option value="">Select Sub Category</option>
                        @foreach($sub_categories as $catId => $category)
                            @php 
                            $check = "";
                            if(isset($product)) {
                                $check = $catId == $product->sub_category_id ? 'selected' : '';
                            }
                            @endphp
                            <option value="{{ $catId }}" {{ $check }}>{{ $category }}</option>
                        @endforeach
                    </select>
                    {!! $errors->first('categories','<p class="text-danger"><strong>:message</strong></p>') !!}
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
            {{-- <div class="col-md-4">
                <div class="form-group">
                    {{ Form::label('max quantity per person', 'Maximum Quantity/Person') }}
                    {{ Form::text('max_quantity_per_person', null, array('class' => 'form-control'.($errors->has('max_quantity_per_person') ? ' is-invalid' : '' ))) }}
                    {!! $errors->first('max_quantity_per_person','<p class="text-danger"><strong>:message</strong></p>') !!}
                </div>
            </div> --}}
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



