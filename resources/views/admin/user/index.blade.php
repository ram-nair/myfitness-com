@extends('adminlte::page')

@section('title', 'Customers')

@section('content_header')
<div class="row mb-2">
    <div class="col-sm-6">
        <h1 class="m-0 text-dark">Customers</h1>
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
                        @if($user->hasRole('super-admin','admin') || $user->hasPermissionTo('user_create','admin'))
                        <a href="{{ route('admin.users.create') }}" class="btn btn-success btn-sm">Add New Customer</a>
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
                            <th>Email</th>
                            <th>Phone</th>
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
                url: "{!! url('admin/users/dt') !!}",
                type: 'post',
                headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'}
            },
            columns: [
                {
                    render: function (data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1;
                    }
                },
                {
                    render: function (data, type, row, meta) {
                        if(row.last_name){
                            return row.first_name + ' '+row.last_name;
                        }else{
                            return row.first_name ;
                        }
                        
                    }
                },
                {data: 'email', name: 'email'},
                {data: 'phone', name: 'phone'},
                {data: 'created_at', name: 'created_at', searchable: false},
               
                {data: 'actions', name: 'actions', searchable: false}
            ]
        });

    });
</script>    
@stop