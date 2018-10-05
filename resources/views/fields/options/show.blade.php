@if($request['show'] !== 'all')
    ->{{ $request['show'] }}()
@endif