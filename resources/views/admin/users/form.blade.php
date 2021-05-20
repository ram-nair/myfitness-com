<div class="card card-outline card-info">
    <div class="card-body pad">
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    {{ Form::label('name', 'Name') }}
                    {{ Form::text('name', null, array('class' => 'form-control'.($errors->has('name') ? ' is-invalid' : ''))) }}
                    {!! $errors->first('name','<p class="text-danger"><strong>:message</strong></p>') !!}
                </div>

                <div class="form-group">
                    {{ Form::label('email', 'Email') }}
                    {{ Form::email('email', null, array('class' => 'form-control'.($errors->has('email') ? ' is-invalid' : ''))) }}
                    {!! $errors->first('email','<p class="text-danger"><strong>:message</strong></p>') !!}
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    {{ Form::label('password', 'Password') }}<br>
                    {{ Form::password('password', array('minlength' => 6, 'class' => 'form-control'.($errors->has('password') ? ' is-invalid' : ''))) }}
                    {!! $errors->first('password','<p class="text-danger"><strong>:message</strong></p>') !!}
                </div>

                <div class="form-group">
                    {{ Form::label('password', 'Confirm Password') }}<br>
                    {{ Form::password('password_confirmation', array("data-rule-equalTo" => "#password", 'minlength' => 6, 'class' => 'form-control'.($errors->has('password_confirmation') ? ' is-invalid' : ''))) }}
                    {!! $errors->first('password_confirmation','<p class="text-danger"><strong>:message</strong></p>') !!}
                </div>
            </div>
        </div>

        <h5>Roles</h5>

        <div class="row">
            <div class="col-12">
                <div class='form-group clearfix'>
                    @foreach ($roles as $role)
                    <div class="icheck-primary d-inline">
                        {{ Form::checkbox('roles[]',  $role->id, null, ['id'=>$role->name] ) }}
                        {{ Form::label($role->name, ucfirst($role->name)) }}
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    <div class="card-footer">
        {{ Form::submit(isset($submitButtonText) ? $submitButtonText : 'Create', array('class' => 'btn btn-info')) }}
        <a href="{{ route('admin.users.index') }}" class="btn btn-default float-right">Cancel</a>
    </div>
</div>