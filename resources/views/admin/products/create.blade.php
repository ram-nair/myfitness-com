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
                    <a class="nav-link  active" id="vert-tabs-home-tab" data-toggle="pill" href="#general" role="tab" aria-controls="vert-tabs-home" aria-selected="false">General</a>
                   <a class="nav-link" id="vert-tabs-home-tab" data-toggle="pill" href="#ptags" role="tab" aria-controls="vert-tabs-home" aria-selected="false">Product Attributes</a>
                    <a class="nav-link" id="vert-tabs-home-tab" data-toggle="pill" href="#hpagesettings" role="tab" aria-controls="vert-tabs-home" aria-selected="false">Home Page Setting</a>
                    <a class="nav-link" id="vert-tabs-home-tab" data-toggle="pill" href="#seoTab" role="tab" aria-controls="vert-tabs-home" aria-selected="false"> Search Engine Optimization</a>
                    <a class="nav-link" id="vert-tabs-home-tab" data-toggle="pill" href="#images" role="tab" aria-controls="vert-tabs-home" aria-selected="false">Images</a>
                </div>
            </div>
            <div class="col-7 col-sm-9">
            {{ Form::open(array('url' => 'admin/products','enctype' => 'multipart/form-data','class' => 'class-create')) }}
                        
                <div class="tab-content">
                    <div class="tab-pane active" id="general">
                        <div class="tile">
                            @include ('admin.products.form')
                            
                        </div>
                    </div>

                    <div class="tab-pane" id="ptags">
                        <div class="tile">
                        <div class="col-md-4">
                <div class="form-group">
                    {{ Form::label('quantity', 'Size') }}
                    {{ Form::number('size', null, array('class' => 'form-control'.($errors->has('quantity') ? ' is-invalid' : '' ))) }}
                    {!! $errors->first('size','<p class="text-danger"><strong>:message</strong></p>') !!}
                </div>
            </div><div class="col-md-4">
                <div class="form-group">
                    {{ Form::label('quantity', 'Color') }}
                    {{ Form::number('color', null, array('class' => 'form-control'.($errors->has('quantity') ? ' is-invalid' : '' ))) }}
                    {!! $errors->first('color','<p class="text-danger"><strong>:message</strong></p>') !!}
                </div>
            </div><div class="col-md-4">
                <div class="form-group">
                    {{ Form::label('quantity', 'Weight') }}
                    {{ Form::number('weight', null, array('class' => 'form-control'.($errors->has('quantity') ? ' is-invalid' : '' ))) }}
                    {!! $errors->first('weight','<p class="text-danger"><strong>:message</strong></p>') !!}
                </div>
            </div>
                        <div class="col-md-4">
                <div class="form-group">
                    {{ Form::label('quantity', 'Incline') }}
                    {{ Form::number('incline', null, array('class' => 'form-control'.($errors->has('quantity') ? ' is-invalid' : '' ))) }}
                    {!! $errors->first('size','<p class="text-danger"><strong>:message</strong></p>') !!}
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    {{ Form::label('quantity', 'Speed') }}
                    {{ Form::number('speed', null, array('class' => 'form-control'.($errors->has('quantity') ? ' is-invalid' : '' ))) }}
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
                   
                    <div class="tab-pane" id="hpagesettings">
                        <div class="tile">
                         <div class="form-group ">
                        {{ Form::label('show_disclaimer', 'Home Page/Main Page') }}
                        <div class="">
                            <input data-bootstrap-switch type="checkbox" name="hot_sale" id="hot_sale" value="1" />
                        </div>
                    {!! $errors->first('hot_sale','<p class="text-danger"><strong>:message</strong></p>') !!}
                       </div>


                       <div class="form-group ">
                        {{ Form::label('show_disclaimer', 'Hot Deal') }}
                        <div class="">
                            <input data-bootstrap-switch type="checkbox" name="hot_deal" id="hot_deal" value="1" />
                        </div>
                    {!! $errors->first('hot_deal','<p class="text-danger"><strong>:message</strong></p>') !!}
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


                    <div class="tab-pane" id="images">
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

                                       <!-- <div class="custom-file">
                                        
												
												
                                            <input type="file" name="image" data-rule-extension="jpg|png" data-msg-extension="Please select jpg or png image" class="image img-preview form-control-file custom-file-input" id="exampleInputFile">
                                            <label class="custom-file-label" for="exampleInputFile">Choose file</label>
                                        </div>-->
                                        </div>
                                    </div>
                                 </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                <a href="{{ route('admin.products.index') }}" class="btn btn-default">Cancel</a>
                {{ Form::submit(isset($submitButtonText) ? $submitButtonText : 'Create', array('class' => 'btn btn-info float-right')) }}
            </div>
                {{ Form::close() }}
            </div>
        </div>
    </div>
</div>
<!---- modal---->

<div class="modal fade" id="setgallery" tabindex="-1" role="dialog" aria-labelledby="setgallery" aria-hidden="true">
			<div class="modal-dialog modal-dialog-centered  modal-lg" role="document">
				<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="exampleModalCenterTitle">Image Gallery</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">×</span>
					</button>
				</div>
				<div class="modal-body">
					<div class="top-area">
						<div class="row">
							<div class="col-sm-6 text-right">
								<div class="upload-img-btn">
											<label for="image-upload" id="prod_gallery"><i class="icofont-upload-alt"></i>Upload File</label>
								</div>
							</div>
							<div class="col-sm-6">
								<a href="javascript:;" class="upload-done" data-dismiss="modal"> <i class="fas fa-check"></i> Done</a>
							</div>
							<div class="col-sm-12 text-center">( <small>You can upload multiple Images.</small> )</div>
						</div>
					</div>
					<div class="gallery-images">
						<div class="selected-image">
							<div class="row">


							</div>
						</div>
					</div>
				</div>
				</div>
			</div>
		</div>






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
            $('#sub_categories').html(options);
            // $('#sub_categories').val($('#sub_categories').attr('value'));
            // console.log($('#parent_id').attr('value'));
            $('.overlay').hide();
        });
    });

    $("#categories").trigger('change');
});
</script>

<script type="text/javascript">
	
// Gallery Section Insert

  $(document).on('click', '.remove-img' ,function() {
    var id = $(this).find('input[type=hidden]').val();
    $('#galval'+id).remove();
    $(this).parent().parent().remove();
  });

  $(document).on('click', '#prod_gallery' ,function() {
    $('#uploadgallery').click();
     $('.selected-image .row').html('');
    $('#geniusform').find('.removegal').val(0);
  });
                                        
                                
  $("#uploadgallery").change(function(){
     var total_file=document.getElementById("uploadgallery").files.length;
     for(var i=0;i<total_file;i++)
     {
      $('.selected-image .row').append('<div class="col-sm-6">'+
                                        '<div class="img gallery-img">'+
                                            '<span class="remove-img"><i class="fas fa-times"></i>'+
                                            '<input type="hidden" value="'+i+'">'+
                                            '</span>'+
                                            '<a href="'+URL.createObjectURL(event.target.files[i])+'" target="_blank">'+
                                            '<img src="'+URL.createObjectURL(event.target.files[i])+'" alt="gallery image">'+
                                            '</a>'+
                                        '</div>'+
                                  '</div> '
                                      );
      $('#geniusform').append('<input type="hidden" name="galval[]" id="galval'+i+'" class="removegal" value="'+i+'">')
     }

  });

// Gallery Section Insert Ends	

</script>

<script type="text/javascript">
	
$('.cropme').simpleCropper();
$('#crop-image').on('click',function(){
$('.cropme').click();
});
</script>



@endsection
