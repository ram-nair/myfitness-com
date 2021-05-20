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
                            <th class="no-sort">SL.No</th>
                            <th class="no-sort">Order #</th>
                            <th>Customer</th>
                            <th>Class</th>
                            <th>Class Type</th>
                            <th class="no-sort">Package</th>
                            <th class="no-sort">Total Amount</th>
                            <th class="no-sort">Payment Type</th>
                            <th class="no-sort">Payment Status</th>
                            <th>Date/Time Ordered</th>
                            <th class="no-sort">Actions</th>
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
            ordering: true,
            columnDefs: [
                {
                    orderable: false,
                    targets: "no-sort"
                },
                { "width": "10px", "targets": 0 },
            ],
             order: [[ 9, "desc" ]],

            //autoWidth: false,
            ajax: {
                url: "{!! url('admin/orders/dt') !!}",
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
                {data: "user.first_name", name:"user.first_name"},
                {data: "class_name", name:"class_name"},
                {data: "class_type", name:"class_type"},
                {data: "package_name", name:"package_name"},
                {data: 'total_amount', name: 'total_amount'},
                {data: 'payment_type', name: 'payment_type'},
                {data: 'payment_status', name: 'payment_status'},
                {data: 'created_at', name: 'created_at', searchable: false},
                {data: 'actions', name: 'actions', searchable: false}
            ]
        });
    });

</script>
@stop
