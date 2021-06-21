@extends('adminlte::page')

@section('title', 'Home Banner Management')

@section('content_header')
<div class="row mb-2">
    <div class="col-sm-6">
        <h1 class="m-0 text-dark">Home Banner Management</h1>
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
                    <div class="btn-group">
                        <a href="{{ route('admin.banners.create',['id'=>$banner_type]) }}" class="btn btn-success btn-sm">New Banner</a>
                    </div>
                </div>
                <!-- /. tools -->
            </div>
            <div class="card-body pad">
                <table class="table table-striped table-bordered dt-responsive nowrap" style="width:100%" id="users-table">
                    <thead>
                        <tr>
                            <th>SL.No</th>
                            <th>Image</th>
                            <th>External Url</th>
                            <th>Type</th>
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
                url: "{!! url('admin/banners/dt') !!}",
                type: 'post',
                headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'},
                data: function ( d ) { 
                    d.banner_type = {{$banner_type}};
                    
                }
            },
            columns: [
                {
                    render: function (data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1;
                    }
                },
                {
                    data: 'image',
                    name: 'image',
                    render: function( data, type, full, meta ) {
                    return "<img src=\"/storage/banner/images/" + data + "\" height=\"150\" alt='No Image'/>";
            }
                },
                {data: 'url', name: 'url', searchable: true},
                {
                    data: 'type',
                    name: 'type',
                    render: function(data, type, row, meta) {
                       
                        if(row.type ==1){
                            return 'Home Banner(Top)';
                        }
                        if(row.type ==2){
                            return 'Home Banner(Middle-1)';
                        }
                        if(row.type ==3){
                            return 'Home Banner(Middle-2)';
                        }
                        if(row.type ==4){
                            return 'Home Banner(Bottom)';
                        }
                        
                    }
                },
                {data: 'status', name: 'status'},
                {data: 'created_at', name: 'created_at', searchable: false},
                {data: 'actions', name: 'actions', searchable: false}
            ]
        });
    });
</script>    
@stop