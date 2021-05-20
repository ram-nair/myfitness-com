@extends('adminlte::page')

@section('title', 'Service Banner Management')

@section('content_header')
<div class="row mb-2">
    <div class="col-sm-6">
        <h1 class="m-0 text-dark">Service Banner Management</h1>
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
                    <?php $user = Auth::guard('admin')->user(); ?>
                    <div class="btn-group">
                        @if($user->hasRole('super-admin','admin') || $user->hasAnyPermission(['servicestype1banner_create','servicestype2banner_create']))
                            <a href="{{ route('admin.service-banner.create',['id'=>$service_type]) }}" class="btn btn-success btn-sm">New Banner</a>
                        @endif
                    </div>
                </div>
                <!-- /. tools -->
            </div>
            <div class="card-body pad">
                <table class="table table-striped table-bordered dt-responsive nowrap" style="width:100%" id="users-table">
                    <thead>
                        <tr>
                            <th>SL.No</th>
                            <th>Name</th>
                            <th>Url</th>
                            <th>Status</th>
                            <th>Date/Time Added</th>
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
        oTable = $('#users-table').DataTable({
            processing: true,
            serverSide: true,
            ordering: false,
            //autoWidth: false,
            ajax: {
                url: "{!! url('admin/service-banner/dt') !!}",
                type: 'post',
                headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'},
                data: function ( d ) {
                    d.service_type = {{$service_type}};
                    /*if( $('#sub_categories').val() != undefined) {
                        d.sub_cat_id = $('#sub_categories').val();
                    }
                    if( $('#categories').val() != undefined) {
                        d.cat_id = $('#categories').val();
                    }*/
                }
            },
            columns: [
                {
                    render: function (data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1;
                    }
                },
                {data: 'name', name: 'name'},
                {data: 'url', name: 'url'},
                
                { 
                    "data": "status",
                    "render": function(data){
                        if(data==1) {
                            return 'Active';
                        }else{
                            return 'Disabled';  
                        }
                       
                    }
                },
                
                {data: 'created_at', name: 'created_at', searchable: false},
                {data: 'actions', name: 'actions', searchable: false}
            ]
        });

    });
</script>    
@stop