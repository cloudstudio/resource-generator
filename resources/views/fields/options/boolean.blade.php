@if(isset($request['trueValue']))
    ->trueValue('{{$request['trueValue']}}')
@endif 
@if(isset($request['falseValue']))
    ->falseValue('{{$request['falseValue']}}')
@endif