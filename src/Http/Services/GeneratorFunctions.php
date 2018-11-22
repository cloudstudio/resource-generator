<?php

namespace Cloudstudio\ResourceGenerator\Http\Services;

use Cloudstudio\ResourceGenerator\Http\Services\Settings;
use File;
use Illuminate\Container\Container;

trait GeneratorFunctions
{

    /**
     * @var mixed
     */
    protected $setting;

    public function __construct(Settings $setting)
    {
        $this->setting = $setting;
    }

    /**
     * Replace PHP tag.
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
     * Get current namespace.
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
                $unique[] = ucfirst($type['relation']);else:
                $unique[] = $type['field'];
            endif;
        endforeach;

        return array_unique($unique);
    }

    /**
     * [arrayToFakeArray description].
     * @param  [type] $arr [description]
     * @return [type]      [description]
     */
    public function arrayToFakeArray($arr)
    {
        return "'" . implode("', '", $arr) . "'";
    }

    /**
     * [checkIfFileExist description].
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
     * [novaPath description].
     * @param  [type] $resource [description]
     * @param  [type] $ext      [description]
     * @return [type]           [description]
     */
    public function novaPath($resource, $ext = null)
    {
        $setting  = new Settings;
        $novaPath = $setting->value('resource');
        $replace  = str_replace('\\', '/', $novaPath);

        return $replace . '/' . $resource . $ext;
    }

    /**
     * [modelPath description].
     * @param  [type] $namespace [description]
     * @param  [type] $model     [description]
     * @param  [type] $ext       [description]
     * @return [type]            [description]
     */
    public function modelPath($namespace, $model, $ext = null)
    {
        return $this->namespaseToDirInApp($namespace) . '/' . $model . $ext;
    }

    /**
     * [checkOrCreateFolder description].
     * @param  [type] $path [description]
     * @return [type]       [description]
     */
    public function checkOrCreateFolder($path)
    {
        $folder = $this->namespaseToDirInApp($path);
        File::isDirectory($folder) or File::makeDirectory($folder, 0777, true, true);

        return $folder;
    }

    /**
     * [namespaseToDirInApp description].
     * @param  [type] $namespace [description]
     * @return [type]            [description]
     */
    protected function namespaseToDirInApp($namespace)
    {
        $spaces = explode('\\', $namespace);
        array_shift($spaces);

        return app_path(implode('/', $spaces));
    }
}
