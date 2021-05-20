@extends('adminlte::page')

@section('title', $pageTitle)

@section('content_header')
<div class="row mb-2">
    <div class="col-sm-6">
        <h1 class="m-0 text-dark">{{$pageTitle}} - {{ $serviceOrder->order_id }}</h1>
    </div><!-- /.col -->
</div><!-- /.row -->
@stop

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="form-group">
            {{ Form::model($serviceOrder, array('route' => array('store.service-orders.update-status', $serviceOrder->id), 'method' => 'POST', 'class' => 'class-create')) }}
                <div class="card card-outline card-info">
                    <div class="overlay" style="display: none;">
                        <i class="fas fa-2x fa-sync-alt fa-spin"></i>
                    </div>
                    <div class="card-body pad">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    {{ Form::label('order status', "Order Status") }}
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-2">
                                <div class="form-group">
                                    @php
                                        $orderStatus = ["submitted", "assigned", "completed", "cancelled"];
                                        $statusIndex = array_search($serviceOrder->order_status, $orderStatus);
                                        $serviceType = $serviceOrder->service_type === "service_type_1" ? "ST1" : "ST2";
                                    @endphp
                                    <select name="order_status"  class="form-control" style="width:100%" id="order_status">
                                        @foreach ($orderStatus as $k => $status)
                                            @php
                                                $selected = "";
                                                $disabled = "";
                                                if($k <= $statusIndex){
                                                    $selected = "selected";
                                                    $disabled = "disabled";
                                                }
                                            @endphp
                                            <option value="{{ $status }}" {{ $selected }} {{ $disabled }}>{{ trans("api.order_status.{$serviceType}.{$status}") }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-1">
                                {{ Form::submit(isset($submitButtonText) ? $submitButtonText : 'Update', array('class' => 'btn btn-info float-left')) }}
                            </div>
                            @php
                                $statusTime =  $serviceOrder->serviceOrderStatusHistory->keyBy('status');
                                if($serviceOrder->order_status === "cancelled"){
                                    $orderStatus = $serviceOrder->serviceOrderStatusHistory->pluck('status')->toArray();
                                }
                                $statusIndex = array_search($serviceOrder->order_status, $orderStatus);
                            @endphp
                            <div class="col-md-12">
                                <ul class="timeline-custom" style="width: 100%">
                                    @foreach ($orderStatus as $k => $status)
                                        @php
                                            $completed = "";
                                            if($k <= $statusIndex)
                                                $completed = "complete";
                                        @endphp
                                        <li class="li {{ $completed }}">
                                            <div class="status">
                                                <h3>
                                                    {{ trans("api.order_status.{$serviceType}.{$status}") }}
                                                    <div class="timestamp">@if(isset($statusTime[$status])) {{ $statusTime[$status]['created_at']->format('jS M Y h:i A') }}@endif </div>
                                                </h3>
                                            </div>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            {{ Form::close() }}
        </div>
    </div>
</div>
<div class="invoice p-3 mb-3">
    <div class="row">
        <div class="col-12">
            <h4>
                <img src="{{asset('/images/myfitness_logo-lg.png')}}" style="max-width:30px;border-radius:25px;"/>
                My Family Fitness
            </h4>
            {{-- <small class="float-right">Date: @php date("Y/m/d") @endphp</small> --}}
        </div>
    </div>
    <div class="row invoice-info">
        <div class="col-sm-3 invoice-col">
            From
            <address>
                <strong>{{ $store->name }}</strong><br>
                Phone: {{ $store->mobile}}<br>
                Email: {{ $store->email }}
            </address>
        </div>
        <div class="col-sm-3 invoice-col">
            To
            <address>
                <strong>{{ $user->first_name }} {{ $user->last_name }}</strong><br>
                {!! $serviceOrder->delivery_address !!}<br>
                Phone: {{ $user->phone }}<br>
                Email: {{ $user->email }}
            </address>
        </div>
        @if($serviceOrder->service_type === "service_type_2")
            <div class="col-sm-3 invoice-col">
                Service Address
                @if($serviceOrder->location_type=='my_location')
                    <address>
                        <strong>{{ $user->first_name }} {{ $user->last_name }}</strong><br>
                        {!! $serviceOrder->delivery_address !!}<br>
                        Phone: {{ $user->phone }}<br>
                        Email: {{ $user->email }}
                    </address>
                @else
                    <address>
                        <strong>{{ $store->name }}</strong><br>
                        Phone: {{ $store->mobile}}<br>
                        Email: {{ $store->email }}
                    </address>
                @endif
            </div>
        @endif
        <div class="col-sm-3 invoice-col">
            <b>Order ID:</b>{{ $serviceOrder->order_id }}<br>
            <b>Order Date:</b>{{ $serviceOrder->created_at->format("d/m/y") }}<br>
            <br/>
            <div style="display: flex">
                <p style="margin-top: -3px;">Order:&nbsp;</p>
                @php
                    $unChecked = 5 - $serviceOrder->rating;
                    if($serviceOrder->rating):
                @endphp
                @foreach (range(1, $serviceOrder->rating) as $i)
                    <span class="fa fa-star rating-star-checked"></span>
                @endforeach
                @php
                    endif
                @endphp
                @if($unChecked != 0)
                    @foreach (range(1, $unChecked) as $i)
                        <span class="fa fa-star-o"></span>
                    @endforeach
                @endif
                <span>({{ (int)$serviceOrder->rating }})</span>
            </div>
            <div style="display: flex">
                <p style="margin-top: -3px; margin-bottom: 2rem;">Accuracy:&nbsp;</p>
                @php
                    $unChecked = 5 - $serviceOrder->accuracy;
                    if($serviceOrder->accuracy):
                @endphp
                @foreach (range(1, $serviceOrder->accuracy) as $i)
                    <span class="fa fa-star rating-star-checked"></span>
                @endforeach
                @php
                    endif
                @endphp
                @if($unChecked != 0)
                    @foreach (range(1, $unChecked) as $i)
                        <span class="fa fa-star-o"></span>
                    @endforeach
                @endif
                <span>({{ (int)$serviceOrder->accuracy }})</span>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12 table-responsive">
          <table class="table table-striped" id="product-table">
            <thead>
            <tr>
                {{-- <th>#</th> --}}
                <th style="display:none;">Order Item</th>
                <th>Product</th>
                <th>Qty</th>
                <th>Price</th>
                <th>Subtotal</th>
            </tr>
            </thead>
            <tbody>
                @foreach($products as $product)
                    <tr>
                        {{-- <td><input type="checkbox" value={{ $product->id }} id="product"/></td> --}}
                        <td style="display:none;">{{ $product->id }}</td>
                        <td>{{ $product->product_name }}</td>
                        <td>{{ $product->quantity }}</td>
                        <td>{{ round_my_number($product->product_price) }}</td>
                        <td>{{ round_my_number($product->total_amount) }}</td>
                    </tr>
                @endforeach
            </tbody>
          </table>
        </div>
        <!-- /.col -->
    </div>
    <div class="row">
        <!-- accepted payments column -->
        <div class="col-6">
            <p>Payment Method: {{ \Helper::getPaymentType($serviceOrder->payment_type) }}</p>
            <p>Payment Status: {!! \Helper::getPaymentStatus($serviceOrder->payment_status) !!}</p>
            <p>{{ $serviceOrder->notes }}</p>
            @if($serviceOrder->pick_up_slot_id)
                @if($serviceOrder->service_type === "service_type_1")
                    <p>Pick Up Date: {{ $serviceOrder->pick_up_date }}</p>
                    <p>Pick Up Time: {{ $serviceOrder->pick_up_slot }}</p>
                @else
                    <p>Service Date: {{ $serviceOrder->storeDaySlot->days }}</p>
                    <p>Service Time: {{ $serviceOrder->storeDaySlot->slots->slot_name }}</p>
                @endif
            @endif
            @if($serviceOrder->drop_off_slot_id && $serviceOrder->drop_off_slot)
                <p>Drop Off Time: {{ $serviceOrder->drop_off_slot }}</p>
            @endif
            @if($serviceOrder->service_type === "service_type_2")
            <p>Location Type :
                @if($serviceOrder->location_type=='my_location')
                    My location
                @else
                    In store
                @endif
            </p>
            <p>Gender Preference : 
                {{ ucwords(str_replace("_", " ", $serviceOrder->gender_preference === null ? 'any' : $serviceOrder->gender_preference)) }}</p>
            @endif
            <br>
        </div>
        <!-- /.col -->
        <div class="col-6">
            <p class="lead">Order Summary</p>
            <div class="table-responsive">
                <table class="table">
                    <tbody>
                        <tr>
                            <th style="width:50%">Items Total</th>
                            <td>{{ $serviceOrder->amount_exclusive_vat }}</td>
                        </tr>
                        <tr>
                            <th>Service Charge</th>
                            <td>{{ round_my_number($serviceOrder->service_charge) }}</td>
                        </tr>
                        <tr>
                            <th>Vat ({{config('settings.vat') }}%)</th>
                            <td>{{ round_my_number($serviceOrder->vat_amount) }}</td>
                        </tr>
                            @if($serviceOrder->location_type === "my_location")
                                <tr>
                                    <th>On My Location Charge</th>
                                    <td>{{ round_my_number($serviceOrder->on_my_location_charge) }}</td>
                                </tr>
                            @endif
                        <tr>
                            <th>Order Total</th>
                         <td>{{ round_my_number($serviceOrder->total_amount) }}</td>
                        </tr>
                    </tbody>
                </table>
          </div>
        </div>
        <!-- /.col -->
    </div>
    {{-- <div class="row no-print">
        <div class="col-12">
          <a href="invoice-print.html" target="_blank" class="btn btn-default"><i class="fas fa-print"></i> Print</a>
          <button type="button" class="btn btn-success float-right"><i class="far fa-credit-card"></i> Submit
            Payment
          </button>
          <button type="button" class="btn btn-primary float-right" style="margin-right: 5px;">
            <i class="fas fa-download"></i> Generate PDF
          </button> 
        </div>
    </div>--}}
</div>
@stop
