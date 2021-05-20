<div class="card card-outline card-info">
    <div class="overlay" style="display: none;">
        <i class="fas fa-2x fa-sync-alt fa-spin"></i>
    </div>
    <div class="card-body pad">

        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="exampleInputFile">Merchant Image (Recommended : {{$imageSize['aspectRatioW']}}x{{$imageSize['aspectRatioH']}})</label>
                    <div class="input-group">
                        <?php
                        if(!empty($offerBrand->image)) {
                            $img = $offerBrand->image;
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
                        if(!empty($offerBrand->cover_image)) {
                            $img = $offerBrand->cover_image;
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
                    {{ Form::label('name', 'Name') }}
                    {{ Form::text('name', null, array('required'=>'','class' => 'form-control'.($errors->has('name') ? ' is-invalid' : '' ))) }}
                    {!! $errors->first('name','<p class="text-danger"><strong>:message</strong></p>') !!}
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    {{ Form::label('category_id', 'Category') }}<br>
                    <select class='form-control select2' required name="category_id" id="category_id" >
                        <option value="">Select Category</option>
                        @foreach($categories as $cat)
                            <option value="{{$cat->id}}" @if(isset($offerBrand) && $offerBrand->category_id == $cat->id) selected @endif>{{$cat->name}}</option>
                        @endforeach
                    </select>
                    {!! $errors->first('brand_id','<p class="text-danger"><strong>:message</strong></p>') !!}
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    {{ Form::label('phone_number', 'Phone Number') }}
                    {{ Form::text('phone_number', null, array('required'=>'','class' => 'form-control'.($errors->has('phone_number') ? ' is-invalid' : '' ))) }}
                    {!! $errors->first('phone_number','<p class="text-danger"><strong>:message</strong></p>') !!}
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    {{ Form::label('email', 'Email') }}
                    {{ Form::email('email', null, array('required'=>'','class' => 'form-control'.($errors->has('email') ? ' is-invalid' : '' ))) }}
                    {!! $errors->first('email','<p class="text-danger"><strong>:message</strong></p>') !!}
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
            <div class="col-md-3">
                @php
                  $start_at = (isset($offerBrand))?$offerBrand->working_start_hour : null;
                  $end_at = (isset($offerBrand))?$offerBrand->working_end_hour : null;
                @endphp

                <div class="form-group" >
                    {{ Form::label('longt', 'Start At') }}
                    {{ Form::text("start_at", $start_at, array("required", "autocomplete" => "off", 'class' => 'timepicker form-control '.($errors->has("start_at") ? ' is-invalid' : ''))) }}
                    {!! $errors->first("start_at", '<p class="text-danger"><strong>:message</strong></p>') !!}
                </div>
            </div>

            <div class="col-md-3">
                <div class="form-group" >
                    {{ Form::label('longt', 'End At') }}
                    {{ Form::text("end_at", $end_at, array("required", "autocomplete" => "off", 'class' => 'timepicker form-control '.($errors->has("end_at") ? ' is-invalid' : ''))) }}
                    {!! $errors->first("end_at", '<p class="text-danger"><strong>:message</strong></p>') !!}
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
        <a href="{{ route('admin.offer-brand.index') }}" class="btn btn-default">Cancel</a>
        {{ Form::submit(isset($submitButtonText) ? $submitButtonText : 'Create', array('class' => 'btn btn-info float-right')) }}
    </div>
</div>

@section('js')
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.5/jquery.timepicker.min.css">
    <script src="//cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.5/jquery.timepicker.min.js"></script>]
    <script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key={{config('settings.google_api_key')}}&libraries=places,drawing&language=en&callback=initMap" async defer></script>

    <script type="text/javascript">
        $(function () {
            $('.timepicker').timepicker({
                timeFormat: 'h:mm p',
                interval: 15,
                dynamic: false,
                dropdown: true,
                scrollbar: true
            });
        });
        var latitude = document.getElementById('latitude');
        var longitude = document.getElementById('longitude');
        var map;
        var input = document.getElementById('location');

        function initMap() {console.log(latitude.value,
            longitude.value)
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

            if (navigator.geolocation) {
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

    </script>
@stop
