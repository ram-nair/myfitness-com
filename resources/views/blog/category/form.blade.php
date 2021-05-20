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
                <div class="form-group">
                    {{ Form::label('Status', 'Status') }}<br>
                    <select class="form-control" name="status" id="status" >

                        <option value="1" @if(isset($blogCategory) && $blogCategory->status == 1) selected @endif>Enable</option>
                        <option value="0" @if(isset($blogCategory) && $blogCategory->status == 0) selected @endif>Disable</option>
                    </select>
                    {!! $errors->first('status','<p class="text-danger"><strong>:message</strong></p>') !!}
                </div>

            </div>
           </div>

    </div>
    <div class="card-footer">
        <a href="{{ route('admin.blog-category.index') }}" class="btn btn-default">Cancel</a>
        {{ Form::submit(isset($submitButtonText) ? $submitButtonText : 'Create', array('class' => 'btn btn-info')) }}
    </div>
</div>
