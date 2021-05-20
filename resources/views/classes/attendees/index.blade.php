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
                <div class="card-tools">
                    <?php $user = Auth::user(); ?>
                    <div class="btn-group">
                        @if($user->hasRole('super-admin') || $user->hasAnyPermission(["{$type}class_create"]))
                            <a href="{{ route('admin.classes.attendees.export', ['session' => $session->id]) }}" class="btn btn-success btn-sm">Download</a>
                        @endif
                    </div>
                </div>
            </div>
            <div class="card-body pad">
                <table class="table table-striped table-bordered dt-responsive nowrap" style="width:100%" id="attendees-table">
                    <thead>
                        <tr>
                            <th>SL.No</th>
                            <th>Participant Name</th>
                            <th>Participant Email</th>
                            <th>Participant Phone</th>
                            <th>Enrolled At</th>
                            <th>Attendance</th>
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
    oTable = $('#attendees-table').DataTable({
        processing: true,
        serverSide: true,
        ordering: false,
        //autoWidth: false,
        ajax: {
            url: '{!! url("admin/sessions/$session->id/attendees/dt") !!}',
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
            {data: 'email', name: 'email'},
            {data: 'phone', name: 'phone'},
            {data: 'enrolled_at', name: 'enrolled_at'},
            {
                render: function (data, type, row, meta) {
                    if(row.attendance === 'absent'){
                        return "<span class='badge badge-danger'>Absent</span>";
                    }
                    return "<span class='badge badge-success'>Present</span>";
                }
            },
            {data: 'created_at', name: 'created_at', searchable: false},
            {data: 'actions', name: 'actions', searchable: false}
        ]
    });

});    
</script>
@stop
