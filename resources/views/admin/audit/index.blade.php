@extends('adminlte::page')

@section('title', 'Logs of '.$label)

@section('content_header')
<div class="row mb-2">
    <div class="col-sm-6">
        <h1 class="m-0 text-dark">Logs of {{$label}}
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
                    <label>Filter By Event</label>
                    <select name="event_filter" id="event_filter" class="form-control filtr">
                         <option value="">Select event</option>
                    <option value="created">Created</option> 
                    <option value="updated">Updated</option> 
                    <option value="deleted">Deleted</option>            
                    </select>
                </div>

                <div class="form-group col-4">
                    <label>Filter By {{$label}}</label>
                    <input type="text" name="filter_type" id="filter_type" class="form-control filtr">
                </div>
                
            </div>
               
            </div>
            <div class="card-body pad">
               <table class="table table-striped table-bordered dt-responsive nowrap" style="width:100%" id="categories-table">
                 <thead>
                        <tr>
                            <th>SL.No</th>
                            <th>Modified by</th>
                            <th>{{$label}}</th>
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
            language: {
                searchPlaceholder: "Event,Old Value,New Value"
            },
            ajax: {
                url: "{!! route('admin.audits.datatable') !!}",
                type: 'post',
                headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'},
                  data: function ( d ) {
                    d.type = '{{$type}}';
                    if( $('#user_filter').val() != "") {
                        d.user_id = $('#user_filter').val();
                    }
                    
                    if( $('#event_filter').val() != "") {
                        d.event_id = $('#event_filter').val();
                        
                    }
                    d.filter_type = $('#filter_type').val();
                }
            },
            columns: [
                {
                    render: function (data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1;
                    }
                },
                {data: 'modifiedBy', name: 'admins.name'},
                {data: 'name', name: 'name', searchable: false},
                {data: 'event', name: 'audits.event'},
                {data: 'old_values', name: 'audits.old_values'},
                {data: 'new_values', name: 'audits.new_values'},
                
                {data: 'created_at', name: 'audits.created_at'},
            ]
        });
        $(".filtr").on("change keyup",  function(){
            oTable.draw();
        })
        

    });
</script>    
@stop