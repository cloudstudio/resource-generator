@if(isset($request['disk']))
	->disk('{{ $request['disk'] }}')
	@if(isset($request['prunable']) and $request['prunable'] == 'true')
		->prunable()
	@endif
@endif 