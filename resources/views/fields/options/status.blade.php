@if(isset($request['loadingWhen']))
    ->loadingWhen([
    	@foreach($request['loadingWhen'] as $loadingWhen)
	    '{{ $loadingWhen }}',
	    @endforeach
	])
@endif
@if(isset($request['failedWhen']))
    ->failedWhen([
    	@foreach($request['failedWhen'] as $failedWhen)
	    '{{ $failedWhen }}',
	    @endforeach
	])
@endif