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
<div class="card card-primary">
    <div class="card-body">
        <div class="row">
            <div class="col-4 col-sm-2">
                <div class="nav flex-column nav-tabs h-100">
                    <a class="nav-link" id="vert-tabs-home-tab" href="{{ route('admin.generalclass.edit', ['generalclass' => $generalclass->id, 'type' => $type])}}" role="tab" aria-controls="vert-tabs-home" aria-selected="false">General</a>
                    <a class="nav-link active" id="vert-tabs-home-tab" href="" role="tab" aria-controls="vert-tabs-home" aria-selected="false">Sessions <span data-toggle="tooltip" title="This Class has {{ $sessionsCount }} sessions" class="badge badge-primary">{{ $sessionsCount }}</span></a>
                </div>
            </div>
            <div class="col-8 col-sm-10">
                <div class="tab-content">
                    <div class="tab-pane active">
                        <div class="tile">
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
                                                    @if($user->hasRole('super-admin') || $user->hasPermissionTo('onlineclass_create'))
                                                        <a href="{{ route('admin.sessions.create', ['generalclass' => $generalclass->id, 'type' => $type]) }}" class="btn btn-success btn-sm">New Session</a>
                                                    @endif
                                                </div>
                                            </div>
                                            <!-- /. tools -->
                                        </div>
                                        <div class="card-body pad">
                                            @if(isset($unAvailableSlots) && $unAvailableSlots)
                                                <div class="alert alert-danger">
                                                    <p>These time slots are not available</p>
                                                    <p>{{ implode(',', $unAvailableDate)}}</p>
                                                    <ul>
                                                        @foreach ($unAvailableSlots as $error)
                                                            <li>
                                                                {{ $error->slots }}
                                                            </li>
                                                        @endforeach
                                                    </ul>
                                                </div>
                                            @endif
                                            @if(isset($availableSlots) && $availableSlots)
                                                <div class="alert alert-success">
                                                    <p>These time slots are booked</p>
                                                    <p>{{ implode(',', $availableDate)}}</p>
                                                    <ul>
                                                        @foreach ($availableSlots as $error)
                                                            <li>
                                                                {{ $error->slots }}
                                                            </li>
                                                        @endforeach
                                                    </ul>
                                                </div>
                                            @endif
                                            <table class="table table-striped table-bordered dt-responsive nowrap" style="width:100%" id="sessions-table">
                                                <thead>
                                                    <tr>
                                                        <th  class="no-sort">SL.No</th>
                                                        <th>Session Date</th>
                                                        <th>Start Time</th>
                                                        <th  class="no-sort">End Time</th>
                                                        <th  class="no-sort">Capacity</th>
                                                        @if($type == "offline")
                                                            <th class="no-sort">Amenity Slots</th>
                                                        @endif
                                                        <th>Date/Time Added</th>
                                                        <th  class="no-sort">Actions</th>
                                                    </tr>
                                                </thead>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="ajaxModal" tabindex="-1" role="dialog" aria-hidden="true"></div>
@stop
@section('js')
<script>
    $(function () {
        oTable = $('#sessions-table').DataTable({
            processing: true,
            serverSide: true,
            ordering: true,
            columnDefs: [
                {
                    orderable: false,
                    targets: "no-sort"
                },
                { "width": "10px", "targets": 0 },
                { "width": "10px", "targets": 6 },
            ],
            order: [[ @if($type == 'offline') 6 @else 5 @endif, "desc" ]],
            //autoWidth: false,
            ajax: {
                url: '{!! url("admin/generalclass/$generalclass->id/sessions/dt") !!}',
                type: 'post',
                headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'},
                data: function ( d ) {
                    d.type = '{{ $type }}';
                }
            },
            columns: [
                {
                    render: function (data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1;
                    }
                },
                {
                    render: function (data, type, row, meta) {
                        status = '';
                        if(row.session_date[2] != 1){
                            status = ' <span data-toggle="tooltip" title="This session is disabled"><i class="fa fa-ban" style="color:red"></i></span>';
                        }
                        if(row.session_date[1] > 0){
                            return row.session_date[0] + ' <span data-toggle="tooltip" title="This Session has ' + row.session_date[1] + ' attendees" class="badge badge-primary">' + row.session_date[1] + '</span>' + status;
                        }
                        return row.session_date[0] + status;
                    }
                },
                {data: 'session_start_time', name: 'session_start_time'},
                {data: 'session_end_time', name: 'session_end_time'},
                {data: 'capacity', name: 'capacity'},
                @if($type == "offline")
                    {data: 'booking_data', name: 'booking_data'},
                @endif
                {data: 'created_at', name: 'created_at', searchable: false}, 
                {data: 'actions', name: 'actions', searchable: false}
            ]
        });
    });    
</script>
@endsection
