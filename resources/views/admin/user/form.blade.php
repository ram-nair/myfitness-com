<div class="card card-outline card-info">
    <div class="card-body pad">
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    {{ Form::label('name', 'First Name') }}
                    {{ Form::text('first_name', null, array('required','class' => 'form-control'.($errors->has('first_name') ? ' is-invalid' : ''))) }}
                    {!! $errors->first('first_name','<p class="text-danger"><strong>:message</strong></p>') !!}
                </div>
                </div>
                <div class="col-md-6">
                <div class="form-group">
                    {{ Form::label('last_name', 'Last Name') }}
                    {{ Form::text('last_name', null, array('class' => 'form-control'.($errors->has('last_name') ? ' is-invalid' : ''))) }}
                    {!! $errors->first('last_name','<p class="text-danger"><strong>:message</strong></p>') !!}
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    {{ Form::label('name', 'Mobile') }}
                    {{ Form::text('phone', null, array('required','class' => 'form-control'.($errors->has('phone') ? ' is-invalid' : ''))) }}
                    {!! $errors->first('phone','<p class="text-danger"><strong>:message</strong></p>') !!}
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    {{ Form::label('email', 'Email') }}
                    {{ Form::email('email', null, array('required','class' => 'form-control'.($errors->has('email') ? ' is-invalid' : ''))) }}
                    {!! $errors->first('email','<p class="text-danger"><strong>:message</strong></p>') !!}
                </div>
            </div>
            <div class="col-md-6">
            <div class="form-group">
                    {{ Form::label('password', 'Gender') }} <span class="required-field"></span><br>
                    <select name="gender"  class="form-control" style="width:100%" required >
                        <option value="">Select a gender</option>
                        <option value="M" @if(!empty($user) && ('M'==$user->gender)) selected @endif>Male</option>
                        <option value="F" @if(!empty($user) && ('F'==$user->gender)) selected @endif>Female</option>
                        <option value="T" @if(!empty($user) && ('T'==$user->gender)) selected @endif>Others</option>
                        </select>
                    {!! $errors->first('gender','<p class="text-danger"><strong>:message</strong></p>') !!}
                </div>
            <div class="form-group">
            </div>
            </div>
        </div>

        

       
    </div>
    <div class="card-footer">
        {{ Form::submit(isset($submitButtonText) ? $submitButtonText : 'Create', array('class' => 'btn btn-info')) }}
        <a href="{{ route('admin.users.index') }}" class="btn btn-default float-right">Cancel</a>
    </div>
</div>