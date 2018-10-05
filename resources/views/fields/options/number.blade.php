@if(isset($request['min']))
	->min({{ $request['min'] }})
@endif
@if(isset($request['max']))
	->max({{ $request['max'] }})
@endif
@if(isset($request['step']))
	->step({{ $request['step'] }})
@endif