@extends('adminlte::page')

@section('title', 'Admin Users')

@section('content_header')
<div class="row mb-2">
    <div class="col-sm-6">
        <h1 class="m-0 text-dark">Audit Logs of {{$user->name}}</h1>({{$user->email}})
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
                    <label>Filter By Model</label>
                    <select name="parent_cat_id" id="category_filter" class="form-control">
                        <option value="">Select Modal</option> 
                       <option value="Category">Category</option> 
                    <option value="Brand">Brand</option> 
                     <option value="Logged In">Logged In</option> 
                     <option value="Logged Out">Logged Out</option> 
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
                            <th>Model</th>
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
                url: "{!! route('admin.user.audit.datatable') !!}",
                type: 'post',
                headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'},
                  data: function ( d ) {
                    d.user_id = '{{$user->id}}';
                    if( $('#category_filter').val() != "") {
                        d.modal_id = $('#category_filter').val();
                        d.event_type = $('#event_filter').val();
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

                {data: 'auditable_type', name: 'auditable_type'},
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

        $(document).on("change", "#category_filter", function() {
            oTable.ajax.reload();
        });
         $(document).on("change", "#event_filter", function() {
            oTable.ajax.reload();
        });

    });
</script>    
@stop