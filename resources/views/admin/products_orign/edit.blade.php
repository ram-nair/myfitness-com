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
                    <a class="nav-link @if(!$showUpload) active @endif" id="vert-tabs-home-tab" data-toggle="pill" href="#general" role="tab" aria-controls="vert-tabs-home" aria-selected="false">General</a>
                    <a class="nav-link @if($showUpload) active @endif" id="vert-tabs-home-tab" data-toggle="pill" href="#images" role="tab" aria-controls="vert-tabs-home" aria-selected="false">Images</a>
                </div>
            </div>
            <div class="col-7 col-sm-9">
                <div class="tab-content">
                    <div class="tab-pane @if(!$showUpload) active @endif" id="general">
                        <div class="tile">
                            {{ Form::model($product, array('route' => array('admin.products.update', $product->id), 'method' => 'POST', 'class' => 'class-create')) }}
                            {{ method_field('PATCH') }}
                            <input type="hidden" name="id" value="{{ $product->id }}">
                            @include ('admin.products.form', ['submitButtonText' => 'Update'])
                            {{ Form::close() }}
                        </div>
                    </div>
                    <div class="tab-pane @if($showUpload) active @endif" id="images">
                        <div class="tile">
                            <div class="card card-outline card-info">
                                <div class="card-header">
                                    <h3 class="card-title">
                                      Upload Images
                                    </h3>
                                </div>
                                <div class="card-body pad">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <form action="" class="dropzone" id="dropzone" style="border: 2px dashed rgba(0,0,0,0.3); padding:20px;">
                                                <input type="hidden" name="id" value="{{ $product->id }}">
                                                {{ csrf_field() }}
                                            </form>
                                            <label for="exampleInputFile">Image (Recommended : {{$imageSize['aspectRatioW']}}x{{$imageSize['aspectRatioH']}})</label>
                                            <div class="row d-print-none mt-2">
                                                <div class="col-12 text-right">
                                                    <button class="btn btn-success" type="submit" id="uploadButton">
                                                        <i class="fa fa-fw fa-lg fa-upload"></i>Upload Images
                                                    </button>
                                                </div>
                                            </div>
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
                                                            <img src="{{ $img }}" class="img-preview-holder" alt="Preview Image">
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
@endsection
