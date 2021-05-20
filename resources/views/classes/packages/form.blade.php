<div class="card card-outline card-info">
    <div class="overlay" style="display: none;">
        <i class="fas fa-2x fa-sync-alt fa-spin"></i>
    </div>
    <div class="card-body pad">
        <div class="row">
            <div class="col-md-4">
                <div class="form-group">
                    {{ Form::label('name', 'Name') }}
                    {{ Form::text('name', null, array('required'=>'','class' => 'form-control'.($errors->has('name') ? ' is-invalid' : '' ))) }}
                    {!! $errors->first('name','<p class="text-danger"><strong>:message</strong></p>') !!}
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    {{ Form::label('price', 'Price') }}
                    {{ Form::text('price', null, array('required'=>'','class' => 'form-control'.($errors->has('price') ? ' is-invalid' : '' ))) }}
                    {!! $errors->first('price','<p class="text-danger"><strong>:message</strong></p>') !!}
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    {{ Form::label('service charge', 'Service Charge') }}
                    {{ Form::text('service_charge', null, array('required'=>'','class' => 'form-control'.($errors->has('service_charge') ? ' is-invalid' : '' ))) }}
                    {!! $errors->first('service_charge','<p class="text-danger"><strong>:message</strong></p>') !!}
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    {{ Form::label('no_of_days', 'No Of Sessions') }}
                    {{ Form::text('no_of_sessions', null, array('required'=>'','class' => 'form-control'.($errors->has('no_of_sessions') ? ' is-invalid' : '' ))) }}
                    {!! $errors->first('no_of_sessions','<p class="text-danger"><strong>:message</strong></p>') !!}
                </div>
            </div>
        </div>
    </div>
    <div class="card-footer">
        <a href="{{ route('admin.packages.index') }}" class="btn btn-default">Cancel</a>
        {{ Form::submit(isset($submitButtonText) ? $submitButtonText : 'Create', array('class' => 'btn btn-info float-right')) }}
    </div>
</div>
