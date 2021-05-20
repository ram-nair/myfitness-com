@extends('adminlte::page')

@section('title', $pageTitle)

@section('content_header')
<div class="row mb-2">
    <div class="col-sm-6">
        <h1 class="m-0 text-dark">{{$pageTitle}}</h1>
    </div><!-- /.col -->
</div><!-- /.row -->
@stop
@section('content')
<div class="card card-primary">
    <div class="card-body">
        <div class="row">
            <div class="col-4 col-sm-2">
                <div class="nav flex-column nav-tabs h-100">
                    <a class="nav-link active" id="vert-tabs-home-tab" href="" role="tab" aria-controls="vert-tabs-home" aria-selected="false">General</a>
                <a class="nav-link" id="vert-tabs-home-tab" href="{{ route("admin.sessions.index", ['generalclass' => $generalclass->id, 'type' => $type]) }}" role="tab" aria-controls="vert-tabs-home" aria-selected="false">Sessions</a>
                </div>
            </div>
            <div class="col-8 col-sm-10">
                <div class="tab-content">
                    <div class="tab-pane active" id="general">
                        <div class="tile">
                            {{ Form::model($generalclass, array('route' => array('admin.generalclass.update', $generalclass->id), 'method' => 'POST', 'enctype' => 'multipart/form-data', 'class' => 'class-create')) }}
                                {{ method_field('PATCH') }}
                                <input type="hidden" name="id" value="{{ $generalclass->id }}">
                                <input type="hidden" value="{{ $type }}" name="type" />
                                @include ('classes.online.form', ['submitButtonText' => 'Update'])
                            {{ Form::close() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@stop
@section('js')
<script>
    var editId = {{ $generalclass->category_id ?? "null" }};
    $('#categories').on('change',function(){
        $('.overlay').show();
        var id = $(this).val();
        console.log(id);
        $.post('{{route('fetch.categories')}}', {"_token": "{{ csrf_token() }}", 'parent_cat_id' : id}, function(data){
            var options = "";
            $.each(data.data, function(i,j){
                var sel = j.id == editId ? 'selected' : '';
                options += "<option value='"+j.id+"' "+sel+">" + j.name + "</option>";
            });
            $('#sub_categories').html(options);
            $('.overlay').hide();
        });
    });
    $("#categories").trigger('change');
});
</script>
@endsection
