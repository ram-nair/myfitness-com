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
                    {{ Form::label('password', 'Password') }}<br>
                    <div class="input-group">
                        <div class="input-group-prepend">                    
                            <span class="input-group-text"><i class="fas fa-key"></i></span>
                        </div>
                        {{ Form::password('password', array('minlength' => 6, empty($store) ? "required" : "",'class' => 'form-control'.($errors->has('password') ? ' is-invalid' : ''))) }}
                        {!! $errors->first('password','<p class="text-danger"><strong>:message</strong></p>') !!}
                    </div>
                </div>

                <div class="form-group">
                    {{ Form::label('password', 'Confirm Password') }}<br>
                    <div class="input-group">
                        <div class="input-group-prepend">                    
                            <span class="input-group-text"><i class="fas fa-key"></i></span>
                        </div>
                        {{ Form::password('password_confirmation',  array("data-rule-equalTo" => "#password", 'minlength' => 6, empty($store) ? "required" : "",'class' => 'form-control'.($errors->has('password_confirmation') ? ' is-invalid' : ''))) }}
                        {!! $errors->first('password_confirmation','<p class="text-danger"><strong>:message</strong></p>') !!}
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
                    <label for="title">Select a Vendor</label>
                    <select name="vendor_id"  class="form-control" style="width:100%" required >
                        @foreach ($vendors as $vendor)
                            <option value="{{$vendor->id}}" @if(!empty($store) && ($vendor->id==$store->vendor_id)) selected @endif>{{$vendor->name}}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    {{ Form::label('business type', 'Business Type') }}<br>
                    <select name="business_type_id"  class="form-control" style="width:100%" required  id="type_id">
                        @foreach ($businessType as $business)
                        <option value="{{$business->id}}" @if(!empty($store) && ($business->id==$store->business_type_id)) selected @endif>{{$business->name}}</option>
                        @endforeach
                    </select>
                   {!! $errors->first('active','<p class="text-danger"><strong>:message</strong></p>') !!}
                </div>
              @php 
              $display ="display:none";
                if(!empty($store) && ($store->service_type)){
                    $display = "display:block";
                }
              @endphp
             <div class="form-group" id="service_div" style="{{$display}}">
                 <label>Service Type </label>
                <select class="form-control" name="service_type" id="service_type" >
                    <option value="" >Select Service Type</option>
                    <option value="service_type_1"  @if(!empty($store) && ('service_type_1'==$store->service_type)) selected @endif>Service Type 1</option>
                    <option value="service_type_2"  @if(!empty($store) && ('service_type_2'==$store->service_type)) selected @endif>Service Type 2</option>
                    <option value="service_type_3"  @if(!empty($store) && ('service_type_3'==$store->service_type)) selected @endif>Service Type 3</option>
                </select>
              </div>  
                <div class="form-group">
                    {{ Form::label('Category', 'Business Category') }}<br>
                    <select name="business_type_category_id" class="form-control" required id="cat_id">
                        <option value="">Sub Category</option>
                    </select>
                    {!! $errors->first('active','<p class="text-danger"><strong>:message</strong></p>') !!}
                </div>    
                 @php 
                    $display ="display:none";
                        if(!empty($store) && ($store->service_type=='service_type_2')){
                            $display = "display:block";
                        }
                    @endphp
                <div class="form-group" id="location_div" style="{{$display}}">
                    <label>Location Type </label>
                    <div class="col">
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-3 col-sm-6">
                                    <div>{{ Form::label('credit_card', 'In store') }}</div>
                                    {{ Form::checkbox('in_store', 1, null, ['data-bootstrap-switch', 'id'=>'in_store', 'class' => 'custom-control'] ) }}
                                </div>
                                <div class="col-md-3 col-sm-6">
                                <div>{{ Form::label('credit_card', 'My location') }}</div>
                                    {{ Form::checkbox('my_location', 1, null, ['data-bootstrap-switch', 'id'=>'my_location', 'class' => 'custom-control'] ) }}
                                </div>
                                <div class="col-md-6 col-sm-12">
                                    @php 
                                        $display ="display:none";
                                        if(!empty($store) && ($store->my_location==1)){
                                            $display = "display:block";
                                        }
                                    @endphp
                                <div class="form-group" id="mylocation_cost" style="{{$display}}">
                                    {{ Form::label('mobile', 'My Location charge') }}
                                    {{ Form::text('on_my_location_charge', null, array('class' => 'form-control'.($errors->has('on_my_location_charge') ? ' is-invalid' : ''))) }}
                                </div>
                                </div>
                            </div>
                        </div>
                    </div>    
                </div>       
                @php 
                    $genderDisplay ="display:none";
                    if(!empty($store) && ($store->service_type=='service_type_2' || $store->service_type=='service_type_3')){
                        $genderDisplay = "display:block";
                    }
                @endphp  
                <div class="form-group" id="gender_preference_div" style="{{ $genderDisplay }}">
                    <label>Gender Preference</label>
                    <div class="col">
                        <div class="form-group">
                            <div class="row">
                                <div class="col">
                                    {{ Form::label('male', 'Male') }}
                                    <div class="form-group">
                                        {{ Form::checkbox('male', 1, null, ['data-bootstrap-switch', 'id'=>'male', 'class' => 'custom-control'] ) }}
                                    </div>
                                </div>
                                <div class="col">
                                    {{ Form::label('female', 'Female') }}
                                    <div class="form-group">
                                        {{ Form::checkbox('female', 1, null, ['data-bootstrap-switch', 'id'=>'female','class'=>'custom-control'] ) }}
                                    </div>
                                </div>
                                <div class="col">
                                    {{ Form::label('any', 'Any') }}
                                    <div class="form-group">
                                        {{ Form::checkbox('any', 1, null, ['data-bootstrap-switch', 'id'=>'any','class'=>'custom-control'] ) }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>    
                <div class="form-group">
                    <label for="exampleInputFile">Image (Recommended : {{$imageSize['aspectRatioW']}}x{{$imageSize['aspectRatioH']}})</label>
                    <div class="input-group"><?php
                        if(!empty($store->image)) {
                            $img = $store->image;
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
        </div>
        <div class="row">
            <div class="col-2">
                <div class="form-group">
                    {{ Form::label('Min', 'Min order Amount') }}
                    {{ Form::text('min_order_amount', null, array('required','id'=>'min_order_amount','class' => 'form-control'.($errors->has('min_order_amount') ? ' is-invalid' : ''))) }}
                    {!! $errors->first('min_order_amount','<p class="text-danger"><strong>:message</strong></p>') !!}
                </div>
            </div>
            <div class="col-2">
                <div class="form-group">
                    {{ Form::label('name', 'Accuracy') }}
                    <select class="form-control" name="accuracy" id="accuracy" required>
                        <option value="" >Select</option>
                        @foreach (range(1, 5) as $val)
                            <option value="{{ $val }}"  @if(!empty($store) && ($val == $store->accuracy)) selected @endif>{{ $val }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-2">
                <div class="form-group">
                    {{ Form::label('speed', 'Speed') }}
                    <select class="form-control" name="speed" id="speed" required>
                        <option value="" >Select</option>
                        @foreach (range(1, 5) as $val)
                            <option value="{{ $val }}"  @if(!empty($store) && ($val == $store->speed)) selected @endif>{{ $val }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-6">
                <div class="row">
                    <div class="col">
                        {{ Form::label('status', 'Status') }}
                        <div class="form-group">
                            {{ Form::checkbox('active', 1, null, ['data-bootstrap-switch', 'id'=>'active', 'class' => 'custom-control'] ) }}
                        </div>
                    </div>
                    <div class="col">
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
                    <div class="col">
                        {{ Form::label('bring_card', 'Bring card') }}
                        <div class="form-group">
                            {{ Form::checkbox('bring_card', 1, null, ['data-bootstrap-switch', 'id'=>'bring_card','class'=>'custom-control'] ) }}
                        </div>
                    </div>
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
                <div class="row">
                    <div class="col-md-4">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group" >
                                    {{ Form::label('longt', 'Store Opening Time') }}
                                    {{ Form::text("start_at", null, array("required", "autocomplete" => "off", 'class' => 'timepicker form-control '.($errors->has("start_at") ? ' is-invalid' : ''))) }}
                                    {!! $errors->first("start_at", '<p class="text-danger"><strong>:message</strong></p>') !!}
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group" >
                                    {{ Form::label('longt', 'Store Closing Time') }}
                                    {{ Form::text("end_at", null, array("required", "autocomplete" => "off", 'class' => 'timepicker form-control '.($errors->has("end_at") ? ' is-invalid' : ''))) }}
                                    {!! $errors->first("end_at", '<p class="text-danger"><strong>:message</strong></p>') !!}
                                </div>
                            </div>
                        </div>


                        {{-- <div class="form-group">
                            {{ Form::label('name', 'Store Timing') }}
                            {{ Form::text('store_timing', null, array('required','id'=>'store_timing','class' => 'form-control'.($errors->has('latitude') ? ' is-invalid' : ''))) }}
                            {!! $errors->first('store_timing','<p class="text-danger"><strong>:message</strong></p>') !!}
                        </div> --}}
                    </div>
                    <div class="col">
                        <div class="form-group">
                            {{ Form::label('longt', 'Time to deliver') }}
                            {{ Form::text('time_to_deliver', null, array('required','id'=>'time_to_deliver','class' => 'form-control'.($errors->has('time_to_deliver') ? ' is-invalid' : ''))) }}
                            {!! $errors->first('time_to_deliver','<p class="text-danger"><strong>:message</strong></p>') !!}
                        </div>
                    </div>
                    <div class="col">
                        <div class="form-group">
                            {{ Form::label('sap_id', 'SAP Id') }}
                            {{ Form::text('sap_id', null, array('id'=>'sap_id','class' => 'form-control'.($errors->has('sap_id') ? ' is-invalid' : ''))) }}
                            {!! $errors->first('sap_id','<p class="text-danger"><strong>:message</strong></p>') !!}
                        </div>
                    </div>
                    <div class="col">
                        <div class="form-group">
                            {{ Form::label('service_charge', 'Service charge') }}
                            {{ Form::number('service_charge', null, array('id'=>'service_charge','class' => 'form-control'.($errors->has('service_charge') ? ' is-invalid' : ''))) }}
                            {!! $errors->first('service_charge','<p class="text-danger"><strong>:message</strong></p>') !!}
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <div class="form-group">
                            {{ Form::label('payment_charge', 'Payment Charge in (%)') }}
                            {{ Form::number('payment_charge', null, array('max' => '100', 'id'=>'payment_charge','class' => 'form-control'.($errors->has('payment_charge') ? ' is-invalid' : ''))) }}
                            {!! $errors->first('payment_charge','<p class="text-danger"><strong>:message</strong></p>') !!}
                        </div>
                    </div>
                    <div class="col">
                        <div class="form-group">
                            {{ Form::label('payment_charge_store_dividend', 'Payment charge store dividend (%)') }}
                            {{ Form::number('payment_charge_store_dividend', null, array('max' => '100', 'id'=>'payment_charge_store_dividend','class' => 'form-control'.($errors->has('payment_charge_store_dividend') ? ' is-invalid' : ''))) }}
                            {!! $errors->first('payment_charge_store_dividend','<p class="text-danger"><strong>:message</strong></p>') !!}
                        </div>
                    </div>
                    <div class="col">
                        <div class="form-group">
                            {{ Form::label('payment_charge_provis_dividend', 'Payment charge provis dividend (%)') }}
                            {{ Form::number('payment_charge_provis_dividend', null, array('max' => '100','id'=>'payment_charge_provis_dividend','class' => 'form-control'.($errors->has('payment_charge_provis_dividend') ? ' is-invalid' : ''))) }}
                            {!! $errors->first('payment_charge_provis_dividend','<p class="text-danger"><strong>:message</strong></p>') !!}
                        </div>
                    </div>
                    <div class="col">
                        {{ Form::label('date range', 'Contract Start Date') }}
                        <div class="input-group form-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                            </div>
                            {!! Form::text('contract_start_date', null, array("autocomplete" => "off", "data-rule-dateBefore" => "#contract_end_date", "data-inputmask-inputformat" => "dd/mm/yyyy",  'id'=>'contract_start_date', 'class' => 'form-control'.($errors->has('contract_start_date') ? ' is-invalid' : ''))) !!}
                            {!! $errors->first('contract_start_date','<p class="text-danger"><strong>:message</strong></p>') !!}

                        </div>
                    </div>
                    <div class="col">
                        {{ Form::label('date range', 'Contract End Date') }}
                        <div class="input-group form-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                            </div>
                            {{ Form::text('contract_end_date', null, array("autocomplete" => "off", "data-rule-dateAfter" => "#contract_start_date",'id'=>'contract_end_date', 'class' => 'form-control'.($errors->has('contract_end_date') ? ' is-invalid' : ''))) }}
                            {!! $errors->first('contract_end_date','<p class="text-danger"><strong>:message</strong></p>') !!}
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
                <div class="form-group">
                    {{ Form::label('Description', 'SLA') }}<br>
                    {!! Form::textarea('sla',null,['class'=>'form-control editor-medium', 'rows' => 2, 'cols' => 40]) !!}
                    {!! $errors->first('sla','<p class="text-danger"><strong>:message</strong></p>') !!}
                </div>
            </div>
            
        </div>
        {{-- Store Contact Details --}}
        <hr/>
        <h4>Store Contact Details</h4>
        <hr/>
        @php
            if(isset($storeContacts)):
                $diffCount = false;
                $superVisorCount = $storeContacts->count();
                $actualCount = config('settings.store_contact_fields_count');
                if($superVisorCount <= $actualCount){
                    $diffCount = $actualCount - $superVisorCount;
                }
            @endphp
                @foreach ($storeContacts as $contact)
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            {{ Form::label('Email', 'Email') }}
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                </div>
                                {{ Form::email("store[$contact->id][email]", $contact->email, array('class' => 'form-control'.($errors->has("store[$contact->id][email]") ? ' is-invalid' : ''))) }}
                                {!! $errors->first("store[$contact->id][email]",'<p class="text-danger"><strong>:message</strong></p>') !!}
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            {{ Form::label('Phone', 'Phone') }}
                            <div class="input-group">
                                <div class="input-group-prepend">                    
                                    <span class="input-group-text"><i class="fas fa-phone"></i></span>
                                </div>
                                {{ Form::number("store[$contact->id][phone]", $contact->phone, array( 'minlength' => 7, 'maxlength' => 12, 'class' => 'form-control'.($errors->has("store[$contact->id][phone]") ? ' is-invalid' : ''))) }}
                                {!! $errors->first("store[$contact->id][phone]",'<p class="text-danger"><strong>:message</strong></p>') !!}
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            @php
                endif
            @endphp
        @php
            $range = config('settings.store_contact_fields_count');
            if(isset($diffCount)){
                if($diffCount > 0){
                    $range = $diffCount;
                }
                else{
                    $range = 0;
                }
            }
        @endphp
        @if($range >= 1)
            @foreach (range(1, $range) as $i)
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            {{ Form::label('Email', 'Email') }}
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                </div>
                                {{ Form::email("store[new][$i][email]", null, array('class' => 'form-control'.($errors->has("store[new]r[$i][email]") ? ' is-invalid' : ''))) }}
                                {!! $errors->first("store[new][$i][email]",'<p class="text-danger"><strong>:message</strong></p>') !!}
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            {{ Form::label('Phone', 'Phone') }}
                            <div class="input-group">
                                <div class="input-group-prepend">                    
                                    <span class="input-group-text"><i class="fas fa-phone"></i></span>
                                </div>
                                {{ Form::number("store[new][$i][phone]", null, array( 'minlength' => 7, 'maxlength' => 12, 'class' => 'form-control'.($errors->has("store[new][$i][phone]") ? ' is-invalid' : ''))) }}
                                {!! $errors->first("store[new][$i][phone]",'<p class="text-danger"><strong>:message</strong></p>') !!}
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        @endif
        {{-- Store Contact END --}}
        {{-- Store Supervisor --}}
        <hr/>
        <h4>Store Supervisors</h4>
        <hr/>
        @php
            if(isset($storeSupervisors)):
                $diffCount = false;
                $superVisorCount = $storeSupervisors->count();
                $actualCount = config('settings.store_supervisor_contact_fields_count');
                if($superVisorCount <= $actualCount){
                    $diffCount = $actualCount - $superVisorCount;
                }
            @endphp
                @foreach ($storeSupervisors as $contact)
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            {{ Form::label('Name', 'Name') }}
                            {!! Form::text("supervisor[$contact->id][name]", $contact->name, array('class' => 'form-control'.($errors->has("supervisor[$contact->id][name]") ? ' is-invalid' : ''))) !!}
                            {!! $errors->first("supervisor[$contact->id][name]",'<p class="text-danger"><strong>:message</strong></p>') !!}
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            {{ Form::label('Email', 'Email') }}
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                </div>
                                {{ Form::email("supervisor[$contact->id][email]", $contact->email, array('class' => 'form-control'.($errors->has("supervisor[$contact->id][email]") ? ' is-invalid' : ''))) }}
                                {!! $errors->first("supervisor[$contact->id][email]",'<p class="text-danger"><strong>:message</strong></p>') !!}
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            {{ Form::label('Phone', 'Phone') }}
                            <div class="input-group">
                                <div class="input-group-prepend">                    
                                    <span class="input-group-text"><i class="fas fa-phone"></i></span>
                                </div>
                                {{ Form::number("supervisor[$contact->id][phone]", $contact->phone, array( 'minlength' => 7, 'maxlength' => 12, 'class' => 'form-control'.($errors->has("supervisor[$contact->id][phone]") ? ' is-invalid' : ''))) }}
                                {!! $errors->first("supervisor[$contact->id][phone]",'<p class="text-danger"><strong>:message</strong></p>') !!}
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            @php
                endif
            @endphp
        @php
            $range = config('settings.store_supervisor_contact_fields_count');
            if(isset($diffCount)){
                if($diffCount > 0){
                    $range = $diffCount;
                }
                else{
                    $range = 0;
                }
            }
        @endphp
        @if($range >= 1)
            @foreach (range(1, $range) as $i)
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            {{ Form::label('Name', 'Name') }}
                            {!! Form::text("supervisor[new][$i][name]", null, array('class' => 'form-control'.($errors->has("supervisor[new][$i][name]") ? ' is-invalid' : ''))) !!}
                            {!! $errors->first("supervisor[new][$i][name]",'<p class="text-danger"><strong>:message</strong></p>') !!}
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            {{ Form::label('Email', 'Email') }}
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                </div>
                                {{ Form::email("supervisor[new][$i][email]", null, array('class' => 'form-control'.($errors->has("superviso[new]r[$i][email]") ? ' is-invalid' : ''))) }}
                                {!! $errors->first("supervisor[new][$i][email]",'<p class="text-danger"><strong>:message</strong></p>') !!}
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            {{ Form::label('Phone', 'Phone') }}
                            <div class="input-group">
                                <div class="input-group-prepend">                    
                                    <span class="input-group-text"><i class="fas fa-phone"></i></span>
                                </div>
                                {{ Form::number("supervisor[new][$i][phone]", null, array( 'minlength' => 7, 'maxlength' => 12, 'class' => 'form-control'.($errors->has("supervisor[new][$i][phone]") ? ' is-invalid' : ''))) }}
                                {!! $errors->first("supervisor[new][$i][phone]",'<p class="text-danger"><strong>:message</strong></p>') !!}
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        @endif
        {{-- Supervisor END --}}
        {{-- Store Manager --}}
        <hr/>
        <h4>Store Managers</h4>
        <hr/>
        @php
            if(isset($storeManagers)):
                $diffCount = false;
                $managerCount = $storeManagers->count();
                $actualCount = config('settings.store_manager_contact_fields_count');
                if($superVisorCount <= $actualCount){
                    $diffCount = $actualCount - $managerCount;
                }
            @endphp
                @foreach ($storeManagers as $contact)
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            {{ Form::label('Name', 'Name') }}
                            {!! Form::text("manager[$contact->id][name]", $contact->name, array('class' => 'form-control'.($errors->has("manager[$contact->id][name]") ? ' is-invalid' : ''))) !!}
                            {!! $errors->first("manager[$contact->id][name]",'<p class="text-danger"><strong>:message</strong></p>') !!}
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            {{ Form::label('Email', 'Email') }}
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                </div>
                                {{ Form::email("manager[$contact->id][email]", $contact->email, array('class' => 'form-control'.($errors->has("manager[$contact->id][email]") ? ' is-invalid' : ''))) }}
                                {!! $errors->first("manager[$contact->id][email]",'<p class="text-danger"><strong>:message</strong></p>') !!}
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            {{ Form::label('Phone', 'Phone') }}
                            <div class="input-group">
                                <div class="input-group-prepend">                    
                                    <span class="input-group-text"><i class="fas fa-phone"></i></span>
                                </div>
                                {{ Form::number("manager[$contact->id][phone]", $contact->phone, array( 'minlength' => 7, 'maxlength' => 12, 'class' => 'form-control'.($errors->has("manager[$contact->id][phone]") ? ' is-invalid' : ''))) }}
                                {!! $errors->first("manager[$contact->id][phone]",'<p class="text-danger"><strong>:message</strong></p>') !!}
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            @php
                endif
            @endphp
        @php
            $range = config('settings.store_manager_contact_fields_count');
            if(isset($diffCount)){
                if($diffCount > 0){
                    $range = $diffCount;
                }
                else{
                    $range = 0;
                }
            }
        @endphp
        @if($range >= 1)
            @foreach (range(1, $range) as $i)
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            {{ Form::label('Name', 'Name') }}
                            {!! Form::text("manager[new][$i][name]", null, array('class' => 'form-control'.($errors->has("manager[new][$i][name]") ? ' is-invalid' : ''))) !!}
                            {!! $errors->first("manager[new][$i][name]",'<p class="text-danger"><strong>:message</strong></p>') !!}
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            {{ Form::label('Email', 'Email') }}
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                </div>
                                {{ Form::email("manager[new][$i][email]", null, array('class' => 'form-control'.($errors->has("manager[new]r[$i][email]") ? ' is-invalid' : ''))) }}
                                {!! $errors->first("manager[new][$i][email]",'<p class="text-danger"><strong>:message</strong></p>') !!}
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            {{ Form::label('Phone', 'Phone') }}
                            <div class="input-group">
                                <div class="input-group-prepend">                    
                                    <span class="input-group-text"><i class="fas fa-phone"></i></span>
                                </div>
                                {{ Form::number("manager[new][$i][phone]", null, array( 'minlength' => 7, 'maxlength' => 12, 'class' => 'form-control'.($errors->has("manager[new][$i][phone]") ? ' is-invalid' : ''))) }}
                                {!! $errors->first("manager[new][$i][phone]",'<p class="text-danger"><strong>:message</strong></p>') !!}
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        @endif
        {{-- Manager END --}}
    </div>
    <div class="card-footer">
        <input type="hidden" name="polygon_data" class="polygons" value="" />
        {{ Form::submit(isset($submitButtonText) ? $submitButtonText : 'Create', array('class' => 'btn btn-info float-right')) }}
        <a href="{{ route('admin.stores.index') }}" class="btn btn-default ">Cancel</a>
    </div>
</div>

@section('js')
<?php 
$polyStrings = null;
if(isset($store) && !$store->boundaries->isEmpty()){
    $polygonArr = $store->boundaries;
    if(!$polygonArr->isEmpty()){
        foreach ($polygonArr as $polygon) {
            $polys = sql_to_coordinates($polygon->positions);
            $polyStrings[$polygon->id] = json_encode($polys);
        }
    }
}
// dd($polyStrings);
?>
<script src="https://polyfill.io/v3/polyfill.min.js?features=default"></script>
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
    
    <?php if(!empty($store) && !empty($store->contract_start_date) && !empty($store->contract_end_date)){?>
        $("#contract_start_date").datepicker("setDate", "{{$store->contract_start_date->format('d/m/Y')}}");
        $("#contract_end_date").datepicker("setDate", "{{$store->contract_end_date->format('d/m/Y')}}");
    <?php }?>
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

        //create existing polygons from db
        @if($polyStrings)
            @foreach($polyStrings as $item => $string)
                var shape = new google.maps.Polygon({
                    paths: {!! $string !!},
                    customId : "{!! $item !!}"
                });
                shape.setMap(map);
                addClicks(shape);
                polygons.push(shape);
                FitBounds();
            @endforeach
            // polygons[polygons.length-1].setMap(map); 
        @endif

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

    $('#type_id').on('change', function(){
        category_id = $(this).val();
        if(category_id == 3){
            $('#service_div').show();
        }else{
            $('#service_div').hide();
            getSubcategory(category_id,null);
        }
           
    });

    $('#service_type').on('change', function(){
        category_id = $(this).val();
        if(category_id == 'service_type_2'){
            $('#location_div').show();
            $('#gender_preference_div').show();
        }else if(category_id == 'service_type_3') {
            $('#location_div').hide();
            $('#gender_preference_div').show();
        }else{            
            $('#location_div').hide();
            $('#gender_preference_div').hide();
        }
        getSubcategory(category_id,null);
    });

    $('#my_location').on('switchChange.bootstrapSwitch', function (event, state) {
        if($("#my_location").is(':checked')) {
            $('#mylocation_cost').show();
        } else {
            $('#mylocation_cost').hide();
        }
    });
        
    <?php 
    if(!empty($store)){?>
        getSubcategory(<?php echo $store->business_type_id;?>,<?php echo $store->business_type_category_id;?>);
    <?php } ?>
    function getSubcategory(catId,subId){
        $('.overlay').show();
        $.ajax({
            url: '{{ url('admin/subcategory') }}/'+catId+'/'+subId,
            success: function(resp) {
                $('#cat_id').html(resp);
                $('.overlay').hide();
            }
        });
    }      
</script>
@stop