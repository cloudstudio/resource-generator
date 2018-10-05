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
		public function {{ str_singular($request['related_table']) }}()
		{
			return $this->{{ $request['relation'] }}('{{ $namespace }}\{{str_singular(ucwords( camel_case($request['related_table']))) }}', '{{ $request['name'] }}', '{{ $request['related_column'] }}');
		}
		@endif
	@endforeach
}
