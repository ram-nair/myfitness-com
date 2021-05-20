@extends('adminlte::page')

@section('title', 'My Profile')

@section('content_header')
<div class="row mb-2">
    <div class="col-sm-6">
        <h1 class="m-0 text-dark">Change Password</h1>
    </div><!-- /.col -->
</div><!-- /.row -->
@stop

@section('content')
{{ Form::open(array('url' => 'admin/changepassword')) }}
<div class="card card-outline card-info">
    <div class="card-body pad">
        <div class="row">
        <div class="col-md-6">
             <!--<div class="form-group">
                    {{ Form::label('password', 'Old Password') }}<br>
                    {{ Form::password('old_password', array('id'=>'old_password','required','minlength' => 6, 'class' => 'form-control'.($errors->has('old_password') ? ' is-invalid' : ''))) }}
                    {!! $errors->first('old_password','<p class="text-danger"><strong>:message</strong></p>') !!}
                </div>-->
                <div class="form-group">
                    {{ Form::label('password', 'New Password') }}<br>
                    {{ Form::password('password', array('id'=>'password','required','minlength' => 6, 'class' => 'form-control'.($errors->has('password') ? ' is-invalid' : ''))) }}
                    {!! $errors->first('password','<p class="text-danger"><strong>:message</strong></p>') !!}
                </div>

                <div class="form-group">
                    {{ Form::label('password', 'Confirm Password') }}<br>
                    {{ Form::password('password_confirmation', array('id'=>'password_confirmation','required',"data-rule-equalTo" => "#password", 'minlength' => 6, 'class' => 'form-control'.($errors->has('password_confirmation') ? ' is-invalid' : ''))) }}
                    {!! $errors->first('password_confirmation','<p class="text-danger"><strong>:message</strong></p>') !!}
                </div>
            </div>
                   
        </div> 
    </div>
    <div class="card-footer">
        {{ Form::submit(isset($submitButtonText) ? $submitButtonText : 'submit', array('id'=>'password_btn','class' => 'btn btn-info')) }}
        </div>
</div>
@stop
@section('css')
<link rel="stylesheet" href="{{asset('vendor/icheck-bootstrap/icheck-bootstrap.min.css')}}">
@stop
@section('js')
<script>
$("#password_btn").click(function(){
        var password = $("#password").val();
        password  = password.replace(/\s/g, "");
        var confirmPassword = $("#password_confirmation").val();
        if(password.length < 5){
            alert("Password must be atleast 6 character!");
            $("#password").val("");
            return false;
        }
        if (password != confirmPassword){
            alert("Passwords does not match!");
            return false;
        }
        return true;
    });
    </script>
    @stack('js')
    
    @yield('js')
    
@stop
