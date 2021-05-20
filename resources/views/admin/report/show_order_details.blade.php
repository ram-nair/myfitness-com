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
                {{ Form::model($order, array('route' => array('store.orders.update-status', $order->id), 'method' => 'POST', 'class' => 'class-create')) }}
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
                                        $orderStatus = ['submitted','assigned','out_for_delivery','delivered','cancelled'];
                                        $statusIndex = array_search($order->order_status, $orderStatus);
                                    @endphp
                                </div>
                            </div>

                            @php
                                $statusTime =  $order->orderStatusHistory->keyBy('status');
                                if($order->order_status === "cancelled"){
                                $orderStatus = $order->orderStatusHistory->pluck('status')->toArray();
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
                                                    {{ trans("api.order_status.ECOM.{$status}") }}
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
                {{-- <small class="float-right">Date: {{date("Y/m/d")}} </small> --}}
            </div>
        </div>
        <div class="row invoice-info">
            <div class="col-sm-4 invoice-col">
                From
                <address>
                    <strong>{{ $store->name }}</strong><br>
                    Phone: {{ $store->mobile}}<br>
                    Email: {{ $store->email }}
                </address>
            </div>
            <div class="col-sm-4 invoice-col">
                To
                <address>
                    <strong>{{ $user->first_name }} {{ $user->last_name ?? "" }}</strong><br>
                    {!! $order->delivery_address !!}<br>
                    Phone: {{ $user->phone }}<br>
                    Email: {{ $user->email }}
                </address>
            </div>
            <div class="col-sm-4 invoice-col">
                <b>Order ID:</b>{{ $order->order_id }}<br>
                <b>Order Date:</b>{{ $order->created_at->format("d/m/y") }}<br>
                {{-- <b>Payment Due:</b>{{ $order->payment_date }}<br> --}}
                <br/>
                <div style="display: flex">
                    <p style="margin-top: -3px;">Order:&nbsp;</p>
                    @php
                        $unChecked = 5 - $order->rating;
                        if($order->rating):
                    @endphp
                    @foreach (range(1, $order->rating) as $i)
                        <span class="fa fa-star rating-star-checked"></span>
                    @endforeach
                    @php
                        endif
                    @endphp
                    @if($unChecked != 0)
                        @foreach (range(1, $unChecked) as $i)
                            <span class="fa fa-star-o" aria-hidden="true"></span>
                        @endforeach
                    @endif
                    <span>({{ (int)$order->rating }})</span>
                </div>
                <div style="display: flex">
                    <p style="margin-top: -3px; margin-bottom: 2rem;">Accuracy:&nbsp;</p>
                    @php
                        $unChecked = 5 - $order->accuracy;
                        if($order->accuracy):
                    @endphp
                    @foreach (range(1, $order->accuracy) as $i)
                        <span class="fa fa-star rating-star-checked"></span>
                    @endforeach
                    @php
                        endif
                    @endphp
                    @if($unChecked != 0)
                        @foreach (range(1, $unChecked) as $i)
                            <span class="fa fa-star-o" aria-hidden="true"></span>
                        @endforeach
                    @endif
                    <span>({{ (int)$order->accuracy }})</span>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12 table-responsive">
                <table class="table table-striped" id="product-table">
                    <thead>
                    <tr>
                        <th style="display:none;">Order Item</th>
                        <th>Product</th>
                        <th>Qty</th>
                        <th>Price</th>
                        <th>Subtotal</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($stockProducts as $product)
                        <tr>
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
        <?php
        if(!$outOfStockProducts->isEmpty()){ ?>
        <p class="bg-red"><span class="ml-2">Out Of Stock Items</span></p>
        <div class="row">
            <div class="col-12 table-responsive">
                <table class="table table-striped" id="product-table">
                    <thead>
                    <tr>
                        <th style="display:none;">Order Item</th>
                        <th>Product</th>
                        <th>Qty</th>
                        <th>Price</th>
                        <th>Subtotal</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($outOfStockProducts as $product)
                        <tr>
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
        <?php } ?>
       

        <div class="row">
            <!-- accepted payments column -->
            <div class="col-6">
                <p>Payment Method: {{ \Helper::getPaymentType($order->payment_type) }}</p>
                <p>Payment Status: {!! \Helper::getPaymentStatus($order->payment_status) !!}</p>
                <p>{{ $order->notes }}</p>
                @if($order->slot_id)
                    <p>{{ $order->scheduled_notes }}</p>
                    <p>Scheduled Date: {{ $order->storeDaySlot->days }}</p>
                    <p>Scheduled Time: {{ $order->storeDaySlot->slots->slot_name }}</p>
                @endif
            </div>
            <!-- /.col -->
            <div class="col-4">
                <p class="lead">Order Summary</p>
                <div class="table-responsive">
                    <table class="table">
                        <tbody>
                        <tr>
                            <th style="width:50%">Items Total</th>
                            <td>{{ round_my_number($order->amount_exclusive_vat) }}</td>
                        </tr>
                        <tr>
                            <th>Service Charge</th>
                            <td>{{ round_my_number($order->service_charge) }}</td>
                        </tr>
                        <tr>
                            <th>Vat ({{config('settings.vat') }}%)</th>
                            <td>{{ round_my_number($order->vat_amount) }}</td>
                        </tr>
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
        </div> --}}
    </div>
@stop
@section('js')
    <script>
        $('.not-available-btn').prop('disabled', true);
        $("#product-table #product").click(function () {
            if ($("#product-table #product").is(':checked')) {
                $('.not-available-btn').prop('disabled', false);
            } else {
                $('.not-available-btn').prop('disabled', true);
            }
        });
        $("#not-available").click(function () {
            var orderItemId = new Array();
            $("#product-table input[type=checkbox]:checked").each(function () {
                var row = $(this).closest("tr")[0];
                orderItemId.push(row.cells[1].innerHTML);
            });
            $('#order-item-ids').val(orderItemId)
        });
    </script>
@stop