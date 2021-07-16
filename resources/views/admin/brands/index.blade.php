@extends('adminlte::page')

@section('title', 'Brand Management')

@section('content_header')
<div class="row mb-2">
    <div class="col-sm-6">
        <h1 class="m-0 text-dark">Brand Management</h1>
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

                        @if($user->hasRole('super-admin','admin') || $user->hasPermissionTo('brand_create','admin'))
                        <a href="{{ route('admin.brands.create') }}" class="btn btn-success btn-sm">New Brand</a>
                        @endif
                    </div>
                </div>
                <!-- /. tools -->
            </div>
            <div class="card-body pad">
                <table class="table table-striped table-bordered dt-responsive nowrap" style="width:100%" id="brands-table">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Logo</th>
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
        oTable = $('#brands-table').DataTable({
           processing: true,
            serverSide: true,
            ordering: false,
            //autoWidth: false,
            ajax: {
                url: "{!! url('admin/brands/dt') !!}",
                type: 'post',
                headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'}
            },
            columns: [
                {data: 'name', name: 'name'},
                {
                    data: 'image',
                    name: 'image',
                    render: function( data, type, full, meta ) {
                    return "<img src=\"/uploads/brand/images/" + data + "\" height=\"50\" alt='No Image'/>";
            }
                },
                {data: 'actions', name: 'actions', searchable: false}
            ]
        });

    });
</script>
@stop
