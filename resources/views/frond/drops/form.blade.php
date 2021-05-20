<div class="card card-outline card-info">
    <div class="card-body pad">
        <hr>
        <div>
          <label for="title">Select slots</label>
          @foreach ($slots as $slot)
            <div class="row">
              <div class="col-sm-4">
                <div class="input-group">
                    <label style="min-width:70px;" class="mr-2" for="day-{{ $slot->id }}">{{ $slot->name }}</label>
                    <input type="checkbox" id="day-{{ $slot->id }}" value="{{$slot->id}}" name="slots[]" class="custom-control slot-on mr-2" />
                  </div>
              </div>
            </div>
            @endforeach
        </div>
        <hr>
    </div>
    <div class="card-footer">
        {{ Form::submit(isset($submitButtonText) ? $submitButtonText : 'Create', array('onclick'=>"return validateCheckbox();",'id'=> 'slot_create','class' => 'btn btn-info float-right')) }}
        <a href="{{ route('store.drops.index') }}" class="btn btn-default ">Cancel</a>
    </div>
</div>

@section('js')
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/inputmask/4.0.9/jquery.inputmask.bundle.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.standalone.min.css" />
@stop