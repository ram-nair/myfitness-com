@extends('adminlte::page')

@section('title', 'Admin Users')

@section('content_header')
<div class="row mb-2">
    <div class="col-sm-6">
        <h1 class="m-0 text-dark">Logs of Brands
    </div><!-- /.col -->
</div><!-- /.row -->
@stop

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card card-outline card-info">
           <div class="card-body pad">
            <div class="row">
                 <div class="form-group col-4">
                    <label>Filter By User</label>
                    <select name="parent_cat_id" id="user_filter" class="form-control">
                        <option value="">Select User</option> 
                        @foreach($users as $user)
                        <option value="{{$user->id}}">{{$user->name}}</option> 
                        @endforeach
                      
                    </select>
                </div>
                <div class="form-group col-4">
                    <label>Filter By Brand</label>
                    <select name="parent_cat_id" id="brand_filter" class="form-control">
                        <option value="">Select Brand</option> 
                        @foreach($brands as $brand)
                        <option value="{{$brand->id}}">{{$brand->name}}</option> 
                        @endforeach 
                    </select>
                </div>
                  <div class="form-group col-4">
                    <label>Filter By Event</label>
                    <select name="" id="event_filter" class="form-control">
                         <option value="">Select event</option>
                    <option value="created">Created</option> 
                    <option value="updated">Updated</option> 
                    <option value="deleted">Deleted</option>            
                    </select>
                </div>
                
            </div>
               
            </div>
            <div class="card-body pad">
               <table class="table table-striped table-bordered dt-responsive nowrap" style="width:100%" id="categories-table">
                 <thead>
                        <tr>
                            <th>SL.No</th>
                            <th>Brand</th>
                            <th>Event</th>
                             <th>Old Value</th>
                             <th>New Value</th>
                            <th>Time</th>
                             
        
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
        oTable = $('#categories-table').DataTable({
            processing: true,
            serverSide: true,
            ordering: false,
            //autoWidth: false,
            ajax: {
                url: "{!! route('admin.brands.brand.datatable') !!}",
                type: 'post',
                headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'},
                  data: function ( d ) {
                   
                    if( $('#user_filter').val() != "") {
                        d.user_id = $('#user_filter').val();
                        
                    }
                    if( $('#brand_filter').val() != "") {
                        d.brand_id = $('#brand_filter').val();
                        
                    }
                    if( $('#event_filter').val() != "") {
                        d.event_id = $('#event_filter').val();
                        
                    }
                    // if($('#category_filter').val()=="" && $('#parent_cat_id').val()!="")
                    // {
                    //     d.parent_cat_id = $('#parent_cat_id').val();
                    // }
                }
            },
            columns: [
                {
                    render: function (data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1;
                    }
                },

                {data: 'brand.name', name: 'brand.name'},
                {data: 'event', name: 'event'},
                {data: 'old_values', name: 'old_values'},
                {data: 'new_values', name: 'new_values'},
                 {data: 'created_at', name: 'created_at'},
                /*{data: 'email', name: 'email'},
                {data: 'created_at', name: 'created_at', searchable: false},
                {data: 'roles', name: 'roles', searchable: false},
                {data: 'actions', name: 'actions', searchable: false}*/
            ]
        });

        $(document).on("change", "#user_filter", function() {
            oTable.ajax.reload();
        });
         $(document).on("change", "#brand_filter", function() {
            oTable.ajax.reload();
        });
          $(document).on("change", "#event_filter", function() {
            oTable.ajax.reload();
        });

    });
</script>    
@stop