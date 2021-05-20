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
                    <div class="card-tools">
                        <?php $user = Auth::user(); ?>
                        <div class="btn-group">
                            @if($user->hasRole('super-admin','admin') || $user->hasPermissionTo('offer_create','admin'))
                                <a href="{{ route('admin.offers.create') }}" class="btn btn-success btn-sm">Add New Offer</a>
                            @endif
                        </div>
                    </div>
                    <!-- /. tools -->
                </div>
                <div class="card-body pad">
                    <table class="table table-striped table-bordered dt-responsive nowrap" style="width:100%" id="offer">
                        <thead>
                        <tr>
                            <th>Title</th>
                            <th>Merchant Name</th>
                            <th>Redeem Text</th>
                            <th>Start Date</th>
                            <th>End Date</th>
                            <th>Status</th>
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
            oTable = $('#offer').DataTable({
                processing: true,
                serverSide: true,
                ordering: false,
                //autoWidth: false,
                ajax: {
                    url: "{!! url('admin/offers/dt') !!}",
                    type: 'post',
                    headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'},
                    data: function ( d ) {

                    }
                },
                columns: [
                    {data: 'title', name:'title'},
                    {data: 'brand_detail.name', name:'brandDetail.name'},
                    {data: 'redeem_text', name:'redeem_text'},
                    {data: 'start_date', name:'start_date'},
                    {data: 'end_date', name: 'end_date'},
                    {data: 'status', name: 'status'},
                    {data: 'actions', name: 'actions', searchable: false}
                ]
            });
        });
    </script>
@stop
