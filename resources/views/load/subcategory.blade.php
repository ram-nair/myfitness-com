@if(Auth::guard('admin')->check())

<option data-href="" value="">Select Sub Category</option>
@foreach($cat as $sub)
<option value="{{ $sub->id }}">{{ $sub->name }}</option>
@endforeach

@else 

<option data-href="" value="">Select Sub Category</option>
@foreach($cat as $sub)
<option value="{{ $sub->id }}">{{ $sub->name }}</option>
@endforeach
@endif