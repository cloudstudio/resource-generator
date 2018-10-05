<?php

namespace Cloudstudio\ResourceGenerator\Http\Services;

use Illuminate\Support\Facades\DB;

class ResourceGeneratorService
{
    use \Cloudstudio\ResourceGenerator\Http\Services\GeneratorFunctions;

    /**
     * @param Storage $storage
     */
    public function __construct()
    {
    }

    /**
     * [getDatabaseTables description].
     * @param  [type] $connection [description]
     * @return [type]             [description]
     */
    public function getDatabaseTables($connection = null)
    {
        return collect(DB::connection($connection)->select('show tables'))->map(function ($val) {
            foreach ($val as $key => $tbl) {
                return [
                    'table'    => $tbl,
                    'singular' => $this->formatSingular($tbl),
                ];
            }
        });
    }

    /**
     * [getTableColumns description].
     * @param  [type] $table      [description]
     * @param  [type] $connection [description]
     * @return [type]             [description]
     */
    public function getTableColumns($table, $connection = null)
    {
        return collect(DB::connection($connection)->select('show columns from '.$table))->map(function ($column) use ($table, $connection) {
            $column = (object) array_change_key_case((array) $column);

            return [
                'name'     => $column->field,
                'type'     => $this->getColumnType($table, $column->field, $connection),
                'nullable' => $column->null == 'YES' ? true : false,
                'field'    => null,
                'label'    => null,
                'required' => null,
                'show'     => 'all',
                'sortable' => null,
                'relation' => null,
            ];
        });
    }

    /**
     * [generateResourceFile description].
     * @param  [type] $request   [description]
     * @param  [type] $namespace [description]
     * @param  [type] $model     [description]
     * @return [type]            [description]
     */
    public function generateResourceFile($request, $namespace, $model)
    {
        $unique = $this->getUniqueField($request);

        $search = $this->arrayToFakeArray($request['search']);

        /* Generate Resource View*/
        $html = view('resource-generator::templates/resource', compact('namespace', 'request', 'unique', 'search', 'model'))->render();
        $render = $this->replacePHP($html);

        file_put_contents(app_path($this->novaPath($request['singular'], '.php')), $render);
    }

    /**
     * [generateModelFile description].
     * @param  [type] $request   [description]
     * @param  [type] $namespace [description]
     * @return [type]            [description]
     */
    public function generateModelFile($request, $namespace)
    {
        /* Generate Model View*/
        $html = view('resource-generator::templates/model', compact('namespace', 'request'))->render();
        $render = $this->replacePHP($html);

        $this->checkOrCreateFolder($namespace);

        file_put_contents(base_path($this->modelPath($namespace, $request['singular'], '.php')), $render);
    }

    /**
     * Return column db type.
     *
     * @param  $table
     * @param  $column
     * @param  $connection
     *
     * @return  string
     */
    private function getColumnType($table, $column, $connection = null)
    {
        return DB::connection($connection)->getDoctrineColumn($table, $column)->getType()->getName();
    }
}
