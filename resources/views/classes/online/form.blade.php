<div class="card card-outline card-info">
    <div class="overlay" style="display: none;">
        <i class="fas fa-2x fa-sync-alt fa-spin"></i>
    </div>
    <div class="card-body pad">
        <div class="row">
            <div class="col-md-4">
                <div class="form-group">
                    {{ Form::label('name', 'Title') }}
                    {{ Form::text('title', null, array('required'=>'','class' => 'form-control'.($errors->has('name') ? ' is-invalid' : '' ))) }}
                    {!! $errors->first('name','<p class="text-danger"><strong>:message</strong></p>') !!}
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    {{ Form::label('instructor', 'Instructor') }}
                    {{ Form::text('instructor', null, array('required'=>'','class' => 'form-control'.($errors->has('instructor') ? ' is-invalid' : '' ))) }}
                    {!! $errors->first('instructor','<p class="text-danger"><strong>:message</strong></p>') !!}
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    {{ Form::label('vendor_id', 'Vendor') }}<br>
                    <select class='form-control select2' required name="vendor_id" id="vendor_id" >
                        <option value="">Select Vendor</option>
                        @foreach($vendors as $vend)
                            <option value="{{$vend->id}}" @if(isset($generalclass) && $generalclass->vendor_id == $vend->id) selected @endif>{{$vend->vendor_name}}</option>
                        @endforeach
                    </select>
                    {!! $errors->first('vendor_id','<p class="text-danger"><strong>:message</strong></p>') !!}
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    {{ Form::label('categories', 'Categories') }}
                    <select name="category_id" class="select2 form-control" style="width:100%" required  id="categories">
                        <option value="">Select Category</option>
                        @foreach($categories as $catId => $category)
                            @php
                                $check = "";
                                if(isset($generalclass)) {
                                    $check = $catId == $generalclass->category_id ? 'selected' : '';
                                }
                            @endphp
                            <option value="{{ $catId }}" {{ $check }}>{{ $category }}</option>
                        @endforeach
                    </select>
                    {!! $errors->first('categories','<p class="text-danger"><strong>:message</strong></p>') !!}
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group">
                    <label for="exampleInputFile">Image (Recommended : {{$imageSize['aspectRatioW']}}x{{$imageSize['aspectRatioH']}})</label>
                    <div class="input-group image-upload"><?php
                        if(!empty($generalclass->image)) {
                            $img = $generalclass->image;
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
            </div>
            <div class="col-md-3">
                {{ Form::label('date range', 'Start Date') }}
                <div class="input-group form-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                    </div>
                    {!! Form::text('start_date',null, array("data-inputmask-inputformat" => "dd/mm/yyyy",  "data-rule-dateBefore" => "#end_date", 'id'=>'start_date', "autocomplete" => "off", 'class' => 'form-control'.($errors->has('start_date') ? ' is-invalid' : ''))) !!}
                </div>
            </div>
            <div class="col-md-3">
                {{ Form::label('date range', 'End Date') }}
                <div class="input-group form-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                    </div>
                    {!! Form::text('end_date',null, array("data-inputmask-inputformat" => "dd/mm/yyyy",  "data-rule-dateAfter" => "#start_date", 'id'=>'end_date', "autocomplete" => "off", 'class' => 'form-control'.($errors->has('end_date') ? ' is-invalid' : ''))) !!}
                </div>
            </div>
        </div>
        <div class="row">
            {{-- This is for package selection --}}
            <div class="col-md-12">
                <div class="form-group">
                    {{ Form::label('packages', 'Packages') }}
                    <select name="package_id[]" class="select2 form-control" style="width:100%" required  id="packages" multiple>
                        <option value="All">All</option>
                        @foreach($packages as $package)
                            <option value="{{ $package->id }}" @if(!empty($generalclass) && in_array($package->id, $generalclass->packages()->pluck('id')->toArray())) selected @endif>{{ $package->name }}</option>
                        @endforeach
                    </select>
                    {!! $errors->first('package_id','<p class="text-danger"><strong>:message</strong></p>') !!}
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    {{ Form::label('description', 'Overview') }}
                    {{ Form::textarea('overview', null, ['class'=>'form-control editor-medium', 'rows' => 2, 'cols' => 40]) }}
                    {!! $errors->first('overview','<p class="text-danger"><strong>:message</strong></p>') !!}
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    {{ Form::label('can policy', 'Cancelation Policy') }}
                    {{ Form::textarea('cancelation_policy', null, ['class'=>'form-control editor-medium', 'rows' => 2, 'cols' => 40]) }}
                    {!! $errors->first('cancelation_policy','<p class="text-danger"><strong>:message</strong></p>') !!}
                </div>
            </div>
        </div>
        @if($type == "offline" && !isset($generalclass))
            <div class="row">
                <div class="form-group col-6">
                    {{ Form::label('communities', 'Select Community') }}
                    <select name="community_id" class="select2 form-control" style="width:100%" id="community_id">
                        <option value="">Select Community</option>
                        @foreach($communities as $community_id => $community)
                            @php
                            $check = "";
                            if(isset($generalclass)) {
                                $check = $community_id == $generalclass->community_id ? 'selected' : '';
                            }
                            @endphp
                            <option value="{{ $community_id }}" {{ $check }}>{{ $community }}</option>
                        @endforeach
                    </select>
                    {!! $errors->first('community_id','<p class="text-danger"><strong>:message</strong></p>') !!}
                </div>
                <div class="form-group col-6">
                    {{ Form::label('amenity', 'Select Amenity') }}
                    <select name="external_amenity_id" class="select form-control" style="width:100%" id="amenity">
                        <option value="">Select Amenity</option>
                    </select>
                    {!! $errors->first('external_amenity_id','<p class="text-danger"><strong>:message</strong></p>') !!}
                </div>
            </div>
        @endif
        @if(!isset($generalclass))
        <hr/>
        <h4>Sessions</h4>
        <hr/>
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        {{ Form::label('date range', 'Date Range') }}
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text">
                                    <i class="far fa-calendar-alt"></i>
                                </span>
                            </div>
                            <input type="text" class="form-control float-right daterange" id="daterange" name="daterange" autocomplete="off"/>
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    {{ Form::label('days', 'Session Days') }}
                    @foreach ($days as $i=>$day)
                        <div class="row">
                            <div class="col-md-3 col-sm-12 slot-switch">
                                <div class="form-group">
                                    {{ Form::label('day', $day) }}
                                    <div class="input-group">
                                        {{ Form::checkbox('days[]', $day, null, ['data-bootstrap-switch', 'class' => 'custom-control exclude-days'] ) }}
                                    </div>
                                    {!! $errors->first('days.*','<p class="text-danger"><strong>:message</strong></p>') !!}
                                </div>
                            </div>
                            <div class="col-md-9 col-sm-12 slot-box" style="display: none;">
                                <div class="row">
                                    <div class="form-group col-md-3 col-sm-12">
                                        {{ Form::label('start', "Start Time") }}<br>
                                        <div class='input-group date' >
                                            {{ Form::text("start_at[{$day}][]", null, array("autocomplete" => "off", 'class' => 'timepicker form-control '.($errors->has("start_at[{$day}]") ? ' is-invalid' : ''))) }}
                                        </div>
                                        {!! $errors->first("start_at[$day]", '<p class="text-danger"><strong>:message</strong></p>') !!}
                                    </div>
                                    <div class="form-group col-md-3 col-sm-12">
                                        {{ Form::label('end', "End Time") }}<br>
                                        <div class='input-group date' >
                                            {{ Form::text("end_at[{$day}][]", null, array("autocomplete" => "off", 'class' => 'timepicker form-control '.($errors->has("end_at[{$day}]") ? ' is-invalid' : ''))) }}
                                        </div>
                                        {!! $errors->first("end_at[$day]", '<p class="text-danger"><strong>:message</strong></p>') !!}
                                    </div>
                                    <div class="form-group col-md-3 col-sm-12">
                                        {{ Form::label('capacity', "Capacity") }}<br>
                                        <div class='input-group capacity'>
                                            {{ Form::text("capacity[{$day}][]", null, array("autocomplete" => "off", 'class' => 'form-control '.($errors->has("capacity[{$day}]") ? ' is-invalid' : ''))) }}
                                        </div>
                                        {!! $errors->first("capacity[$day]", '<p class="text-danger"><strong>:message</strong></p>') !!}
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                <input type="hidden" name="community_name" value="" id="community_name"/>
                <input type="hidden" name="amenity_name" value="" id="amenity_name"/>
            </div>
        @endif
    </div>
    <div class="card-footer">
        <a href="{{ route('admin.generalclass.index', ['type' => $type]) }}" class="btn btn-default">Cancel</a>
        {{ Form::submit(isset($submitButtonText) ? $submitButtonText : 'Create', array('class' => 'btn btn-info float-right')) }}
    </div>
</div>

@section('js')
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/inputmask/4.0.9/jquery.inputmask.bundle.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.standalone.min.css" />
<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.5/jquery.timepicker.min.css">
<script src="//cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.5/jquery.timepicker.min.js"></script>
<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key={{config('settings.google_api_key')}}&libraries=places&language=en&callback=initMap" async defer></script>
<script type="text/javascript">
    $(function () {
        var amenityDetails = null;
        var editId = null;
        $('body').on('change', '#community_id', function(){
            $('.overlay').show();
            var id = $(this).val();
            $.post('{{route('admin.fetch.aminities')}}', {"_token": "{{ csrf_token() }}", "community_id" : id }, function(data){
                var options = "";
                amenityDetails = data.data;
                options += "<option value=''>Select</option>";
                $.each(amenityDetails, function(key, value){
                    var sel = key == editId ? 'selected' : '';
                    options += "<option value='"+key+"' "+sel+">" + value + "</option>";
                });
                var community= $('#community_id').find(":selected").text();
                $('#community_name').val(community);
                $('#amenity').html(options);
                $('.overlay').hide();
            });
        });

        $('#amenity').on('change', function(){
            var amenity = $(this).val();
            var amenityname = $('#amenity').find(":selected").text();
            $('#amenity_name').val(amenityname);
            var item = _.find(amenityDetails, function(obj) {
                return obj.id_community_asset == amenity;
            });
            $('.max-members').html(item['max_members']);
            $('.location').html(item['location']);
            var slotHtml = "";
            $.each(item['slots'], function(i, slot){
                slotHtml += slot.slot_start + " - " + slot.slot_end + "<br>";
            });
            $('.slots').html(slotHtml);
        });
        //Date range picker
        // $('#daterange').daterangepicker({
        //     "minDate": "{{ date('d/m/Y') }}",
        //     locale: {
        //         format: 'DD/MM/YYYY'
        //     }
        // });
        // $('#daterange').val('');
        $('#daterange').daterangepicker({
            minDate : "{{ date('d/m/Y') }}",
            locale : {
                format: 'DD/MM/YYYY'
            }
        });
        $('#daterange').val('');

        $('#start_date').datepicker({
            format: 'dd/mm/yyyy',
        });
        $('#end_date').datepicker({
            format: 'dd/mm/yyyy',
        });

        $('.exclude-days').on('switchChange.bootstrapSwitch', function (event, state) {
            if(state){
                $(this).parents('.slot-switch').next('.slot-box').show();
            }else{
                $(this).parents('.slot-switch').next('.slot-box').hide();
            }
        });
        $('.timepicker').timepicker({
            timeFormat: 'h:mm p',
            interval: 15,
            dynamic: false,
            dropdown: true,
            scrollbar: true
        });
    });

    
    <?php if(!empty($generalclass) && !empty($generalclass->start_date) && !empty($generalclass->end_date)){?>
        $("#start_date").datepicker("setDate", "{{$generalclass->start_date->format('d/m/Y')}}");
        $("#end_date").datepicker("setDate", "{{$generalclass->end_date->format('d/m/Y')}}");    
    <?php }?>
    $("#categories").select2();
    $("#categories").on('change', function(){
        var selected = $(this).val();
        if(selected != null)
        {
            if(selected.indexOf('All')>=0){
                $(this).val('All').select2();
            }
        }
    });
</script>
@stop

