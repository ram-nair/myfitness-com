<option value="">Select Child Category</option>
@foreach($subcat as $child)
<option value="{{ $child->id }}">{{ $child->name }}</option>
@endforeach