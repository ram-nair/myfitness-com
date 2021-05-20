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

<div class="row">
    <div class="col-md-12">
        <div class="card card-outline card-info">
            <div class="overlay" style="display: none;">
                <i class="fas fa-2x fa-sync-alt fa-spin"></i>
            </div>
            <div class="card-header">
                <h3 class="card-title">&nbsp;</h3>
                <!-- tools box -->
                <div class="card-tools">
                    <?php $user = Auth::user(); ?>
                    <div class="btn-group">
                        @if($user->hasRole('super-admin') || $user->hasPermissionTo('package_create'))
                            <a href="{{ route('admin.packages.create') }}" class="btn btn-success btn-sm">New Package</a>
                        @endif
                    </div>
                </div>
                <!-- /. tools -->
            </div>
            <div class="card-body pad">
                {{-- <div class="row">
                    <div class="form-group col-4">
                        <label>Filter By category</label>
                        <select name="category_id" id="categories" class="form-control select2">
                            <option value="">Select Category</option>
                            @foreach($categories as $key => $categoryName)
                            <option value="{{ $key }}">{{ $categoryName }}</option>
                            @endforeach
                        </select>
                    </div>
                </div> --}}
                <table class="table table-striped table-bordered dt-responsive nowrap" style="width:100%" id="packages-table">
                    <thead>
                        <tr>
                            <th class="no-sort">SL.No</th>
                            <th>Name</th>
                            <th class="no-sort">Price</th>
                            <th class="no-sort">No Of Sessions</th>
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
    oTable = $('#packages-table').DataTable({
        processing: true,
        serverSide: true,
        ordering: true,
        columnDefs: [
            {
                orderable: false,
                targets: "no-sort"
            },
            { "width": "10px", "targets": 0 },
            { "width": "100px", "targets": 4 },
            { "width": "50px", "targets": 5 },
        ],
        order: [[ 4, "desc" ]],
        ajax: {
            url: "{!! url('admin/packages/dt') !!}",
            type: 'post',
            headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'},
        },
        columns: [
            {
                render: function (data, type, row, meta) {
                    return meta.row + meta.settings._iDisplayStart + 1;
                }
            },
            {data: 'name', name: 'name'},
            {data: 'price', name: 'price', searchable: false},
            {data: 'no_of_sessions', name: 'no_of_sessions', searchable: false},
            {data: 'created_at', name: 'created_at', searchable: false},
            {data: 'actions', name: 'actions', searchable: false}
        ]
    });

});    
</script>
@stop
