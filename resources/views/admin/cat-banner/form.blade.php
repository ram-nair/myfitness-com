<div class="card card-outline card-info">
    <div class="overlay" style="display: none;">
        <i class="fas fa-2x fa-sync-alt fa-spin"></i>
    </div>
    <div class="card-body pad">
        <div class="form-group">
            {{ Form::label('name', 'Name') }}
            {{ Form::text('name', null, array('required','class' => 'form-control'.($errors->has('name') ? ' is-invalid' : ''))) }}
            {!! $errors->first('name','<p class="text-danger"><strong>:message</strong></p>') !!}
        </div>
        
        <div class="row">
            <div class="form-group col">
                <label class="" for="customSwitch3">Show in Category</label><br/>
                <input type="checkbox" name="in_category" value="1" id="custdomSwitch3" @if (empty($ecommerceBanner)) checked @endif @if (!empty($ecommerceBanner) && $ecommerceBanner->in_category == 1) checked @endif data-bootstrap-switch >
            </div>
            <div class="form-group col">
                <label class="" for="customSwitch3">Show in Products</label><br/>
                <input type="checkbox" name="in_product" value="1" id="custdomSwitch3" @if (empty($ecommerceBanner)) checked @endif @if (!empty($ecommerceBanner) && $ecommerceBanner->in_product == 1) checked @endif data-bootstrap-switch >
            </div>
            <div class="form-group col">
                <label class="" for="customSwitch3">Status</label><br>
                <input type="checkbox" name="status" value="1" id="custdomSwitch3" @if (empty($ecommerceBanner)) checked @endif @if (!empty($ecommerceBanner) && $ecommerceBanner->status == 1) checked @endif data-bootstrap-switch >
            </div>
            <div class="form-group col-6">
                <label for="exampleInputFile">Image (Recommended : {{$imageSize['aspectRatioW']}}x{{$imageSize['aspectRatioH']}})</label>
                <div class="input-group"><?php
                    if(!empty($ecommerceBanner->image)) {
                        $img = $ecommerceBanner->image;
                    } else {
                        $img = url('/')."/images/no-image.jpg";
                    }?>
                    <img class="img-preview-holder" src="{{$img}}" alt="Preview Image" />
                    <div class="custom-file">
                        <input type="file" name="images" data-rule-extension="jpg|png" data-msg-extension="Please select jpg or png image" class="image img-preview form-control-file custom-file-input" id="exampleInputFile">
                        <label class="custom-file-label" for="exampleInputFile">Choose file</label>
                    </div>
                </div>
            </div>
        </div>
        <div class="form-group">
        {{ Form::label('url', 'External Url') }}
        <div class="input-group">
                <div class="input-group-prepend">                    
                    <span class="input-group-text"><i class="fas fa-link"></i></span>
                </div>
                {{ Form::text('url', null, array('class' => 'form-control'.($errors->has('url') ? ' is-invalid' : ''))) }}
                {!! $errors->first('url','<p class="text-danger"><strong>:message</strong></p>') !!}
        </div>
         </div>
        <div class="form-group">
            {{ Form::label('Description', 'Description') }}<br>
            {!! Form::textarea('description',null,['class'=>'form-control editor-medium', 'rows' => 2, 'cols' => 40]) !!}
            {!! $errors->first('description','<p class="text-danger"><strong>:message</strong></p>') !!}
        </div>
    </div>
    <div class="card-footer">
        {{ Form::submit(isset($submitButtonText) ? $submitButtonText : 'Create', array('class' => 'btn btn-info float-right')) }}
        <a href="{{ route('admin.cat-banners.index') }}" class="btn btn-default">Cancel</a>
    </div>
</div>
@section('js')
<script>
$("#categories").select2();

$("#categories").on('change', function(){
    var selected = $(this).val();
    if(selected != null)
      {
       if(selected.indexOf('All')>=0){
       $(this).val('All').select2();
     }
  }
})
</script>
@stop
