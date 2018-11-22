<?php

namespace Cloudstudio\ResourceGenerator\Http\Services;

use Doctrine\DBAL\Schema\Column;
use Illuminate\Database\DatabaseManager;

class ResourceGeneratorService
{
    use \Cloudstudio\ResourceGenerator\Http\Services\GeneratorFunctions;

    private $db;

    public function __construct(DatabaseManager $db)
    {
        $this->db = $db;
    }

    /**
     * [getDatabaseTables description].
     *
     * @param  [type] $connection [description]
     *
     * @return [type]             [description]
     */
    public function getDatabaseTables($connection = null)
    {
        return collect($this->db->connection($connection)->getDoctrineSchemaManager()->listTableNames())
            ->map(function (string $table) {
                return [
                    'table' => $table,
                    'singular' => $this->formatSingular($table),
                ];
            });
    }

    /**
     * [getTableColumns description].
     *
     * @param  [type] $table      [description]
     * @param  [type] $connection [description]
     *
     * @return [type]             [description]
     */
    public function getTableColumns($table, $connection = null)
    {
        return collect($this->db->connection($connection)->getDoctrineSchemaManager()->listTableColumns($table))
            ->map(function (Column $column) use ($table, $connection) {
                return [
                    'name' => $column->getName(),
                    'type' => $column->getType()->getName(),
                    'nullable' => ! $column->getNotnull(),
                    'field' => null,
                    'label' => null,
                    'required' => null,
                    'show' => 'all',
                    'sortable' => null,
                    'relation' => null,
                ];
            });
    }

    /**
     * [generateResourceFile description].
     *
     * @param  [type] $request   [description]
     * @param  [type] $namespace [description]
     * @param  [type] $model     [description]
     *
     * @return [type]            [description]
     */
    public function generateResourceFile($request, $namespace, $model, $resource)
    {
        $unique = $this->getUniqueField($request);

        $search = $this->arrayToFakeArray($request['search']);

        /* Generate Resource View*/
        $html = view('resource-generator::templates/resource', compact('namespace', 'request', 'unique', 'search', 'model', 'resource'))->render();
        $render = $this->replacePHP($html);

        file_put_contents(app_path($this->novaPath($request['singular'], '.php')), $render);
    }

    /**
     * [generateModelFile description].
     *
     * @param  [type] $request   [description]
     * @param  [type] $namespace [description]
     *
     * @return [type]            [description]
     */
    public function generateModelFile($request, $namespace)
    {
        /* Generate Model View*/
        $html = view('resource-generator::templates/model', compact('namespace', 'request'))->render();
        $render = $this->replacePHP($html);

        $this->checkOrCreateFolder($namespace);

        file_put_contents($this->modelPath($namespace, $request['singular'], '.php'), $render);
    }
}
