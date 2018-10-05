@if(isset($request['language']))
	@if($request['language'] == 'json')
		->json()
	@else
		->language('{{ $request['language'] }}')
	@endif
@endif 