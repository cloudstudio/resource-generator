@if(isset($request['cities']))
	@if($request['cities'])
	    ->countries([
	    	@foreach($request['cities'] as $country)
		    '{{ $country }}',
		    @endforeach
		])
	@endif
	@if($request['onlyCities'] == 'true')
		->onlyCities()
	@endif
@endif