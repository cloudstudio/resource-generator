<?php

namespace Cloudstudio\ResourceGenerator\Http\Services;

use File;
use Illuminate\Container\Container;

trait GeneratorFunctions
{

    /**
     * Replace PHP tag
     * 
     * @param   string
     *
     * @return  string
     */
    public function replacePHP($render)
    {
        return str_replace('@phptag', '<?php', $render);
    }

    /**
     * Get current namespace
     * 
     * @return  string
     */
    public function getNamespace()
    {
        return Container::getInstance()->getNamespace();
    }

   
    public function formatSingular($tbl)
    {
        $replace = camel_case($tbl);
        return str_singular(ucwords($replace));
    }

    /**
     * @param  [type]
     * @return [type]
     */
    public function getUniqueField($request)
    {

        $unique = [];
        foreach ($request['columns'] as $type):
            if ($type['relation']):
                $unique[] = ucfirst($type['relation']);
            else:
                $unique[] = $type['field'];
            endif;
        endforeach;

        return array_unique($unique);
    }

    /**
     * [arrayToFakeArray description]
     * @param  [type] $arr [description]
     * @return [type]      [description]
     */
    public function arrayToFakeArray($arr)
    {
        return "'" . implode("', '", $arr) . "'";
    }

    /**
     * [checkIfFileExist description]
     * @param  [type] $namespace [description]
     * @param  [type] $file      [description]
     * @return [type]            [description]
     */
    public function checkIfFileExist($namespace, $file)
    {
        $replace = str_replace('\\', '/', $namespace);
        return file_exists(base_path() . '/' . $replace . $file . '.php');
    }

    /**
     * [novaPath description]
     * @param  [type] $resource [description]
     * @param  [type] $ext      [description]
     * @return [type]           [description]
     */
    public function novaPath($resource, $ext = null)
    {
        return 'Nova/' . $resource . $ext;
    }

    /**
     * [modelPath description]
     * @param  [type] $namespace [description]
     * @param  [type] $model     [description]
     * @param  [type] $ext       [description]
     * @return [type]            [description]
     */
    public function modelPath($namespace, $model, $ext = null)
    {
        $replace = str_replace('\\', '/', $namespace);
        return $replace . '/' . $model . $ext;
    }

    /**
     * [checkOrCreateFolder description]
     * @param  [type] $path [description]
     * @return [type]       [description]
     */
    public function checkOrCreateFolder($path)
    {
        $folder = base_path() . '/' . str_replace('\\', '/', $path);
        File::isDirectory($folder) or File::makeDirectory($folder, 0777, true, true);
        return $folder;
    }

}
