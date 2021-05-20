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
        <ul>
            @foreach ($errors->all() as $error)
                <li>
                    {{ $error }}
                </li>
            @endforeach
        </ul>
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
                        @if($user->hasRole('super-admin') || $user->hasAnyPermission(["{$type}class_create"]))
                            <a href="{{ route('admin.generalclass.create', ['type' => $type]) }}" class="btn btn-success btn-sm">New Class</a>
                        @endif
                    </div>
                </div>
                <!-- /. tools -->
            </div>
            <div class="card-body pad">
            <div class="row">
            <div class="form-group col-4">
                    <label>Filter By Category</label>
                    <select name="category_id" id="categories" class="form-control select2">
                        <option value="">Select Category</option>
                        @foreach($categories as $key => $categoryName)
                            <option value="{{ $key }}">{{ $categoryName }}</option>
                        @endforeach
                    </select>
                </div>
             </div>
                <table class="table table-striped table-bordered dt-responsive nowrap" style="width:100%" id="class-table">
                    <thead>
                        <tr>
                            <th class="no-sort">SL.No</th>
                            <th>Title</th>
                            <th>Instructor</th>
                            @if($type == 'offline')
                                <th class="no-sort">Community</th>
                                <th class="no-sort">Amenity</th>
                            @endif
                            <th class="no-sort">Categories</th>
                            <th>Start Date</th>
                            <th>End Date</th>
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
    oTable = $('#class-table').DataTable({
        processing: true,
        serverSide: true,
        ordering: true,
        columnDefs: [
            {
                orderable: false,
                targets: "no-sort"
            },
            { "width": "10px", "targets": 0 },
            { "width": "100px", "targets": @if($type == 'offline') 8 @else 6 @endif },
            { "width": "50px", "targets": @if($type == 'offline') 9 @else 7 @endif },
        ],
        order: [[ @if($type == 'offline') 8 @else 6 @endif, "desc" ]],
        ajax: {
            url: "{!! url('admin/generalclass/dt') !!}",
            type: 'post',
            headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'},
            data: function ( d ) {
                d.type = '{{ $type }}';
                if( $('#categories').val() != undefined) {
                    d.cat_id = $('#categories').val();
                }
            }
        },
        columns: [
            {
                render: function (data, type, row, meta) {
                    return meta.row + meta.settings._iDisplayStart + 1;
                }
            },
            {data: 'title', name: 'title'},
            {data: 'instructor', name: 'instructor'},
            @if($type == 'offline')
                {data: 'community_name', name: 'community_name'},
                {data: 'amenity_name', name: 'amenity_name'},
            @endif
            {data: 'categories', name: 'categories',
                render: function(data, type, row, meta) {
                    return "<span class='badge badge-info'>" + row.categories + "</span>";
                }, searchable:false
            },
            {data: 'start_date', name: 'start_date', searchable: false},
            {data: 'end_date', name: 'end_date', searchable: false},
            {data: 'created_at', name: 'created_at', searchable: false},
            {data: 'actions', name: 'actions', searchable: false}
        ]
    });

    var editId = {{ $product->category_id ?? "null" }};
    $('#categories').on('change',function(){
        $('.overlay').show();
        var id = $(this).val();
        $.post('{{route('fetch.categories')}}', {"_token": "{{ csrf_token() }}", 'parent_cat_id' : id}, function(data){
            var options = "<option value=''>Select Sub Category</option>";
            $.each(data.data, function(i,j){
                var sel = j.id == editId ? 'selected' : '';
                options += "<option value='"+j.id+"' "+sel+">" + j.name + "</option>";
            });
            $('#sub_categories').html(options);
            $('.overlay').hide();
        });
    });

    $(document).on("change", "#categories", function() {
        oTable.ajax.reload();
    });
});    
</script>
@stop
