@if($request['relation'])
	@include('resource-generator::fields.options.relation', ['request' => $request])
@else
	@include('resource-generator::fields.options.input', ['request' => $request])
@endif

@include('resource-generator::fields.options.required', ['request' => $request])
@include('resource-generator::fields.options.searchable', ['request' => $request])
@include('resource-generator::fields.options.show', ['request' => $request])
@include('resource-generator::fields.options.sortable', ['request' => $request])
@include('resource-generator::fields.options.boolean', ['request' => $request])
@include('resource-generator::fields.options.code', ['request' => $request])
@include('resource-generator::fields.options.file', ['request' => $request])
@include('resource-generator::fields.options.place', ['request' => $request])
@include('resource-generator::fields.options.select', ['request' => $request])
@include('resource-generator::fields.options.status', ['request' => $request])
@include('resource-generator::fields.options.format', ['request' => $request])
@include('resource-generator::fields.options.number', ['request' => $request])