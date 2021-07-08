@extends('adminlte::page')

@section('title', 'Gift Card Management')

@section('content_header')
<div class="row mb-2">
    <div class="col-sm-6">
        <h1 class="m-0 text-dark">Gift Card Management</h1>
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
                        <a href="{{ route('admin.gifts.create') }}" class="btn btn-success btn-sm">New Gift Card</a>
                    </div>
                </div>
                <!-- /. tools -->
            </div>
            <div class="card-body pad">
                <div class="form-group col-4">
                    <label>Filter By Date</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                        </div>
                        <input type="text" class="form-control"  name="slot_date" data-inputmask-alias="datetime" data-inputmask-inputformat="dd/mm/yyyy" data-mask="" id="datepicker">
                    </div>
                </div>
                <table class="table table-striped table-bordered dt-responsive nowrap" style="width:100%" id="users-table">
                    <thead>
                        <tr>
                            <th class="no-sort">SL.No</th>
                            <th>Name</th>
                            <th>Code</th>
                            <th>Balance Amount</th>
                            <th>Status</th>
                            <th>Created At</th>
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
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/inputmask/4.0.9/jquery.inputmask.bundle.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.standalone.min.css" />

<script type='text/javascript'>
    $(function () {
        oTable = $('#users-table').DataTable({
            processing: true,
            serverSide: true,
            ordering: true,
            columnDefs: [
                {
                    orderable: false,
                    targets: "no-sort"
                },
                { "width": "10px", "targets": 0 },
                { "width": "100px", "targets": 3 },
                { "width": "100px", "targets": 5 }, 
            ],
            order: [[ 4, "dsc"]],
            //autoWidth: false,
            ajax: {
                url: "{!! url('admin/gifts/dt') !!}",
                type: 'POST',
                headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'},
                data: function ( d ) {
                    if( $('#datepicker').val() != undefined) {
                        d.days = $('#datepicker').val();
                    }
                }
                  
            },
            columns: [
                {
                    render: function (data, type, row, meta) {
                        
                            return meta.row + meta.settings._iDisplayStart + 1;
                   }
                },
                {data: 'name', name: 'name'},
                {data: 'code', name: 'code'},
                {data: 'balance_amt', name: 'balance_amt',
                render: function(data, type, row, meta) {
                    return "AED " + row.balance_amt ;
                }
            },
                {data: 'status', name: 'status',
                render: function(data, type, row, meta) {
                    if(row.status){
                        return "Active";
                    }else{
                        return "Inactive";
                    }
                    
                }
            },
           
                {data: 'created_at', name: 'created_at', searchable: false},
                {data: 'actions', name: 'actions', searchable: false}
            ]
        });
         $(document).on("change", "#datepicker", function() {
            oTable.ajax.reload();
        });
    });
$(function () {
  //Date range picker
 
  $('#datepicker').datepicker({
    format: 'dd/mm/yyyy',
  });

});

</script>    
@stop