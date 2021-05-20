<div class="card card-outline card-info">
    <div class="overlay" style="display: none;">
        <i class="fas fa-2x fa-sync-alt fa-spin"></i>
    </div>
    <div class="card-body pad">
        <div class="form-group">
        <input type="hidden" name="service_type" value="{{$service_type}}">
            {{ Form::label('name', 'Name') }}
            {{ Form::text('name', null, array('required','class' => 'form-control'.($errors->has('name') ? ' is-invalid' : ''))) }}
            {!! $errors->first('name','<p class="text-danger"><strong>:message</strong></p>') !!}
        </div>
        
        <div class="form-group"  id="store_div">
            <label for="title">Display Banner In Stores</label>
            <select name="store_id[]" class="select2 form-control" style="width:100%" required  id="categories" multiple>
                <option value="All">All</option>
                @foreach($stores as $category)
                    <option value="{{ $category->id }}" @if(!empty($serviceBanner) && in_array($category->id, $serviceBanner->store->pluck('id')->toArray())) selected @endif>{{ $category->name }}</option>
                @endforeach
            </select>
        </div>
        
        <div class="row">
            <div class="form-group col-6 mr-5">
                <label for="exampleInputFile">Image (Recommended : {{$imageSize['aspectRatioW']}}x{{$imageSize['aspectRatioH']}})</label>
                <div class="input-group"><?php
                    if(!empty($serviceBanner->image)) {
                        $img = $serviceBanner->image;
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
            @if($service_type == 2)
                <div class="form-group col">
                    <label class="" for="customSwitch3">Enable in Category Listing</label><br/>
                    <input type="checkbox" name="in_category" value="1" id="custdomSwitch3" @if (!empty($serviceBanner) && $serviceBanner->in_category == 1) checked @endif data-bootstrap-switch >
                </div>
                <div class="form-group col">
                    <label class="" for="customSwitch3">Enable in Products Listing</label><br/>
                    <input type="checkbox" name="in_product" value="1" id="custdomSwitch3" @if (!empty($serviceBanner) && $serviceBanner->in_product == 1) checked @endif data-bootstrap-switch >
                </div>
            @endif
            <div class="form-group col">
                <label class="" for="customSwitch3">Status</label><br>
                <input type="checkbox" name="status" value="1" id="custdomSwitch3" @if (empty($serviceBanner)) checked @endif @if (!empty($serviceBanner) && $serviceBanner->status == 1) checked @endif data-bootstrap-switch >
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
            {!! Form::textarea('description', null ,['class'=>'form-control editor-medium', 'rows' => 2, 'cols' => 40]) !!}
            {!! $errors->first('description','<p class="text-danger"><strong>:message</strong></p>') !!}
        </div>
    </div>
    <div class="card-footer">
        {{ Form::submit(isset($submitButtonText) ? $submitButtonText : 'Create', array('class' => 'btn btn-info float-right')) }}
        <a href="{{ route('admin.service-banner.index') }}" class="btn btn-default">Cancel</a>
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
