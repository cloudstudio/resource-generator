{{ ucwords($request['relation']) }}::make('{{ Str::singular(ucwords( Str::camel($request['related_table']))) }}')
