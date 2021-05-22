<div class="card card-outline card-info">
    <div class="overlay" style="display: none;">
        <i class="fas fa-2x fa-sync-alt fa-spin"></i>
    </div>
    <div class="card-body pad">
        <div class="row">
            <div class="col-md-6">
             <div class="form-group col-md-6">
                        {{ Form::label('category_id', 'Product Main Category') }}
                        {{ Form::select('category_id', $cats, null, array('required','class' => 'form-control select2 '.($errors->has('category_id') ? ' is-invalid' : ''))) }}
                        {!! $errors->first('category_id','<p class="text-danger"><strong>:message</strong></p>') !!}
                    </div>
                <div class="form-group">
                    {{ Form::label('name', 'Name') }}
                    {{ Form::text('name', null, array('required','class' => 'form-control'.($errors->has('name') ? ' is-invalid' : '' ))) }}
                    {!! $errors->first('name','<p class="text-danger"><strong>:message</strong></p>') !!}
                </div> 
            </div>
        </div>
    </div>
    <div class="card-footer">
        <a href="{{ route('admin.subcategories.index') }}" class="btn btn-default">Cancel</a>
        {{ Form::submit(isset($submitButtonText) ? $submitButtonText : 'Create', array('class' => 'btn btn-info float-right')) }}
    </div>
</div>

@section('js')
<script>
$(function(){
    var editId = {{ $category->parent_cat_id ?? "null" }};
    var currentId = {{ $category->id ?? "null" }};
    $('#business_type_category_id').on('change',function(){
        $('.overlay').show();
        var id = $(this).val();
        $.post('{{route('get-sub-categories')}}',{id:id},function(data){
            $('#parent_cat_id').html('');
            var options = "<option value=''>Main Category</option><optgroup label='Sub Categories'>";
            $('#parent_cat_id').append(options);
            $.each( data.data, function( key, value ) {
                var sel = key == editId ? 'selected' : '';                
                var dis = key == currentId ? 'disabled' : '';                
                $('#parent_cat_id').append("<option value='"+ key + "' "+sel+" "+dis+">"+ value + "</option");
            });
            $('#parent_cat_id').append("</optgroup>");
            // $('#parent_id').val($('#parent_id').attr('value'));
            $('.overlay').hide();
        });
    });


    $("#business_type_category_id").trigger('change');
    $('#is_service').on('switchChange.bootstrapSwitch', function (event, state) {
        if(state)
            $("#form_service_type").show();
        else
            $("#form_service_type").hide();
    });

    $('#show_disclaimer').on('switchChange.bootstrapSwitch', function (event, state) {
        if(state)
            $("#form_disclaimer").show();
        else
            $("#form_disclaimer").hide();
    });
});
</script>
@endsection
