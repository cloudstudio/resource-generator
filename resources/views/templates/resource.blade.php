@phptag

namespace {{ $namespace }}{{ $resource }};

use Illuminate\Http\Request;
@foreach($unique as $type)
use Laravel\Nova\Fields\{{ $type }};
@endforeach


class {{ $request['singular'] }} extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \{{ $model }}\{{ $request['singular'] }}::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = '{{ $request['title'] }}';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        {!! $search !!}
    ];

    /**
     * Get the displayable label of the resource.
     *
     * @return string
     */
    public static function label()
    {
        return __('{{ \str_plural(\ucwords(\snake_case($request['singular'], ' '))) }}');
    }

    /**
    * Get the displayable singular label of the resource.
    *
    * @return string
    */
    public static function singularLabel()
    {
        return __('{{ \ucwords(\snake_case($request['singular'], ' ')) }}');
    }

    /**
     * Get the fields displayed by the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function fields(Request $request)
    {
        return [
            @foreach($request['columns'] as $request)
                @if($request['show'] !== 'disabled')
                    @include('resource-generator::fields/magic', ['request' => $request]),
                @endif
            @endforeach
        ];
    }

    /**
     * Get the cards available for the request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function cards(Request $request)
    {
        return [];
    }

    /**
     * Get the filters available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function filters(Request $request)
    {
        return [];
    }

    /**
     * Get the lenses available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function lenses(Request $request)
    {
        return [];
    }

    /**
     * Get the actions available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function actions(Request $request)
    {
        return [];
    }
}
