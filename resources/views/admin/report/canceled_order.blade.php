@extends('adminlte::page')

@section('title', $pageTitle)

@section('content_header')
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1 class="m-0 text-dark">{{$pageTitle}}</h1>
        </div><!-- /.col -->
    </div><!-- /.row -->
@stop
@section('content')
    @if(isset($errors) && $errors->any())
        <div class="alert alert-danger">
            @foreach ($errors->all() as $error)
                {{ $error }}
            @endforeach
        </div>
    @endif
    @if((session())->has('failures'))
        <table class="table table-danger">
            <tr>
                <th>Row</th>
                <th>Product</th>
                <th>Attribute</th>
                <th>Errors</th>
                <th>Value</th>
            </tr>
            @foreach (session()->get('failures') as $validation)
                <tr>
                    <td>{{ $validation->row()   }}</td>
                    <td>{{ $validation->values()['product_name'] }}</td>
                    <td>{{ $validation->attribute() }}</td>
                    <td>
                        <ul>
                            @foreach ($validation->errors() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </td>
                    <td>{{ $validation->values()['product_name'] }}</td>
                </tr>
            @endforeach
        </table>
    @endif
    @if(session()->has('success') && session()->has('success') > 0)
        <div class="alert alert-success alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <h5><i class="icon fas fa-check"></i>Import Success</h5>
            {{ session()->get('success') }} products imported
        </div>
    @endif
    <div class="row">
        <div class="col-md-12">
            <div class="card card-outline card-info">
                {{--<div class="overlay" style="display: none;">--}}
                {{--<i class="fas fa-2x fa-sync-alt fa-spin"></i>--}}
                {{--</div>--}}
                {{ Form::open(array('url' => 'admin/download-canceled-order-report', 'enctype' => 'multipart/form-data')) }}

                <div class="card-header">
                    <h3 class="card-title">&nbsp;</h3>
                    <!-- tools box -->
                    <?php $user = Auth::user(); ?>
                    @if(Helper::get_guard() == "admin")
                        <div class="card-tools">
                            <div class="btn-group">
                                <button type="submit" class="btn btn-success btn-sm"> <i class="fa fa-download" aria-hidden="true"></i> Download</button>
                            </div>
                        </div>
                @endif
                <!-- /. tools -->
                </div>
                <div class="card-body pad">
                    @if($guard_name == 'admin')
                        <div class="row">
                            <div class="form-group col-3">
                                <label>Payment Type</label>
                                <select name="payment_type" id="payment_type_filter" class="form-control select2">
                                    <option value="">Select Payment Type</option>
                                    <option value="credit_card">Credit Card</option>
                                    <option value="card_reader">Card Reader</option>
                                    <option value="online_pay">Online Pay</option>
                                    <option value="cash_on_delivery">Cash On Delivery</option>

                                </select>
                            </div>
                            <div class="form-group col-3">
                                <label>Order Status</label>
                                <select name="order_type" id="order_type_filter" class="form-control select2">
                                    <option value="">Select Order Status</option>
                                        <option value="submitted">Submitted</option>
                                        <option value="assigned">Assigned</option>
                                        <option value="out_for_delivery">Out For Delivery</option>
                                        <option value="delivered">Delivered</option>
                                        <option value="cancelled">Cancelled</option>
                                </select>
                            </div>
                        </div>
                    @endif
                    <table class="table table-striped table-bordered dt-responsive nowrap" style="width:100%" id="canceled-order-table">
                        <thead>
                        <tr>
                            <th>Order Time</th>
                            <th>Customer Name</th>
                            <th>Customer Number</th>
                            <th>Customer Email</th>
                            <th>Order Id</th>
                            <th>Total Amount</th>
                            <th>Payment Type</th>
                            <th>Payment Status</th>
                            <th>Order Status</th>
                            <th>Order Number</th>
                        </tr>
                        </thead>
                    </table>
                </div>
                {{ Form::close() }}
            </div>
        </div>
    </div>

@stop
@section('js')
    <?php
    $guardname = Helper::get_guard();
    $url = url($guardname.'/report-canceled-order/dt');
    ?>
    <script type='text/javascript'>
        $(function () {
            var oTable = $('#canceled-order-table').DataTable({
                processing: true,
                serverSide: true,
                ordering: false,
                //autoWidth: false,
                ajax: {
                    url: "{!! $url !!}",
                    type: 'post',
                    headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'},
                    data: function ( d ) {
                        if( $('#payment_type_filter').val() != undefined) {
                            d.payment_type = $('#payment_type_filter').val();
                        }
                        if( $('#order_type_filter').val() != undefined) {
                            d.order_status = $('#order_type_filter').val();
                        }
                    }
                },
                columns: [
                    {data: 'created_at', name: 'created_at'},
                    {data: 'name', name: 'name'},
                    {data: 'user.phone', name: 'user.phone'},
                    {data: 'user.email', name: 'user.email'},
                    {data: 'order_id', name: 'order_id'},
                    {data: 'total_amount', name: 'total_amount'},
                    {data: 'payment_type', name: 'payment_type'},
                    {data: 'payment_status', name: 'payment_status'},
                    {data: 'order_status', name: 'order_status'},
                    {data: 'id', name: 'id'},
                ]
            });
            $(document).on("change", "#payment_type_filter, #order_type_filter", function() {
                oTable.ajax.reload();
            });
        });
    </script>
@stop
