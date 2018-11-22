<?php

namespace Cloudstudio\ResourceGenerator\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Cloudstudio\ResourceGenerator\Http\Services\Settings;
use Cloudstudio\ResourceGenerator\Http\Services\GeneratorFunctions;
use Cloudstudio\ResourceGenerator\Http\Services\ResourceGeneratorService;

class ResourceGeneratorController extends Controller
{
    use GeneratorFunctions;

    /**
     * @var mixed
     */
    protected $service;

    /**
     * @var mixed
     */
    protected $setting;

    /**
     * @param ResourceGeneratorService $filemanagerService
     */
    public function __construct(ResourceGeneratorService $resourceGeneratorService, Settings $setting)
    {
        $this->service = $resourceGeneratorService;
        $this->setting = $setting;
    }

    /**
     * @param Request $request
     */
    public function checkFile(Request $request)
    {
        if ($request->has('createModel')) {
            $modelNamespace = $this->setting->getSettings()->namespace->value;
            $last = substr($modelNamespace, -1);
            ($last == '\\') ? $modelNamespace : $modelNamespace.'\\';

            $model = $this->checkIfFileExist($modelNamespace, $request->get('singular'));
        }

        $resource = $this->checkIfFileExist($this->getNamespace(), $this->novaPath($request->get('singular')));

        return response()->json([
            'model'    => isset($model) ? $model : false,
            'resource' => $resource,
        ]);
    }

    /**
     * [getTables description].
     * @return [type] [description]
     */
    public function getTables()
    {
        return response()->json([
            'tables'   => $this->service->getDatabaseTables(),
            'settings' => $this->setting->getSettings(),
        ]);
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function getColumns(Request $request)
    {
        return response()->json(['columns' => $this->service->getTableColumns($request->table)]);
    }

    /**
     * @param $data
     * @return mixed
     */
    public function generateResource($data, $namespace, $model, $resource)
    {
        return $this->service->generateResourceFile($data, $namespace, $model, $resource);
    }

    /**
     * @param $data
     * @return mixed
     */
    public function generateModel($data, $namespace)
    {
        return $this->service->generateModelFile($data, $namespace);
    }

    /**
     * @param Request $request
     */
    public function generateFile(Request $request)
    {
        $namespace = $this->setting->value('namespace');
        $resource = $this->setting->value('resource');

        $this->generateResource($request, $this->getNamespace(), $namespace, $resource);

        if ($request['createModel']):
            $this->generateModel($request, $namespace);
        endif;
    }
}
