<div class="card card-outline card-info">
    <div class="card-body pad">
        <div class="row">
          <div class="col-md-2 form-group">
            {{ Form::label('date range', 'Expires At') }}
            <div class="input-group">
              <div class="input-group-prepend">
                <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
              </div>
              <input type="text" class="form-control"  name="expire_at" data-inputmask-alias="datetime" data-inputmask-inputformat="yyyy-mm-d" data-mask="" id="datepicker"  @if(!@empty($gift)) value="{{ $gift->expire_at }}" @else  value="{{ date('Y-m-d') }}" @endif >
            </div>
          </div>
         
          </div>

          
      
        <div class="row">
          <div class="form-group">
                    {{ Form::label('name', 'Balance(AED)') }}
                    {{ Form::text('balance_amt', null, array('required','class' => 'form-control'.($errors->has('balance_amt') ? ' is-invalid' : '' ))) }}
                    {!! $errors->first('balance_amt','<p class="text-danger"><strong>:message</strong></p>') !!}
                </div>
        </div>

        <div class="row">
        <div class="form-group col-md-3">
                        {{ Form::label('parent_cat_id', 'Status') }}
                        <select class="form-control" name="status" id="status"  value={{$gift->status??''}}>
                        <option value="1" @if(!@empty($gift) && $gift->status==1) selected @endif>Active</option>
                        <option value="0" @if(!@empty($gift)&& $gift->status!=1) selected @endif>Inactive</option>
                        </select>
                        {!! $errors->first('parent_cat_id','<p class="text-danger"><strong>:message</strong></p>') !!}
                    </div>
        </div>

        <div class="row">
        <div class="form-group col-md-3">
                        {{ Form::label('parent_cat_id', 'Is Redeemable') }}
                        <select class="form-control" name="is_redeem" id="is_redeem"  value={{$gift->is_redeem??''}}>
                           <option value="1" @if(!@empty($gift) && $gift->is_redeem==1) selected @endif>Yes</option>
                            <option value="0" @if(!@empty($gift)&& $gift->is_redeem!=1) selected @endif>No</option>
                        </select>
                        {!! $errors->first('parent_cat_id','<p class="text-danger"><strong>:message</strong></p>') !!}
                    </div>
        </div>


    </div>
    <div class="card-footer">
        {{ Form::submit(isset($submitButtonText) ? $submitButtonText : 'Create', array('id'=> 'slot_create','class' => 'btn btn-info float-right')) }}
        <a href="{{ route('admin.gifts.index') }}" class="btn btn-default">Cancel</a>
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
  
  $('#daterange').val('');
  $('#datepicker').datepicker({
    format: 'yyyy-mm-d',
  });
  $('[data-mask]').inputmask();
  $('#daterange').on('cancel.daterangepicker', function(ev, picker) {
    //do something, like clearing an input
    $('#daterange').val('');
  });
  
  
});
</script>
@stop