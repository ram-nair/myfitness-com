
@if(!empty($data))
    @foreach($data as $k=>$d)
        <b>{{$k}}</b> : {!! $d !!}
        <br>
    @endforeach
@endif