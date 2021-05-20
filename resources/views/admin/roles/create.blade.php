@extends('adminlte::page')

@section('title', 'Add New Role')

@section('content_header')
<div class="row mb-2">
    <div class="col-sm-6">
        <h1 class="m-0 text-dark">Add Role</h1>
    </div><!-- /.col -->
</div><!-- /.row -->
@stop

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card card-outline card-info">
            {{ Form::open(array('url' => 'admin/roles')) }}
            <div class="card-body pad">
                <div class="row">
                    <div class="col-md-6">   
                        <div class="form-group">
                            {{ Form::label('name', 'Name') }}
                            {{ Form::text('name', null, array('class' => 'form-control'.($errors->has('name') ? ' is-invalid' : ''))) }}
                            {!! $errors->first('name','<p class="text-danger"><strong>:message</strong></p>') !!}
                        </div>
                    </div>
                </div>

                <h5 class="mb-2">Assign Permissions</h5>

                <div class="row">
                    @foreach ($permissions as $k => $v)
                    <div class="col-md-3">
                        <div class="card card-outline card-info">
                            <div class="card-header p-1"><h3 class="card-title text-bold text-sm">{{ucfirst($k)}}</h3></div>
                            <div class="card-body pb-0 pt-1">
                                <div class="row">
                                    @if(is_array($v))
                                    <?php $v1 = array_chunk($v, 2, true); ?>
                                    @foreach ($v1 as $v2)
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            @foreach ($v2 as $k1 => $v3)
                                            <div class="custom-control custom-checkbox">
                                                {{ Form::checkbox('permissions[]',  $k1,  null, ['id'=>$k1,'class'=>'custom-control-input'] ) }}
                                                {{ Form::label($k1, ucfirst($v3),['class'=>'custom-control-label text-sm']) }}
                                            </div>
                                            @endforeach
                                        </div>
                                    </div>
                                    @endforeach
                                    @else
                                    <div class="form-group">
                                        <div class="custom-control custom-checkbox">
                                            {{ Form::checkbox('permissions[]',  $v,  null, ['id'=>$v,'class'=>'custom-control-input'] ) }}
                                            {{ Form::label($v, 'All',['class'=>'custom-control-label text-sm']) }}
                                        </div>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            <div class="card-footer">
                {{ Form::submit('submit', array('class' => 'btn btn-info')) }}
                <a href="{{ route('admin.roles.index') }}" class="btn btn-default float-right">Cancel</a>
            </div>
            {{ Form::close() }}
        </div>
    </div>
</div>
@stop
@section('css')
<link rel="stylesheet" href="{{asset('vendor/bs-custom-file-input/bs-custom-file-input.min.js')}}">
@stop