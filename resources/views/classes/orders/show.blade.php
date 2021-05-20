@extends('adminlte::page')

@section('title', $pageTitle)

@section('content_header')
<div class="row mb-2">
    <div class="col-sm-6">
        <h1 class="m-0 text-dark">{{$pageTitle}} - {{ $order->order_id }}</h1>
    </div><!-- /.col -->
</div><!-- /.row -->
@stop

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="form-group">
            {{ Form::model($order, array('route' => array('admin.orders.update', $order->id), 'method' => 'POST', 'class' => 'class-create')) }}
            {{ method_field('PATCH') }}

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
                                        $orderStatus = ["submitted", "enrolled", "completed"];
                                        $statusIndex = array_search($order->order_status, $orderStatus);
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
                                            <option value="{{ $status }}" {{ $selected }} {{ $disabled }}>{{ trans("api.order_status.CLASS.{$status}") }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-1">
                                {{ Form::submit(isset($submitButtonText) ? $submitButtonText : 'Update', array('class' => 'btn btn-info float-left')) }}
                            </div>
                            @php
                                $statusTime =  $order->classOrderStatusHistory->keyBy('status');
                                if($order->order_status === "cancelled"){
                                    $orderStatus = ['submitted','cancelled'];   
                                }  
                                $statusIndex = array_search($order->order_status, $orderStatus);
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
                                                    {{ trans("api.order_status.CLASS.{$status}") }}
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
                My Family Fitness
            </h4>
            {{-- <small class="float-right">Date: @php date("Y/m/d") @endphp</small> --}}
        </div>
    </div>
    <div class="row invoice-info">
        <div class="col-sm-3 invoice-col">
            Class
            <address>
                {{ $order->class_name }}<br/>
                {{ $order->package_name }}<br/>
                Instructor: <strong>{{ $order->generalClass->instructor }}</strong><br>
            </address>
        </div>
        <div class="col-sm-3 invoice-col">
            Particepant
            <address>
                <strong>{{ $user->first_name }} {{ $user->last_name }}</strong><br>
                Phone : {{ $user->phone }}<br>
                Email : {{ $user->email }}
            </address>
        </div>
        @if($order->class_type === "offline")
            <div class="col-sm-3 invoice-col">
                Community : <strong>{{ $order->generalClass->community_name }}</strong><br>
                Amenity   : <strong>{{ $order->generalClass->amenity_name }}</strong><br>
            </div>
        @endif
        <div class="col-sm-3 invoice-col">
            <b>Order ID   : </b>{{ $order->order_id }}<br>
            <b>Order Date : </b>{{ $order->created_at->format("d/m/y") }}<br>
        </div>
    </div>
    <div class="row">
        <div class="col-12 table-responsive">
          <table class="table table-striped" id="product-table">
            <thead>
            <tr>
                <th>Session Date</th>
                <th>Start Time</th>
                <th>End Time</th>
            </tr>
            </thead>
            <tbody>
                @foreach($slots as $slot)
                    <tr>
                        <td>{{ \Carbon\Carbon::parse($slot->slot_date)->format('F d, Y') }}</td>
                        <td>{{ $slot->start_at }}</td>
                        <td>{{ $slot->end_at }}</td>
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
            <p>Payment Method: {{ \Helper::getPaymentType($order->payment_type) }}</p>
            <p>Payment Status: {!! \Helper::getPaymentStatus($order->payment_status) !!}</p>
            <p>{{ $order->notes }}</p>
            @if($order->pick_up_slot_id)
                @if($order->service_type === "service_type_1")
                    <p>Pick Up Date: {{ $order->pick_up_date }}</p>
                    <p>Pick Up Time: {{ $order->pick_up_slot }}</p>
                @else
                    <p>Service Date: {{ $order->storeDaySlot->days }}</p>
                    <p>Service Time: {{ $order->storeDaySlot->slots->name }}</p>
                @endif
            @endif
            @if($order->drop_off_slot_id && $order->drop_off_slot)
                <p>Drop Off Time: {{ $order->drop_off_slot }}</p>
            @endif
            @if($order->service_type === "service_type_2")
            <p>Location Type :
                @if($order->location_type=='my_location')
                    My location
                @else
                    In store
                @endif
            </p>
            <p>Gender Preference : 
                {{ ucwords(str_replace("_", " ", $order->gender_preference === null ? 'any' : $order->gender_preference)) }}</p>
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
                            <td>{{ $order->amount_exclusive_vat }}</td>
                        </tr>
                        <tr>
                            <th>Service Charge</th>
                            <td>{{ round_my_number($order->service_charge) }}</td>
                        </tr>
                        <tr>
                            <th>Vat ({{config('settings.vat') }}%)</th>
                            <td>{{ round_my_number($order->vat_amount) }}</td>
                        </tr>
                            @if($order->location_type === "my_location")
                                <tr>
                                    <th>On My Location Charge</th>
                                    <td>{{ round_my_number($order->on_my_location_charge) }}</td>
                                </tr>
                            @endif
                        <tr>
                            <th>Order Total</th>
                         <td>{{ round_my_number($order->total_amount) }}</td>
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
