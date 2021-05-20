@extends('adminlte::page')

@section('title', 'Store Management')

@section('content_header')
<div class="row mb-2">
    <div class="col-sm-6">
        <h1 class="m-0 text-dark">Store Management</h1>
    </div><!-- /.col -->
</div><!-- /.row -->
@stop

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card card-outline card-info">
            <div class="card-header">
                <h3 class="card-title">&nbsp;</h3>
                <!-- tools box -->
                <div class="card-tools">
                    <?php $user = Auth::user(); ?>
                    <div class="btn-group">                       
                        @if($user->hasRole('super-admin','admin') || $user->hasPermissionTo('store_create','admin'))
                            <a href="{{ route('admin.stores.create') }}" class="btn btn-success btn-sm">New Store</a>
                            &nbsp;<a href="{{ route('admin.audits.activityLog','store') }}" class="btn btn-success btn-sm">View Audit Log</a>
                        @endif
                    </div>
                </div>
                <!-- /. tools -->
            </div>
            <div class="card-body pad">
                <div class="row">
                    <div class="form-group col-4">
                        <label>Business Type </label>
                        <select class="form-control select2" name="business_type_id" id="business_type_id" >
                        <option value="">Select Business Type</option>
                            @foreach ($businessType as $business)
                            <option value="{{$business->id}}">{{$business->name}}</option>
                            @endforeach
                        </select>                        
                    </div>
                </div>
                <table class="table table-striped table-bordered dt-responsive nowrap" style="width:100%" id="users-table">
                    <thead>
                        <tr>
                            <th class="no-sort">SL.No</th>
                            <th>Name</th>
                            <th>Vendor Name</th>
                            <th class="no-sort">Email</th>
                            <th class="no-sort">Mobile</th>
                            {{--<th></th>--}}
                            <th>Date/Time Added</th>
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
        oTable = $('#users-table').DataTable({
            processing: true,
            serverSide: true,
            ordering: true,
            columnDefs: [
                {
                    orderable: false,
                    targets: "no-sort"
                },
                { "width": "10px", "targets": 0 },
                { "width": "100px", "targets": 5 },
                { "width": "50px", "targets": 6 },
            ],
            order: [[ 5, "dsc" ]],
            //autoWidth: false,
            ajax: {
                url: "{!! url('admin/stores/dt') !!}",
                type: 'post',
                headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'},
                data: function ( d ) {
                    if( $('#business_type_id').val() != "") {
                        d.business_type_id = $('#business_type_id').val();
                    }
                }
            },
            columns: [
                {
                    render: function (data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1;
                    }
                },
                {data: 'name', name: 'name', searchable: true},
                {data: 'store_vendor.name', name: 'storeVendor.name', searchable: false},
                {data: 'email', name: 'email', searchable: true},
                {data: 'mobile', name: 'mobile', searchable: true},
//                {data: 'descrption', name: 'descrption', searchable:false},
                {data: 'created_at', name: 'created_at', searchable: true},
                {data: 'actions', name: 'actions', searchable: false}
            ]
        });
        $(document).on("change", "#business_type_id", function() {
            oTable.ajax.reload();
        });
    });
</script>    
@stop