<div class="card card-outline card-info">
    <div class="card-body pad">
        <div class="row">
          <div class="col-md-2 form-group">
            {{ Form::label('date range', 'Date') }}
            <div class="input-group">
              <div class="input-group-prepend">
                <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
              </div>
              <input type="text" class="form-control"  name="slot_date" data-inputmask-alias="datetime" data-inputmask-inputformat="dd/mm/yyyy" data-mask="" id="datepicker" value="{{ date('d/m/Y') }}">
            </div>
          </div>

          <div class="form-group">
            {{ Form::label('or', 'OR') }}
          </div>
          
          <div class="col-md-3 form-group">
            {{ Form::label('date range', 'Date Range') }}
            <div class="input-group">
              <div class="input-group-prepend">
                <span class="input-group-text">
                  <i class="far fa-calendar-alt"></i>
                </span>
              </div>
              <input type="text" class="form-control float-right daterange" id="daterange" name="daterange" value="" autocomplete="off">
            </div>
          </div>
        </div>
<hr>
          <div>
            <label for="title">Select slots</label>
            @foreach ($slots as $slot)
              <div class="row">
                <div class="col-sm-4">
                  <div class="input-group" style="margin-bottom:20px;">
                      <label style="min-width:70px;" class="mr-2" for="day-{{ $slot->id }}">{{ $slot->slot_name }}</label>
                      <input type="checkbox" id="day-{{ $slot->id }}" value="{{$slot->id}}" name="slots[slot][]" class="custom-control slot-on mr-2" />
                      <input type="number" style="display: none;" name="capacity[slot][]" placeholder="capacity" class="capacity-container form-control" value="">
                  </div>
                </div>
              </div>
              @endforeach
          </div>
        <hr>
        <div class="row">
          <div class="col">
            {{ Form::label('days', 'Exclude Days') }}
            <div class="row">
              @foreach ($days as $day)              
                <div class="col form-group">
                  {{ Form::label('day', $day) }}
                  <div class="input-group">
                    {{ Form::checkbox('days[]', $day, null, [ ($day == "Sunday") ? 'checked' : '', 'data-bootstrap-switch', 'class' => 'custom-control'] ) }}
                  </div>
                </div>
              @endforeach
            </div>
          </div>
        </div>

    </div>
    <div class="card-footer">
        {{ Form::submit(isset($submitButtonText) ? $submitButtonText : 'Create', array('id'=> 'slot_create','class' => 'btn btn-info float-right')) }}
        <a href="{{ route('store.slots.index') }}" class="btn btn-default ">Cancel</a>
    </div>
</div>

@section('js')
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/inputmask/4.0.9/jquery.inputmask.bundle.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.standalone.min.css" />
<script>
$(function () {
  //Date range picker
  $('#daterange').daterangepicker({
    "minDate": "{{ date('d/m/Y') }}",
    locale: {
      format: 'DD/MM/YYYY'
    }
  });
  $('#daterange').val('');
  $('#datepicker').datepicker({
    format: 'dd/mm/yyyy',
  });
  $('[data-mask]').inputmask();
  $('body').on('change', '.slot-on', function(){
    if($(this).is(":checked")){
      $(this).next('.capacity-container').show();
    }else{
      $(this).next('.capacity-container').hide();
    }
  });
  $('#daterange').on('cancel.daterangepicker', function(ev, picker) {
    //do something, like clearing an input
    $('#daterange').val('');
  });
  
  $('body').on('submit', '.class-create', function(){
    var formSubmit = true;
    var anySelect = false;
    $(".slot-on").each(function(index){
      if($(this).is(':checked')){
        anySelect = true;
        if($(this).next('.capacity-container').val() < 1){
          formSubmit = false;
        }
      }
    });
    if(!formSubmit || !anySelect){
      alert("Please select slot, capacity");
      return false;
    }
  });
});
</script>
@stop