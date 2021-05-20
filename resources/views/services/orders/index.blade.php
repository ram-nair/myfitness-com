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
<div class="row">
    <div class="col-md-12">
        <div class="card card-outline card-info">
            <div class="overlay" style="display: none;">
                <i class="fas fa-2x fa-sync-alt fa-spin"></i>
            </div>
            <div class="card-body pad">
                {{-- <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Filter by Order Type</label>
                            <select name="order_type" id="order-type" class="form-control">
                                <option value="">Select Category</option>
                                <option value="scheduled">Scheduled Order</option>
                                <option value="quick">Quick Order</option>
                            </select>
                        </div>
                    </div>
                </div> --}}
                <table class="table table-striped table-bordered dt-responsive nowrap" style="width:100%" id="orders-table">
                    <thead>
                        <tr>
                            <th>SL.No</th>
                            <th>Order #</th>
                            <th>Customer</th>
                            <th>Total Amount</th>
                            <th>Payment Type</th>
                            <th>Payment Status</th>
                            <th>Order Status</th>
                            <th>Date/Time Ordered</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>
@stop
@section('js')
<script type='text/javascript'>
    $(function () {
        oTable = $('#orders-table').DataTable({
            processing: true,
            serverSide: true,
            ordering: false,
            //autoWidth: false,
            ajax: {
                url: "{!! url('store/service-orders/dt') !!}",
                type: 'post',
                headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'},
                data: function ( d ) {
                    if( $('#order-type').val() != undefined) {
                        d.order_type = $('#order-type').val();
                    }
                }
            },
            columns: [
                {
                    render: function (data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1;
                    }
                },
                {data: 'order_id', name:'order_id'},
                { 
                    "data": "user",
                    "render": function(data){
                        if(data) {
                            return data.email;
                        }
                    }
                },
              
                {data: 'total_amount', name: 'total_amount'},
                {data: 'payment_type', name: 'payment_type'},
                {data: 'payment_status', name: 'payment_status'},
                {data: 'order_status', name: 'order_status'},
                {data: 'created_at', name: 'created_at', searchable: false},
                {data: 'actions', name: 'actions', searchable: false}
            ]
        });
    });

</script>
@stop
