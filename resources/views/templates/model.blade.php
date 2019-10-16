@phptag

namespace {{ $namespace }};

use Illuminate\Database\Eloquent\Model;

class {{ $request['singular'] }} extends Model
{

/**
* @var string
*/
protected $table = '{{ $request['table'] }}';

protected $casts = [
@foreach($request['columns'] as $cast)
@if($cast['field'] == 'DateTime' or $cast['field'] == 'Date')
'{{ $cast['name'] }}' => '{{ $cast['type'] }}',
@endif
@endforeach
];

@foreach($request['columns'] as $request)
@if(isset($request['relation']))
public function {{ Str::singular($request['related_table']) }}()
{
return $this->{{ $request['relation'] }}('{{ $namespace }}\{{Str::singular(ucwords( Str::camel($request['related_table']))) }}', '{{ $request['name'] }}', '{{ $request['related_column'] }}');
}
@endif
@endforeach
}