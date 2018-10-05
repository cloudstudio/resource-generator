@if(isset($request['options']))
    ->options([
    	@foreach($request['options'] as $key => $value)
	    '{{ $key }}' => '{{ $value }}',
	    @endforeach
	])
@endif