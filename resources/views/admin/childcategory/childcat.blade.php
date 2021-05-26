<option data-href="" value="">Select Child Categories</option>
@foreach($cat as $subs)
<option  value="{{ $subs->id }}" @if(($sub) && $sub==$subs->id) selected @endif>{{ $subs->name }}</option>
@endforeach
