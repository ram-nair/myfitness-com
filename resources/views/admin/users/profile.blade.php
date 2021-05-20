@extends('adminlte::page')

@section('title', 'My Profile')

@section('content_header')
<div class="row mb-2">
    <div class="col-sm-6">
        <h1 class="m-0 text-dark">My Profile</h1>
    </div><!-- /.col -->
</div><!-- /.row -->
@stop

@section('content')
<div class="row">
    <div class="col-md-3">
        <!-- Profile Image -->
        <div class="card card-primary card-outline">
            <div class="card-body box-profile">
                <div class="text-center">
                    <img class="profile-user-img img-fluid img-circle" src="{{asset('images/default_user.png')}}" alt="User profile picture">
                </div>
                <h3 class="profile-username text-center">{{ $user->name  }}</h3>
                <p class="text-muted text-center">{{ $user->roles()->pluck('name')->implode(',') }}</p>
            </div>
            <!-- /.card-body -->
        </div>
        <!-- /.card -->
    </div>

    <div class="col-md-9">
        <div class="card card-primary card-outline">
            {{ Form::model($user, array('route' => array('admin.user.profile', $user->id), 'method' => 'POST', 'autocomplete' => 'off')) }}
            <div class="card-body">
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
            </div>
            <div class="card-footer">
                {{ Form::submit('Save', array('class' => 'btn btn-info float-right')) }}
            </div>
            {{ Form::close() }}
        </div>
    </div>
</div>
@stop