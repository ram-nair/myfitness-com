@extends('adminlte::page')

@section('title', 'Edit slot')

@section('content_header')
<div class="row mb-2">
    <div class="col-sm-6">
        <h1 class="m-0 text-dark">Edit Slot</h1>
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
{{ Form::model($slot, array('route' => array('store.slots.update', $slot->id), 'method' => 'POST')) }}
    {{ method_field('PATCH') }}
    <div class="card card-outline card-info">
    <div class="card-body pad">
        <div class="row">
            <div class="col">
            <div class="input-group">
            {{ Form::label('date range', 'Date') }}
                    <div class="input-group-prepend">
                      <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                    </div>
                    <input type="text" class="form-control"  value="{{$slot->days}}" name="slot_date" data-inputmask-alias="datetime" data-inputmask-inputformat="dd/mm/yyyy" data-mask="" im-insert="false" readonly>
                  </div>
OR
                
                <div class="col">
                <div class="form-group">
                    <label for="title">slots</label>   <label for="title">capasity</label>
                        <div class="row">
                        <div class="form-group">
                          <input type="checkbox"  name="slots"  class="form-control" value="{{$slot->slots->id}}" checked disabled /><label for="title">{{$slot->slots->name}}</label>
                        
                        </div>
                        <div class="form-group">
                        
                          <input type="text" name="capacity" placeholder="capacity"  value="{{$slot->capacity}}" class="form-control">
                       </div>
                       </div>
                    
                </div>   
            </div>

             </div>
            
        </div>
      

    </div>
    <div class="card-footer">
        {{ Form::submit(isset($submitButtonText) ? $submitButtonText : 'Update', array('class' => 'btn btn-info float-right')) }}
        <a href="{{ route('store.slots.index') }}" class="btn btn-default ">Cancel</a>
    </div>
</div>
@section('js')
<script>
  $(function () {
   

    //Datemask dd/mm/yyyy
    $('#datemask').inputmask('dd/mm/yyyy', { 'placeholder': 'dd/mm/yyyy' })
    
    $('[data-mask]').inputmask()

    //Date range picker
    $('#reservation').daterangepicker()
    //Date range picker with time picker
   
    //Date range as a button
    $('#daterange-btn').daterangepicker(
      {
        ranges   : {
          'Today'       : [moment(), moment()],
          'Yesterday'   : [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
          'Last 7 Days' : [moment().subtract(6, 'days'), moment()],
          'Last 30 Days': [moment().subtract(29, 'days'), moment()],
          'This Month'  : [moment().startOf('month'), moment().endOf('month')],
          'Last Month'  : [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
        },
        startDate: moment().subtract(29, 'days'),
        endDate  : moment()
      },
      function (start, end) {
        $('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'))
      }
    )

    //Timepicker
    $('#timepicker').datetimepicker({
      format: 'LT'
    })
    
})
      
</script>

@stop
{{ Form::close() }}
@stop