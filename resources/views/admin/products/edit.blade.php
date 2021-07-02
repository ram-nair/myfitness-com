@extends('adminlte::page')

@section('title', $pageTitle)

@section('content_header')
<div class="row mb-2">
    <div class="col-sm-6">
        <h1 class="m-0 text-dark">{{$pageTitle}}</h1>
    </div><!-- /.col -->
</div><!-- /.row -->
@stop
@section('content')
<div class="card card-primary">
    <div class="card-body">
        <div class="row">
            <div class="col-5 col-sm-3">
                <div class="nav flex-column nav-tabs h-100">
                  <!--  <a class="nav-link @if(!$showUpload) active @endif" id="vert-tabs-home-tab" data-toggle="pill" href="#general" role="tab" aria-controls="vert-tabs-home" aria-selected="false">General</a>
                    <a class="nav-link @if($showUpload) active @endif" id="vert-tabs-home-tab" data-toggle="pill" href="#images" role="tab" aria-controls="vert-tabs-home" aria-selected="false">Images</a>
                   -->
                    <a class="nav-link  active" id="vert-tabs-home-tab" data-toggle="pill" href="#general" role="tab" aria-controls="vert-tabs-home" aria-selected="false">General</a>
                   <a class="nav-link" id="vert-tabs-home-tab" data-toggle="pill" href="#ptags" role="tab" aria-controls="vert-tabs-home" aria-selected="false">Product tags</a>
                    <a class="nav-link" id="vert-tabs-home-tab" data-toggle="pill" href="#hpagesettings" role="tab" aria-controls="vert-tabs-home" aria-selected="false">Home Page Setting</a>
                    <a class="nav-link" id="vert-tabs-home-tab" data-toggle="pill" href="#seoTab" role="tab" aria-controls="vert-tabs-home" aria-selected="false"> Search Engine Optimization</a>
                    <a class="nav-link" id="vert-tabs-home-tab" data-toggle="pill" href="#images" role="tab" aria-controls="vert-tabs-home" aria-selected="false">Images</a>
               
                
                </div>
            </div>
            <div class="col-7 col-sm-9">
            {{ Form::model($product, array('route' => array('admin.products.update', $product->id), 'method' => 'POST', 'enctype' => 'multipart/form-data','class' => 'class-create')) }}
            {{ method_field('PATCH') }}
                           
                <div class="tab-content">
                    <div class="tab-pane @if(!$showUpload) active @endif" id="general">
                        <div class="tile">
                            <input type="hidden" name="id" value="{{ $product->id }}">
                            @include ('admin.products.form', ['submitButtonText' => 'Update'])
                           
                        </div>
                    </div>

                    <div class="tab-pane" id="hpagesettings">
                        <div class="tile">
                         <div class="form-group ">
                        {{ Form::label('show_disclaimer', 'Hot Sale') }}
                        <div class="">
                            <input data-bootstrap-switch type="checkbox" name="hot_sale" id="hot_sale" value="1"  @if(!empty($product) && ($product->hot_sale==1)) checked @endif />
                        </div>
                    {!! $errors->first('hot_sale','<p class="text-danger"><strong>:message</strong></p>') !!}
                       </div>


                       <div class="form-group ">
                        {{ Form::label('show_disclaimer', 'Inspirations') }}
                        <div class="">
                            <input data-bootstrap-switch type="checkbox" name="hot_deal" id="hot_deal" value="1"  @if(!empty($product) && ($product->hot_deal==1)) checked @endif />
                        </div>
                    {!! $errors->first('hot_deal','<p class="text-danger"><strong>:message</strong></p>') !!}
                       </div>
                       <div class="form-group ">
                        {{ Form::label('show_disclaimer', 'Popular gear') }}
                        <div class="">
                            <input data-bootstrap-switch type="checkbox" name="popular_gear" id="popular_gear" value="1" @if(!empty($product) && ($product->popular_gear==1)) checked @endif />
                        </div>
                    {!! $errors->first('hot_deal','<p class="text-danger"><strong>:message</strong></p>') !!}
                       </div>

                        </div>
                    </div>
                    <div class="tab-pane" id="ptags">
                    <div class="tile">
                    <div class="col-md-4">
                    <div class="form-group">
                    {{ Form::label('quantity', 'Size') }}
                    <select class="form-control"  name="size">
                    <option value=""></option>
                    <option  value="12Oz">12Oz</option>
                    <option  value="13Oz">13Oz</option>
                    <option  value="14Oz">14Oz</option>
                    <option  value="15Oz">15Oz</option>
                    <option  value="SMALL">SMALL</option>
                    <option  value="MEDIUM">MEDIUM</option>
                    <option  value="LARGE">LARGE</option>
                    <option  value="EXTRA LARGE">EXTRA LARGE</option>
                    <option value="12 inch">12 inch</option>
                    <option  value="14 inch">14 inch</option>
                    <option  value="16 inch">16 inch</option>
                    <option value="18 inch">18 inch</option>
                    <option value="20 inch">20 inch</option>
                    </select>
                    {!! $errors->first('size','<p class="text-danger"><strong>:message</strong></p>') !!}
                
            </div></div>
            <div class="col-md-4">
                <div class="form-group">
                    {{ Form::label('quantity', 'Color') }}
                    <select class="form-control"  name="color">
                    <option value=""></option>
                    <option value="Black">Black</option>
                    <option  value="Red">Red</option>
                    <option value="Blue">Blue</option>
                    <option  value="Gray">Gray</option>
                    <option value="White">White</option>
                    <option value="Navy Blue">Navy Blue</option>
                    <option value="Pink">Pink</option>
                    <option value="Purple">Purple</option>
                    <option value="Green">Green</option>
                    </select>
                    {!! $errors->first('color','<p class="text-danger"><strong>:message</strong></p>') !!}
                
            </div></div>
            <div class="col-md-4">
                <div class="form-group">
                    {{ Form::label('quantity', 'Product Weight') }}
                    <select class="form-control"  name="weight">
                    <option value=""></option>
                    <option value="1kg">1 kg</option>
                    <option value="2kg">2 kg</option>
                    <option value="3kg">3 kg</option>
                    <option value="5Lbs">5 Lbs</option>
                    <option value="8Lbs">8 Lbs</option>
                    <option value="10Lbs">10 Lbs</option>
                    <option value="12Lbs">12 Lbs</option>
                    <option value="14Lbs">14 Lbs</option>
                    <option value="15Lbs">15 Lbs</option>
                    <option value="25Lbs">25 Lbs</option>
                    <option value="35Lbs">35 Lbs</option>
                    <option value="40Lbs">40 Lbs</option>
                    <option value="4 kg">4 kg</option>
                    <option value="6 kg">6 kg</option>
                    <option value="8 kg">8 kg</option>
                    <option value="20 kg">20 kg</option>
                    </select>
                   
                    {!! $errors->first('weight','<p class="text-danger"><strong>:message</strong></p>') !!}
                </div>
            </div>
                <div class="col-md-4">
                <div class="form-group">
                    {{ Form::label('quantity', 'Incline') }}
                    <select class="form-control"  name="incline">
                    <option value=""></option>
                    <option value="5%">5%</option>
                    <option value="10%">10%</option>
                    <option value="12%">12%</option>
                    <option value="15%">15%</option>
                    <option value="16% & Above">16% & Above</option>                 
                    </select>
                 
                    {!! $errors->first('size','<p class="text-danger"><strong>:message</strong></p>') !!}
                </div>
            </div>
            <div class="col-md-4">
            <div class="form-group col">
                    {{ Form::label('quantity', 'Speed') }}
                    <select class="form-control"  name="speed">
                    <option value=""></option>
                    <option data-title="12Oz" value="11 Kph">11 Kph</option>
                    <option data-title="12Oz" value="12 Kph">12 Kph</option>
                    <option data-title="12Oz" value="13 Kph">13 Kph</option>
                    <option data-title="12Oz" value="14 Kph">14 Kph</option>
                    <option data-title="12Oz" value="25 Kph">25 Kph</option>
                    <option data-title="12Oz" value="12 Kph">12 Kph</option>
                    <option data-title="13Oz" value="15 Kph">15 Kph</option>
                    <option data-title="14Oz" value="16 Kph">16 Kph</option>
                    <option data-title="15Oz" value="18 Kph">18 Kph</option>
                    <option data-title="SMALL" value="19 Kph">19 Kph</option>
                    <option data-title="MEDIUM" value="20 Kph">20 Kph</option>
                    <option data-title="LARGE" value="22 Kph">22 Kph</option>
                   
                    </select>
                  {!! $errors->first('size','<p class="text-danger"><strong>:message</strong></p>') !!}
                </div>
            </div>

            <div class="col-md-4">
                <div class="form-group">
                    {{ Form::label('quantity', 'Capacity') }}
                    {{ Form::number('capacity', null, array('class' => 'form-control'.($errors->has('quantity') ? ' is-invalid' : '' ))) }}
                    {!! $errors->first('size','<p class="text-danger"><strong>:message</strong></p>') !!}
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    {{ Form::label('quantity', 'Diameter') }}
                    {{ Form::number('diameter', null, array('class' => 'form-control'.($errors->has('quantity') ? ' is-invalid' : '' ))) }}
                    {!! $errors->first('size','<p class="text-danger"><strong>:message</strong></p>') !!}
                </div>
            </div>
                       
                        </div>
                    </div>

                    <div class="tab-pane" id="seoTab">
                        <div class="tile">
                         
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            {{ Form::label('name', ' Meta Title') }}
                            {{ Form::text('name', null, array('required'=>'','class' => 'form-control'.($errors->has('name') ? ' is-invalid' : '' ))) }}
                            {!! $errors->first('name','<p class="text-danger"><strong>:message</strong></p>') !!}
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            {{ Form::label('sku', ' Meta Keywords') }}
                            {{ Form::text('meta_tag', null, array('required'=>'','class' => 'form-control'.($errors->has('sku') ? ' is-invalid' : '' ))) }}
                            {!! $errors->first('meta_tag','<p class="text-danger"><strong>:message</strong></p>') !!}
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-8">
                        <div class="form-group">
                            {{ Form::label('name', 'Meta Description') }}
                            {{ Form::textarea('meta_description', null, ['class'=>'form-control editor-medium', 'rows' => 2, 'cols' => 40]) }}
                            {!! $errors->first('meta_description','<p class="text-danger"><strong>:message</strong></p>') !!}
                        </div>
                    </div>
                    
                </div>



                        </div>
                    </div>






                    <div class="tab-pane @if($showUpload) active @endif" id="images">
                        <div class="tile">
                            <div class="card card-outline card-info">
                                <div class="card-header">
                                    <h3 class="card-title">
                                      Upload Multiple Images
                                    </h3>
                                </div>
                                <div class="card-body pad">
                                    <div class="row">
                                        <div class="col-md-12">
                                        <input type="file" name="image[]" multiple class="form-control" accept="image/*">

                                            <label for="exampleInputFile">Image (Recommended : {{$imageSize['aspectRatioW']}}x{{$imageSize['aspectRatioH']}})</label>
                                           
                                        </div>
                                    </div>
                                    @if ($product->images)
                                        <hr>
                                        <div class="row">
                                            @foreach($product->images as $image)
                                            <?php
                                                $img = false;
                                                if(!empty($image->full)) {
                                                    $img = $image->full;
                                                }
                                                if($img){?>
                                                <div class="col-md-3">
                                                    <div class="card">
                                                        <div class="card-body">
                                                            <img src="{{asset('uploads/product/images/'.$img)}}" class="img-preview-holder" alt="Preview Image">
                                                            <a class="card-link float-right text-danger" href="{{ route('admin.products.images.delete', $image->id) }}">
                                                                <i class="fa fa-fw fa-lg fa-trash"></i>
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                                <?php } ?>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                <a href="{{ route('admin.products.index') }}" class="btn btn-default">Cancel</a> <span class="required">*</span> are mandatory fields
                {{ Form::submit(isset($submitButtonText) ? $submitButtonText : 'Create', array('class' => 'btn btn-info float-right')) }}
            </div>
                {{ Form::close() }}
            </div>
            </div>
        </div>
    </div>
</div>




<div class="modal" id="modal-default">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title">Advanced Pricing</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
            <form action="{{ route('admin.products.offer_price') }}"  method="post">
             <input type="hidden" name="id" value="{{ $product->id }}">
            {{ csrf_field() }}
            <div class="row">
               <div class="form-group">
                    {{ Form::label('name', ' Special Price') }}
                    <input type="text" class="form-control" required placeholder="Discount price" name="discount_price" id="discount_price"  value="{{ $product->discount_price }}">
                    {!! $errors->first('discount_price','<p class="text-danger"><strong>:message</strong></p>') !!}
                </div>
              </div>

              <div class="row input-daterange" id="global_dates">
                <div class="form-group">
                    {{ Form::label('name', ' Special Price From') }}
                    <div class="col-12">
                    
                    <input type="text" class="form-control" required  placeholder="Starts At" name="discount_start_date" id="start_date" autocomplete="off" value="{{ $product->discount_start_date }}">
                </div>
                <div class="col-12">
                    <label>To</label>
                    <input type="text" class="form-control"  required placeholder="End At" name="discount_end_date" id="end_date" autocomplete="off" value="{{ $product->discount_end_date }}">
                </div>
                </div>
                </div>
              
                <div class="row">
                <div class="form-group">
                    {{ Form::label('name', 'Orginal price') }}
                      <input type="text" class="form-control" placeholder="Orginal price" name="unit_price" id="unit_price"  value="{{ $product->unit_price }}">
                     {!! $errors->first('unit_price','<p class="text-danger"><strong>:message</strong></p>') !!}
                </div>
			</div>

            </div>
            <div class="modal-footer justify-content-between">
              <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
              {{ Form::submit(isset($submitButtonText) ? $submitButtonText : 'Save changes', array('class' => 'btn btn-info float-right')) }}

            </div>
          </div>
          <!-- /.modal-content -->
         </form>
        </div>
        <!-- /.modal-dialog -->
      </div>
      <!-- /.modal -->
@stop
@section('js')
    <link rel="stylesheet" type="text/css" href="{{ asset('css/dropzone.css') }}"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.0.0/animate.min.css" />
    <script type="text/javascript" src="{{ asset('js/dropzone.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/bootstrap-notify.min.js') }}"></script>
    <script>
   
        Dropzone.autoDiscover = false;
        $( document ).ready(function() {
            // $('#categories').select2();
            let myDropzone = new Dropzone("#dropzone", {
                paramName: "image",
                addRemoveLinks: false,
                maxFilesize: 4,
                parallelUploads: 2,
                uploadMultiple: false,
                url: "{{ route('admin.products.images.upload') }}",
                autoProcessQueue: false,
            });
            myDropzone.on("queuecomplete", function (file) {
                window.location.reload();
                showNotification('Completed', 'All product images uploaded', 'success', 'fa-check');
                //alert('All product images uploaded')
            });
            $('#uploadButton').click(function(){
                if (myDropzone.files.length === 0) {
                    showNotification('Error', 'Please select files to upload.', 'danger', 'fa-close');
                    //alert('Please select files to upload.')
                } else {
                    myDropzone.processQueue();
                }
            });
            function showNotification(title, message, type, icon)
            {
                $.notify({
                    title: title + ' : ',
                    message: message,
                    icon: 'fa ' + icon
                },{
                    offset: {
		                x: 13,
		                y: 50
	                },
                    type: type,
                    allow_dismiss: true,
                    placement: {
                        from: "top",
                        align: "right"
                    },
                });
            }
            
    var editId = {{ $product->sub_category_id ?? "null" }};
    // var editSubId = {{ $product->sub_category_id ?? "null" }};
    $('#categories').on('change',function(){
        $('.overlay').show();
        var id = $(this).val();
        $.post('{{route('fetch.categories')}}', {"_token": "{{ csrf_token() }}", 'parent_cat_id' : id}, function(data){
            var options = "";
            $.each(data.data, function(i,j){
                var sel = j.id == editId ? 'selected' : '';
                options += "<option value='"+j.id+"' "+sel+">" + j.name + "</option>";
            });
            $('#sub_category_id').html(options);
            // $('#sub_categories').val($('#sub_categories').attr('value'));
            // console.log($('#parent_id').attr('value'));
            $('.overlay').hide();
        });
    });

    $("#categories").trigger('change');
   
@endsection
