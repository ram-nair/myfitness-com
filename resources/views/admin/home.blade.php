@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1 class="m-0 text-dark">Dashboard</h1>
        </div><!-- /.col -->
    </div><!-- /.row -->
@stop

@section('content')
    <div class="row input-daterange" id="global_dates">
        <div class="col-3">
            <label>Starts At</label>
            <input type="text" class="form-control" placeholder="Starts At" name="start_date" id="start_date" autocomplete="off">
        </div>
        <div class="col-3">
            <label>End At</label>
            <input type="text" class="form-control" placeholder="End At" name="end_date" id="end_date" autocomplete="off">
        </div>
    </div><br>

    <div class="row">
    {{--<div class="col-lg-6">
        <div class="card card-outline card-info">
            <div class="card-header border-0">
                <div class="d-flex justify-content-between">
                    <h3 class="card-title">Out Of Stock</h3>
                </div>
            </div>
            <div class="card-body">
                <div class="d-flex">
                    <p class="d-flex flex-column">
                        <span class="text-bold text-lg">{{$result['out_of_stock_product_tot']}}</span>
                        <span>Out of stock product</span>
                    </p>
                    <p class="ml-auto d-flex flex-column">

                            <label>Filter By Store</label>
                            <select name="store_id" id="store_filter" class="form-control select2">
                                <option value="">Select Store</option>
                                @foreach($result['stores'] as $key => $store)
                                    <option value="{{ $key }}">{{ $store }}</option>
                                @endforeach
                            </select>

                    </p>
                </div>
                <!-- /.d-flex -->

                <div class="position-relative mb-4">
                    <canvas id="visitors-chart" height="200"></canvas>
                </div>

            </div>
        </div>
        <!-- /.card -->
    </div>--}}

    <!-- /.col-md-6 -->
    <div class="col-lg-6">
        <div class="card card-outline card-info">
            <div class="card-header border-0">
                <div class="d-flex justify-content-between">
                    <h3 class="card-title">Users</h3>
                </div>
            </div>
            <div class="card-body">
                <div class="d-flex">
                    <p class="d-flex flex-column">
                        <span class="text-bold text-lg">{{$result['total_customer']}}</span>
                        <span>Registered User</span>
                    </p>
                    <p class="ml-auto d-flex flex-column text-right">
                        <span class="text-muted">Current Year</span>
                    </p>
                </div>
                <!-- /.d-flex -->

                <div class="position-relative mb-4">
                    <canvas id="registered_user" height="200"></canvas>
                </div>
            </div>
        </div>
        <!-- /.card -->
    </div>
    <!-- /.col-md-6 -->
        <div class="col-lg-6">
            <div class="card card-outline card-info">
                <div class="card-header border-0">
                    <div class="d-flex justify-content-between">
                        <h3 class="card-title">Last 7 Days Orders</h3>
                    </div>
                </div>
                <div class="card-body">
                    <div class="d-flex">
                        <p class="d-flex flex-column">
                            <span class="text-bold text-lg">{{$result['order_count']}}</span>
                            <span>Total Orders</span>
                        </p>
                    </div>
                    <!-- /.d-flex -->

                    <div class="position-relative mb-4">
                        <canvas id="last-seven-day-chart" height="200"></canvas>
                    </div>

                </div>
            </div>
            <!-- /.card -->
        </div>
</div>
    <div class="row">
        <div class="col-lg-6">
            <div class="card card-outline card-info">
                <div class="card-header border-0">
                    <div class="d-flex justify-content-between">
                        <h3 class="card-title">Accepted & Cancel Order</h3>
                    </div>
                </div>
                <div class="card-body">
                    <div class="d-flex">
                        <p class="ml-auto d-flex flex-column text-right">
                            <span class="text-muted">Current Year</span>
                        </p>
                    </div>
                    <!-- /.d-flex -->

                    <div class="position-relative mb-4">
                        <canvas id="accepted_canceled_order" height="200"></canvas>
                    </div>
                    <div class="d-flex flex-row justify-content-end">
                    <span class="mr-2">
                        <i class="fas fa-square text-primary"></i> Accepted Order
                    </span>

                        <span>
                        <i class="fas fa-square text-gray"></i> Canceled Order
                    </span>
                    </div>
                </div>
            </div>
            <!-- /.card -->
        </div>
        <!-- /.col-md-6 -->
        <div class="col-lg-6">
            <div class="card card-outline card-info">
                <div class="card-header border-0">
                    <div class="d-flex justify-content-between">
                        <h3 class="card-title">Last 7 Days Orders Revenue</h3>
                    </div>
                </div>
                <div class="card-body">
                    <div class="d-flex">
                        <p class="d-flex flex-column">
                            <span class="text-bold text-lg">{{$result['order_total']}}</span>
                            <span>Total Orders Revenue</span>
                        </p>
                    </div>
                    <!-- /.d-flex -->
                    <div class="position-relative mb-4">
                        <canvas id="order-revenue-chart" height="200"></canvas>
                    </div>

                </div>
            </div>
            <!-- /.card -->
        </div>
    </div>
    <div class="row">
        
        <!-- /.col-md-6 -->
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header border-0">
                    <h3 class="card-title">Top 10 Users</h3>
                    {{--<div class="card-tools">--}}
                    {{--<a href="#" class="btn btn-tool btn-sm">--}}
                    {{--<i class="fas fa-download"></i>--}}
                    {{--</a>--}}
                    {{--<a href="#" class="btn btn-tool btn-sm">--}}
                    {{--<i class="fas fa-bars"></i>--}}
                    {{--</a>--}}
                    {{--</div>--}}
                </div>
                <div class="card-body table-responsive p-0">
                    <table class="table table-striped table-valign-middle">
                        <thead>
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Number</th>
                            <th>Total Order</th>
                        </tr>
                        </thead>
                        <tbody>
                        @if(count($result['top_ten_users'])>0)
                            @foreach($result['top_ten_users'] as $user)
                                <tr>
                                    <td>
                                        {{$user->first_name.' '.$user->last_name}}
                                    </td>
                                    <td>{{$user->email}}</td>
                                    <td>{{$user->phone}}</td>
                                    <td>
                                        {{$user->order_count}}
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr><td colspan="4">Users are not found.</td></tr>
                        @endif

                        </tbody>
                    </table>
                </div>
            </div>
            <!-- /.card -->
        </div>
        <!-- /.col-md-6 -->
    </div>
    <div class="row">
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header border-0">
                    <h3 class="card-title">Top 5 Category</h3>
                </div>
                <div class="card-body table-responsive p-0">
                    <table class="table table-striped table-valign-middle">
                        <thead>
                        <tr>
                            <th>Name</th>
                            <th>Description</th>
                        </tr>
                        </thead>
                        <tbody>
                       {{-- @if(count($result['top_business_category'])>0)
                            @foreach($result['top_business_category'] as $category)
                                <tr>
                                    <td>
                                        {{$category->name}}
                                    </td>
                                    <td>{!! $category->description !!}</td>
                                </tr>
                            @endforeach
                        @else
                            <tr><td colspan="2">Business Category are not found.</td></tr>
                        @endif
--}}
                        </tbody>
                    </table>
                </div>
            </div>
            <!-- /.card -->
        </div>
    </div>
@stop
@section('css')
<link rel="stylesheet" href="http://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
@stop
@section('js')
    <link href="{{ asset('css/bootstrap-datepicker.css')}}" id="theme" rel="stylesheet">
    <script src="{{ asset('js/bootstrap-datepicker.min.js')}}"></script>

    <script>
        $("#global_dates").datepicker({
            toggleActive: !0
        });
   var customer_chart_label =  <?php echo json_encode(Illuminate\Support\Arr::pluck($result['customer_graph_data'],'label')); ?>;
   var customer_chart_value =  <?php echo json_encode(Illuminate\Support\Arr::pluck($result['customer_graph_data'],'y')); ?>;

   var seven_day_order_chart_label =  <?php echo json_encode(Illuminate\Support\Arr::pluck($result['order_graph_data'],'label')); ?>;
   var seven_day_order_chart_value =  <?php echo json_encode(Illuminate\Support\Arr::pluck($result['order_graph_data'],'y')); ?>;
   var seven_day_order_revenue_chart_value =  <?php echo json_encode(Illuminate\Support\Arr::pluck($result['order_graph_data'],'total_revenue')); ?>;

   var accepted_canceled_chart_label =  <?php echo json_encode(Illuminate\Support\Arr::pluck($result['accepted_canceled_order_data'],'label')); ?>;
   var accepted_chart_value =  <?php echo json_encode(Illuminate\Support\Arr::pluck($result['accepted_canceled_order_data'],'accepted_order_count')); ?>;
   var canceled_chart_value =  <?php echo json_encode(Illuminate\Support\Arr::pluck($result['accepted_canceled_order_data'],'rejected_order_count')); ?>;




        $(function () {
        'use strict'

        var ticksStyle = {
            fontColor: '#495057',
            fontStyle: 'bold'
        }

        var mode = 'index'
        var intersect = true

        var $registered_user = $('#registered_user')
        var registered_user = new Chart($registered_user, {
                data: {
                    labels: customer_chart_label,
                    datasets: [{
                        type: 'line',
                        data: customer_chart_value,
                        backgroundColor: 'transparent',
                        borderColor: '#007bff',
                        pointBorderColor: '#007bff',
                        pointBackgroundColor: '#007bff',
                        fill: false
                        // pointHoverBackgroundColor: '#007bff',
                        // pointHoverBorderColor    : '#007bff'
                    }]
                },
                options: {
                    maintainAspectRatio: false,
                    tooltips: {
                        mode: mode,
                        intersect: intersect
                    },
                    hover: {
                        mode: mode,
                        intersect: intersect
                    },
                    legend: {
                        display: false
                    },
                    scales: {
                        yAxes: [{
                            // display: false,
                            gridLines: {
                                display: true,
                                lineWidth: '4px',
                                color: 'rgba(0, 0, 0, .2)',
                                zeroLineColor: 'transparent'
                            },
                            ticks: $.extend({
                                beginAtZero: true,
                                suggestedMax: 200
                            }, ticksStyle)
                        }],
                        xAxes: [{
                            display: true,
                            gridLines: {
                                display: false
                            },
                            ticks: ticksStyle
                        }]
                    }
                }
            })


        var $last_seven_day_chart = $('#last-seven-day-chart')
        var last_seven_day_chart = new Chart($last_seven_day_chart, {
            data: {
                labels: seven_day_order_chart_label,
                datasets: [{
                        type: 'line',
                        data: seven_day_order_chart_value,
                        backgroundColor: 'transparent',
                        borderColor: '#007bff',
                        pointBorderColor: '#007bff',
                        pointBackgroundColor: '#007bff',
                        fill: false
                                // pointHoverBackgroundColor: '#007bff',
                                // pointHoverBorderColor    : '#007bff'
                    }]
            },
            options: {
                maintainAspectRatio: false,
                tooltips: {
                    mode: mode,
                    intersect: intersect
                },
                hover: {
                    mode: mode,
                    intersect: intersect
                },
                legend: {
                    display: false
                },
                scales: {
                    yAxes: [{
                            // display: false,
                            gridLines: {
                                display: true,
                                lineWidth: '4px',
                                color: 'rgba(0, 0, 0, .2)',
                                zeroLineColor: 'transparent'
                            },
                            ticks: $.extend({
                                beginAtZero: true,
                                suggestedMax: 200
                            }, ticksStyle)
                        }],
                    xAxes: [{
                            display: true,
                            gridLines: {
                                display: false
                            },
                            ticks: ticksStyle
                        }]
                }
            }
        })

        var $last_seven_day_order_revenue_chart = $('#order-revenue-chart')
        var last_seven_day_order_revenue_chart = new Chart($last_seven_day_order_revenue_chart, {
                data: {
                    labels: seven_day_order_chart_label,
                    datasets: [{
                        type: 'line',
                        data: seven_day_order_revenue_chart_value,
                        backgroundColor: 'transparent',
                        borderColor: '#007bff',
                        pointBorderColor: '#007bff',
                        pointBackgroundColor: '#007bff',
                        fill: false
                        // pointHoverBackgroundColor: '#007bff',
                        // pointHoverBorderColor    : '#007bff'
                    }]
                },
                options: {
                    maintainAspectRatio: false,
                    tooltips: {
                        mode: mode,
                        intersect: intersect
                    },
                    hover: {
                        mode: mode,
                        intersect: intersect
                    },
                    legend: {
                        display: false
                    },
                    scales: {
                        yAxes: [{
                            // display: false,
                            gridLines: {
                                display: true,
                                lineWidth: '4px',
                                color: 'rgba(0, 0, 0, .2)',
                                zeroLineColor: 'transparent'
                            },
                            ticks: $.extend({
                                beginAtZero: true,
                                suggestedMax: 200
                            }, ticksStyle)
                        }],
                        xAxes: [{
                            display: true,
                            gridLines: {
                                display: false
                            },
                            ticks: ticksStyle
                        }]
                    }
                }
            })

        var $accepted_cancel = $('#accepted_canceled_order')
        var accepted_cancel = new Chart($accepted_cancel, {
                data: {
                    labels: accepted_canceled_chart_label,
                    datasets: [{
                        type: 'line',
                        data: accepted_chart_value,
                        backgroundColor: 'transparent',
                        borderColor: '#007bff',
                        pointBorderColor: '#007bff',
                        pointBackgroundColor: '#007bff',
                        fill: false
                        // pointHoverBackgroundColor: '#007bff',
                        // pointHoverBorderColor    : '#007bff'
                    },
                        {
                            type: 'line',
                            data: canceled_chart_value,
                            backgroundColor: 'tansparent',
                            borderColor: '#ced4da',
                            pointBorderColor: '#ced4da',
                            pointBackgroundColor: '#ced4da',
                            fill: false
                            // pointHoverBackgroundColor: '#ced4da',
                            // pointHoverBorderColor    : '#ced4da'
                        }]
                },
                options: {
                    maintainAspectRatio: false,
                    tooltips: {
                        mode: mode,
                        intersect: intersect
                    },
                    hover: {
                        mode: mode,
                        intersect: intersect
                    },
                    legend: {
                        display: false
                    },
                    scales: {
                        yAxes: [{
                            // display: false,
                            gridLines: {
                                display: true,
                                lineWidth: '4px',
                                color: 'rgba(0, 0, 0, .2)',
                                zeroLineColor: 'transparent'
                            },
                            ticks: $.extend({
                                beginAtZero: true,
                                suggestedMax: 200
                            }, ticksStyle)
                        }],
                        xAxes: [{
                            display: true,
                            gridLines: {
                                display: false
                            },
                            ticks: ticksStyle
                        }]
                    }
                }
            })


    })
</script>
@stop