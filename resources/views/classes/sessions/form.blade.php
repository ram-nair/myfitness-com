<div class="card card-outline card-info">
    <div class="overlay" style="display: none;">
        <i class="fas fa-2x fa-sync-alt fa-spin"></i>
    </div>
    <div class="card-body pad">
        @if($type == "offline")
            <div class="row">
                <div class="form-group col-6">
                    {{ Form::label('amenity', 'Select Amenity') }}
                    <select name="external_amenity_id" class="select form-control" style="width:100%" id="amenity">
                        <option value="">Select Amenity</option>
                        @foreach ($amenities as $id_community_asset => $assets_name)
                            <option value="{{ $id_community_asset }}">{{ $assets_name }}</option>
                        @endforeach
                    </select>
                    {!! $errors->first('external_amenity_id','<p class="text-danger"><strong>:message</strong></p>') !!}
                </div>
            </div>
        @endif
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
                        <input type="text" class="form-control float-right daterange" id="daterange" name="daterange" autocomplete="off" required>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                {{ Form::label('days', 'Session Days') }}
                @foreach ($days as $i=>$day)
                    <div class="row">
                        <div class="col-1 slot-switch">
                            <div class="form-group">
                                {{ Form::label('day', $day) }}
                                <div class="input-group">
                                    {{ Form::checkbox('days[]', $day, null, ['data-bootstrap-switch', 'class' => 'custom-control exclude-days'] ) }}
                                </div>
                                {!! $errors->first('days.*','<p class="text-danger"><strong>:message</strong></p>') !!}
                            </div>
                        </div>
                        <div class="col-11 slot-box" style="display: none;">
                            <div class="row">
                                <div class="form-group col-2">
                                    {{ Form::label('start', "Start Time") }}<br>
                                    <div class='input-group date' >
                                        {{ Form::text("start_at[{$day}][]", null, array("autocomplete" => "off", 'class' => 'timepicker form-control '.($errors->has("start_at[{$day}]") ? ' is-invalid' : ''))) }}
                                    </div>
                                    {!! $errors->first("start_at[$day]", '<p class="text-danger"><strong>:message</strong></p>') !!}
                                </div>
                                <div class="form-group col-2">
                                    {{ Form::label('end', "End Time") }}<br>
                                    <div class='input-group date' >
                                        {{ Form::text("end_at[{$day}][]", null, array("autocomplete" => "off", 'class' => 'timepicker form-control '.($errors->has("end_at[{$day}]") ? ' is-invalid' : ''))) }}
                                    </div>
                                    {!! $errors->first("end_at[$day]", '<p class="text-danger"><strong>:message</strong></p>') !!}
                                </div>
                                <div class="form-group col-2">
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
        </div>
    </div>
    <div class="card-footer">
        <a href="{{ route('admin.generalclass.index') }}" class="btn btn-default">Cancel</a>
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
        
        // $('#amenity').on('change', function(){
        //     var amenity = $(this).val();
        //     var amenityname = $('#amenity').find(":selected").text();
        //     $('#amenity_name').val(amenityname);
        //     var item = _.find(amenityDetails, function(obj) {
        //         return obj.id_community_asset == amenity;
        //     });
        //     $('.max-members').html(item['max_members']);
        //     $('.location').html(item['location']);
        //     var slotHtml = "";
        //     $.each(item['slots'], function(i, slot){
        //         slotHtml += slot.slot_start + " - " + slot.slot_end + "<br>";
        //     });
        //     $('.slots').html(slotHtml);
        // });
        //Date range picker
        $('#daterange').daterangepicker({
            "minDate": "{{ date('d/m/Y') }}",
            locale: {
                format: 'DD/MM/YYYY'
            }
        });
        $('#daterange').val('');
        $('#daterange').daterangepicker({
            minDate : "{{ date('d/m/Y') }}",
            locale : {
                format: 'DD/MM/YYYY'
            }
        });
        $('#daterange').val('');

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

    $('#start_date').datepicker({
        format: 'dd/mm/yyyy',
    });
    $('#end_date').datepicker({
        format: 'dd/mm/yyyy',
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

