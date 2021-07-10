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
                <div class="card-header">
                    <h3 class="card-title">&nbsp;</h3>
                    <!-- tools box -->
                    
                    <!-- /. tools -->
                </div>
                <div class="card-body pad">
                    <table class="table table-striped table-bordered dt-responsive nowrap" style="width:100%" id="page-content-id">
                        <thead>
                        <tr>
                             <th>Product Name</th>
                             <th>Customer Name</th>
                             <th>Email</th>
                             <th>Phone</th>
                             <th>Message</th>
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
            oTable = $('#page-content-id').DataTable({
                processing: true,
                serverSide: true,
                ordering: false,
                //autoWidth: false,
                ajax: {
                    url: "{!! url('admin/pages/preqlist') !!}",
                    type: 'post',
                    headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'},
                    data: function ( d ) {

                    }
                },
                columns: [
                    {data: 'productname', name:'productname'},
                    {data: 'name', name:'name'},
                    {data: 'email', name:'email'},
                    {data: 'phone', name:'phone'},
                    {data: 'message', name:'message'}, 
                    {data: 'actions', name: 'actions', searchable: false}
                ]
            });
        });
    </script>
@stop
