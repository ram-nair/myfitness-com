@extends('adminlte::page')

@section('title', 'Product Management')

@section('content_header')
<div class="row mb-2">
    <div class="col-sm-6">
        <h1 class="m-0 text-dark">Add Product</h1>
    </div><!-- /.col -->
</div><!-- /.row -->
@stop

@section('content')
{{ Form::open(array('route' => 'admin.service-products.store','enctype' => 'multipart/form-data','class' => 'class-create')) }}
 @include ('services.products_2.form')
{{ Form::close() }}
@endsection

@section('js')
<script>
$(function(){
    var editId = {{ $product->category_id ?? "null" }};
   /* $('#categories').on('change',function(){
        $('.overlay').show();
        var id = $(this).val();
        $.post('{{route('fetch.categories')}}', {"_token": "{{ csrf_token() }}", 'parent_cat_id' : id, 'business_type_cat_id': '{{ $businessTypeCatId }}'}, function(data){
            var options = "";
            $.each(data.data, function(i,j){
                var sel = j.id == editId ? 'selected' : '';
                options += "<option value='"+j.id+"' "+sel+">" + j.name + "</option>";
            });
            $('#sub_categories').html(options);
            $('.overlay').hide();
        });
    });*/

    // $("#categories").trigger('change');
});
</script>
@endsection