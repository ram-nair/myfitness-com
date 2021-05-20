<div class="card card-outline card-info">
    <div class="overlay" style="display: none;">
        <i class="fas fa-2x fa-sync-alt fa-spin"></i>
    </div>
    <div class="card-body pad">

        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="exampleInputFile">Offer Image (Recommended : {{$imageSize['aspectRatioW']}}x{{$imageSize['aspectRatioH']}})</label>
                    <div class="input-group">
                        <?php
                        if(!empty($offer->image)) {
                            $img = $offer->image;
                        } else {
                            $img = url('/')."/images/no-image.jpg";
                        }
                        ?>
                        <img class="img-preview-holder" src="{{$img}}" alt="Preview Image" />
                        <div class="custom-file">
                            <input type="file" name="image" data-rule-extension="jpg|png" data-msg-extension="Please select jpg or png image" class="image img-preview form-control-file custom-file-input" id="exampleInputFile">
                            <label class="custom-file-label" for="exampleInputFile">Choose file</label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="exampleInputFile">Cover Image (Recommended : {{$imageSize['aspectRatioW']}}x{{$imageSize['aspectRatioH']}})</label>
                    <div class="input-group">
                        <?php
                        if(!empty($offer->cover_image)) {
                            $img = $offer->cover_image;
                        } else {
                            $img = url('/')."/images/no-image.jpg";
                        }
                        ?>
                        <img class="img-preview-holder" src="{{$img}}" alt="Preview Image" />
                        <div class="custom-file">
                            <input type="file" name="cover_image" data-rule-extension="jpg|png" data-msg-extension="Please select jpg or png image" class="image img-preview form-control-file custom-file-input" id="exampleInputFile">
                            <label class="custom-file-label" for="exampleInputFile">Choose file</label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-3">
                <div class="form-group">
                    {{ Form::label('brand_id', 'Merchant') }}<br>
                    <select class='form-control select2' required name="brand_id" id="brand_id" >
                        <option value="">Select Merchant</option>
                        @foreach($brands as $brand)
                            <option value="{{$brand->id}}" @if(isset($offer) && $offer->brand_id == $brand->id) selected @endif>{{$brand->name}}</option>
                        @endforeach
                    </select>
                    {!! $errors->first('brand_id','<p class="text-danger"><strong>:message</strong></p>') !!}
                </div>
            </div>
            {{--<div class="col-md-3">--}}
                {{--<div class="form-group">--}}
                    {{--{{ Form::label('purchase_validity', 'Purchase Availability') }}<br>--}}
                    {{--<select class='form-control select2' required name="purchase_validity" id="purchase_validity" >--}}
                        {{--<option value="">Select Availability</option>--}}
                        {{--@foreach($purchase_validity as $availability)--}}
                            {{--<option value="{{$availability}}" @if(isset($offer) && $offer->purchase_validity == $availability) selected @endif>{{$availability}}</option>--}}
                        {{--@endforeach--}}
                    {{--</select>--}}
                    {{--{!! $errors->first('purchase_validity','<p class="text-danger"><strong>:message</strong></p>') !!}--}}
                {{--</div>--}}
            {{--</div>--}}
            <div class="col-md-3">
                <div class="form-group">
                    {{ Form::label('title', 'Title') }}
                    {{ Form::text('title', null, array('required'=>'','class' => 'form-control'.($errors->has('title') ? ' is-invalid' : '' ))) }}
                    {!! $errors->first('title','<p class="text-danger"><strong>:message</strong></p>') !!}
                </div>
            </div>
            {{--<div class="col-md-3">--}}
                {{--<div class="form-group">--}}
                    {{--{{ Form::label('coupon_code', 'Coupon Code') }}--}}
                    {{--{{ Form::text('coupon_code', null, array('required'=>'','class' => 'form-control'.($errors->has('coupon_code') ? ' is-invalid' : '' ))) }}--}}
                    {{--{!! $errors->first('coupon_code','<p class="text-danger"><strong>:message</strong></p>') !!}--}}
                {{--</div>--}}
            {{--</div>--}}

        </div>
            <div class="row input-daterange" id="global_dates">
            <div class="col-md-3">
                @php
                    $start_date = (isset($offer))?date('m-d-Y',strtotime($offer->start_date)) : null;
                    $end_date = (isset($offer))?date('m-d-Y',strtotime($offer->end_date)) : null;
                @endphp

                <div class="form-group" >
                    {{ Form::label('longt', 'Start Date') }}
                    {{ Form::text("start_date", $start_date, array("required", "autocomplete" => "off", 'class' => 'start_end_date form-control '.($errors->has("start_date") ? ' is-invalid' : ''))) }}
                    {!! $errors->first("start_date", '<p class="text-danger"><strong>:message</strong></p>') !!}
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group" >
                    {{ Form::label('longt', 'End Date') }}
                    {{ Form::text("end_date", $end_date, array("required", "autocomplete" => "off", 'class' => 'start_end_date form-control '.($errors->has("end_date") ? ' is-invalid' : ''))) }}
                    {!! $errors->first("end_date", '<p class="text-danger"><strong>:message</strong></p>') !!}
                </div>
            </div>
             <div class="col-md-3">
                    <div class="form-group">
                        {{ Form::label('Status', 'Status') }}<br>
                        <select class="form-control" name="status" id="status" >
                            <option value="1" @if(isset($offerBrand) && $offerBrand->status == 1) selected @endif>Enable</option>
                            <option value="0" @if(isset($offerBrand) && $offerBrand->status == 0) selected @endif>Disable</option>
                        </select>
                        {!! $errors->first('status','<p class="text-danger"><strong>:message</strong></p>') !!}
                    </div>
                </div>
            </div>


        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    {{ Form::label('redeem_text', 'Redeem Text') }}
                    {{ Form::textarea('redeem_text', null, array('required','class' => 'form-control editor-medium')) }}
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    {{ Form::label('description', 'Description') }}
                    {{ Form::textarea('description', null, array('class' => 'form-control editor-medium')) }}
                </div>
            </div>
        </div>
    </div>
    <div class="card-footer">
        <a href="{{ route('admin.offers.index') }}" class="btn btn-default">Cancel</a>
        {{ Form::submit(isset($submitButtonText) ? $submitButtonText : 'Create', array('class' => 'btn btn-info float-right')) }}
    </div>
</div>

@section('js')
    <link href="{{ asset('css/bootstrap-datepicker.css')}}" id="theme" rel="stylesheet">
    <script src="{{ asset('js/bootstrap-datepicker.min.js')}}"></script>
    <script type="text/javascript">
        $(function () {
            $("#global_dates").datepicker({
                toggleActive: !0
            });
        });

    </script>
@stop
