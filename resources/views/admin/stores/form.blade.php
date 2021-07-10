<div class="card card-outline card-info">
    <div class="overlay" style="display: none;">
        <i class="fas fa-2x fa-sync-alt fa-spin"></i>
    </div>
    <div class="card-body pad">
        <div class="row">
            <div class="col">
                <div class="form-group">
                    {{ Form::label('name', 'Store App Name') }}
                    {{ Form::text('name', null, array('required','class' => 'form-control'.($errors->has('name') ? ' is-invalid' : ''))) }}
                    {!! $errors->first('name','<p class="text-danger"><strong>:message</strong></p>') !!}
                </div>
                <div class="form-group">
                    {{ Form::label('store_fullname', 'Store Backend Name') }}
                    {{ Form::text('store_fullname', null, array('required','class' => 'form-control'.($errors->has('store_fullname') ? ' is-invalid' : ''))) }}
                    {!! $errors->first('store_fullname','<p class="text-danger"><strong>:message</strong></p>') !!}
                </div>
                <div class="form-group input-group">
                    {{ Form::label('email', 'Email') }}
                    <div class="input-group">
                        <div class="input-group-prepend">                    
                            <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                        </div>
                        {{ Form::email('email', null, array('required' ,'class' => 'form-control'.($errors->has('email') ? ' is-invalid' : ''))) }}
                        {!! $errors->first('email','<p class="text-danger"><strong>:message</strong></p>') !!}
                    </div>
                </div>
                <div class="form-group">
                    {{ Form::label('mobile', 'Mobile') }}
                    <div class="input-group">
                        <div class="input-group-prepend">                    
                            <span class="input-group-text"><i class="fas fa-phone"></i></span>
                        </div>
                        {{ Form::number('mobile', null, array('required', 'minlength' => 7, 'maxlength' => 12, 'class' => 'form-control'.($errors->has('mobile') ? ' is-invalid' : ''))) }}
                        {!! $errors->first('mobile','<p class="text-danger"><strong>:message</strong></p>') !!}
                    </div>
                </div>

            </div>
            <div class="col">
                

                 <div class="form-group">
                    <label for="exampleInputFile">Image (Recommended : {{$imageSize['aspectRatioW']}}x{{$imageSize['aspectRatioH']}})</label>
                    <div class="input-group"><?php
                         if(!empty($store->image)) {
                            $img = $store->image;
                            ?>
                             <img class="img-preview-holder" src="{{asset('uploads/store/images/'.$img)}}" alt="Preview Image" />
                       <?php  } else {
                             $img = url('/')."/images/no-image.jpg";
                            ?>
                            <img class="img-preview-holder" src="{{$img}}" alt="Preview Image" />
                       <?php  }?>
                        <div class="custom-file">
                            <input type="file" name="images" data-rule-extension="jpg|png|jpeg" data-msg-extension="Please select jpg or png image" class="image img-preview form-control-file custom-file-input" id="exampleInputFile">
                            <label class="custom-file-label" for="exampleInputFile">Choose file</label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
           {{-- <div class="col-2">
                <div class="form-group">
                    {{ Form::label('Min', 'Min order Amount') }}
                    {{ Form::text('min_order_amount', null, array('required','id'=>'min_order_amount','class' => 'form-control'.($errors->has('min_order_amount') ? ' is-invalid' : ''))) }}
                    {!! $errors->first('min_order_amount','<p class="text-danger"><strong>:message</strong></p>') !!}
                </div>
            </div>
            --}}
            
            <div class="col-6">
                <div class="row">
                    <div class="col">
                        {{ Form::label('status', 'Status') }}
                        <div class="form-group">
                            {{ Form::checkbox('active', 1, null, ['data-bootstrap-switch', 'id'=>'active', 'class' => 'custom-control'] ) }}
                        </div>
                    </div>
                  {{--  <div class="col">
                        {{ Form::label('credit_card', 'Credit card') }}
                        <div class="form-group">
                            {{ Form::checkbox('credit_card', 1, null, ['data-bootstrap-switch', 'id'=>'credit_card', 'class' => 'custom-control'] ) }}
                        </div>
                    </div>
                    <div class="col">
                        {{ Form::label('cash_accept', 'Cash') }}
                        <div class="form-group">
                            {{ Form::checkbox('cash_accept', 1, null, ['data-bootstrap-switch', 'id'=>'cash_accept','class'=>'custom-control'] ) }}
                        </div>
                    </div>
                   --}}
                    {{-- <div class="col">
                        <label class="" for="customSwitch3">Featured</label>
                        <div class="form-group">
                            <input type="checkbox" name="featured" value="1" id="custdomSwitch3" @if (empty($store)) checked @endif @if (!empty($store) && $store->featured == 1) checked @endif data-bootstrap-switch>
                        </div>
                    </div> --}}
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="form-group">
                    {{ Form::label('location', 'Location') }}<br>
                    <i>NB: 1. Use Draw a Shape Tool to draw a region. 2. Double click a region to delete.</i>
                    {{ Form::text('location', null, array('required','id'=>'location','class' => 'form-control'.($errors->has('location') ? ' is-invalid' : ''))) }}
                    {!! $errors->first('location','<p class="text-danger"><strong>:message</strong></p>') !!}
                </div>
                <div class="form-group">
                    <div id="map" style="height:400px;"></div> 
                </div>
            </div>
            <div class="col">
                <div class="row">
                    <div class="col">
                        <div class="form-group">
                            {{ Form::label('name', 'Latitude') }}
                            {{ Form::text('latitude', null, array('required','id'=>'latitude','class' => 'form-control'.($errors->has('latitude') ? ' is-invalid' : ''))) }}
                            {!! $errors->first('latitude','<p class="text-danger"><strong>:message</strong></p>') !!}
                        </div>
                    </div>
                    <div class="col">
                        <div class="form-group">
                            {{ Form::label('longt', 'Longitude') }}
                            {{ Form::text('longitude', null, array('required','id'=>'longitude','class' => 'form-control'.($errors->has('longitude') ? ' is-invalid' : ''))) }}
                            {!! $errors->first('longitude','<p class="text-danger"><strong>:message</strong></p>') !!}
                        </div>
                    </div>
                </div>
               {{--<div class="row">
                    <div class="col">
                        <div class="form-group">
                            {{ Form::label('payment_charge', 'Payment Charge in (%)') }}
                            {{ Form::number('payment_charge', null, array('max' => '100', 'id'=>'payment_charge','class' => 'form-control'.($errors->has('payment_charge') ? ' is-invalid' : ''))) }}
                            {!! $errors->first('payment_charge','<p class="text-danger"><strong>:message</strong></p>') !!}
                        </div>
                    </div>
                </div>--}}
                <div class="row">
                    <div class="col">
                        <div class="form-group">
                            {{ Form::label('payment_charge', 'Shipping charge') }}
                            {{ Form::number('shipping_charge', null, array('max' => '100', 'id'=>'shipping_charge','class' => 'form-control'.($errors->has('shipping_charge') ? ' is-invalid' : ''))) }}
                            {!! $errors->first('shipping_charge','<p class="text-danger"><strong>:message</strong></p>') !!}
                        </div>
                    </div>
                </div>
             </div>
        </div>
        <div class="row">
            <div class="col">
                <div class="form-group">
                    {{ Form::label('Description', 'Description') }}<br>
                    {!! Form::textarea('description',null,['class'=>'form-control editor-medium', 'rows' => 2, 'cols' => 40]) !!}
                    {!! $errors->first('description','<p class="text-danger"><strong>:message</strong></p>') !!}
                </div>
            </div>
            
        </div>
             </div>
    <div class="card-footer">
        <input type="hidden" name="polygon_data" class="polygons" value="" />
        {{ Form::submit(isset($submitButtonText) ? $submitButtonText : 'Create', array('class' => 'btn btn-info float-right')) }}
        <a href="{{ route('admin.stores.index') }}" class="btn btn-default ">Cancel</a>
    </div>
</div>

@section('js')


<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/inputmask/4.0.9/jquery.inputmask.bundle.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.standalone.min.css" />
<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key={{config('settings.google_api_key')}}&libraries=places,drawing&language=en&callback=initMap" async defer></script>
<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.5/jquery.timepicker.min.css">
<script src="//cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.5/jquery.timepicker.min.js"></script>
<script type="text/javascript">
    $('#contract_start_date').datepicker({
        format: 'dd/mm/yyyy',
    });
    $('#contract_end_date').datepicker({
        format: 'dd/mm/yyyy',
    });

    var latitude = document.getElementById('latitude');
    var longitude = document.getElementById('longitude');    
    var map;
    var input = document.getElementById('location');

    var polygons = [];
    var allNewPolys = [];
    function initMap() {
        var selectedShape;

        var userLocation = new google.maps.LatLng(
            latitude.value,
            longitude.value
        );

        map = new google.maps.Map(document.getElementById('map'), {
            center: userLocation,
            zoom: 15
        });

        var service = new google.maps.places.PlacesService(map);
        var autocomplete = new google.maps.places.Autocomplete(input);
        var infowindow = new google.maps.InfoWindow();

        autocomplete.bindTo('bounds', map);

        var infowindow = new google.maps.InfoWindow({
            content: "Store Location",
        });

        var marker = new google.maps.Marker({
            map: map,
            draggable: true,
            anchorPoint: new google.maps.Point(0, -29)
        });

        marker.setVisible(true);
        marker.setPosition(userLocation);
        infowindow.open(map, marker);

        if (navigator.geolocation && latitude.value == "") {
            navigator.geolocation.getCurrentPosition(function(location) {
                var userLocation = new google.maps.LatLng(
                    location.coords.latitude,
                    location.coords.longitude
                );
                marker.setPosition(userLocation);
                map.setCenter(userLocation);
                map.setZoom(13);
            });
        }

        google.maps.event.addListener(map, 'click', updateMarker);
        google.maps.event.addListener(marker, 'dragend', updateMarker);

        function updateMarker(event) {
            var geocoder = new google.maps.Geocoder();
            geocoder.geocode({'latLng': event.latLng}, function (results, status) {
                if (status == google.maps.GeocoderStatus.OK) {
                    if (results[0]) {
                        input.value = results[0].formatted_address;
                        updateForm(event.latLng.lat(), event.latLng.lng(), results[0].formatted_address);
                    } else {
                        alert('No Address Found');
                    }
                } else {
                    alert('Geocoder failed due to: ' + status);
                }
            });

            marker.setPosition(event.latLng);
            map.setCenter(event.latLng);
        }

        autocomplete.addListener('place_changed', function(event) {
            marker.setVisible(false);
            var place = autocomplete.getPlace();

            if (place.hasOwnProperty('place_id')) {
                if (!place.geometry) {
                    window.alert("Autocomplete's returned place contains no geometry");
                    return;
                }
                updateLocation(place.geometry.location);
            } else {
                service.textSearch({
                    query: place.name
                }, function(results, status) {
                    if (status == google.maps.places.PlacesServiceStatus.OK) {
                        updateLocation(results[0].geometry.location, results[0].formatted_address);
                        input.value = results[0].formatted_address;
                    }
                });
            }
        });


        //create drawing
        var polyOptions = {
          editable: true
        };
        const drawingManager = new google.maps.drawing.DrawingManager({
            // drawingMode: google.maps.drawing.OverlayType.POLYGON,
            drawingControl: true,
            drawingControlOptions: {
                position: google.maps.ControlPosition.TOP_CENTER,
                drawingModes: [
                    google.maps.drawing.OverlayType.POLYGON,
                    //google.maps.drawing.OverlayType.MARKER,
                    //google.maps.drawing.OverlayType.CIRCLE,
                    //google.maps.drawing.OverlayType.POLYLINE,
                    //google.maps.drawing.OverlayType.RECTANGLE,
                ],
            },
            polygonOptions: polyOptions,
        });
        drawingManager.setMap(map);
        google.maps.event.addListener(drawingManager, "overlaycomplete", function (
            polygon
        ) {
            var newPolys = [];
            $.each(polygon.overlay.getPath().getArray(), function (key, latlng) {
                var lat = latlng.lat();
                var lon = latlng.lng();
                newPolys.push(latlng.lat() + " " + latlng.lng());
            });
            allNewPolys.push(newPolys);
            $('.polygons').val(JSON.stringify(allNewPolys));
            // console.log( JSON.stringify(allNewPolys))
            // Switch back to non-drawing mode after drawing a shape.
            drawingManager.setDrawingMode(null);

            // Add an event listener that selects the newly-drawn shape when the user
            // mouses down on it.
            var newShape = polygon.overlay;
            // newShape.type = polygon.type;
            addClicks(newShape);
            setSelection(newShape);
            // $('.overlay').show();
            // $.ajax({
            //     url: "{{ url('admin/add-marker') }}",
            //     success: function(resp) {
            //         $('.overlay').hide();
            //     }
            // });
        });
        
        google.maps.event.addListener(map, 'click', clearSelection);

        function FitBounds(){
            var bounds = new google.maps.LatLngBounds();
            for (var i=0; i < polygons.length; i++){
                var paths = polygons[i].getPaths();
                paths.forEach(function(path){
                    var ar = path.getArray();
                    for(var i=0, l = ar.length; i <l; i++){
                        bounds.extend(ar[i]);
                    }
                });
            }
            bounds.extend(new google.maps.LatLng(parseFloat(latitude.value), parseFloat(longitude.value)));
            map.fitBounds(bounds);
            // console.log(bounds.getCenter())
            // var allPolygonBounds = new google.maps.LatLngBounds();
            // //combine all of your polygons together into a single bounds object that contains them all using union
            // allPolygonBounds.union(bounds);
            // map.fitBounds(allPolygonBounds);
            // map.setCenter(bounds.getCenter());
        }

        function addClicks(newShape) {
            google.maps.event.addListener(newShape, 'click', function() {
                newShape.set('strokeColor', '#FF8C00');
                setSelection(newShape);
            });
            google.maps.event.addListener(newShape, 'dblclick', function(e) {
                var conf = confirm("Are you sure to delete this area?");
                if(conf){
                    selectedShape = newShape;
                    selectedShape.setMap(null);
                    selectedShape = null;
                    e.stop();
                    $('.overlay').show();
                    $.ajax({
                        url: "{{ url('admin/delete-marker?marker_id=') }}" + newShape.customId,
                        success: function(resp) {
                            $('.overlay').hide();
                        }
                    });
                }
            });
        }

        function clearSelection() {
            if (selectedShape) {
                selectedShape.set('strokeColor', '#000000')
                selectedShape.setEditable(false);
                selectedShape = null;
            }
        }

        function setSelection(shape) {
            clearSelection();
            selectedShape = shape;
            shape.setEditable(true);
        }

        function selectColor() {
            var polygonOptions = drawingManager.get('polygonOptions');
            polygonOptions.fillColor = '#FF8C00';
            drawingManager.set('polygonOptions', polygonOptions);
        }

        function updateLocation(location) {
            map.setCenter(location);
            marker.setPosition(location);
            marker.setVisible(true);
            infowindow.open(map, marker);
            updateForm(location.lat(), location.lng(), input.value);
        }

        function updateForm(lat, lng, addr) {
            // console.log(lat,lng, addr);
            latitude.value = lat;
            longitude.value = lng;
            input.value = addr;
        }
    }

    $('.timepicker').timepicker({
        timeFormat: 'h:mm p',
        interval: 15,
        dynamic: false,
        dropdown: true,
        scrollbar: true
    });

    $('#my_location').on('switchChange.bootstrapSwitch', function (event, state) {
        if($("#my_location").is(':checked')) {
            $('#mylocation_cost').show();
        } else {
            $('#mylocation_cost').hide();
        }
    });
</script>
@stop