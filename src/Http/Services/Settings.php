<?php

namespace Cloudstudio\ResourceGenerator\Http\Services;

class Settings
{
    /**
     * @var mixed
     */
    protected $settings;

    /**
     * @var mixed
     */
    protected $filePath;

    /**
     * @param $customPath
     */
    public function __construct($customPath = null)
    {
        $this->settings = $this->getSettingsFile();
        if ($customPath === null) {
            $this->filePath = storage_path('nova-resource-generator.json');
        } else {
            $this->filePath = $customPath;
        }
    }

    /**
     * Return all settings.
     *
     * @return json
     */
    public function getSettings()
    {
        return $this->settings;
    }

    /**
     * Get the value from the given name.
     *
     * @param   $name
     *
     * @return  mixed
     */
    public function value($name)
    {
        if ($this->checkOptionKey($name)) {
            return $this->getOptionValueKey($name);
        }

        return false;
    }

    /**
     * Set a value for given name.
     *
     * @param $name
     * @param $value
     */
    public function set($name, $value)
    {
        if ($this->checkOptionKey($name)) {
            $this->setValue($name, $value);
        }
    }

    /**
     * Save current options in the settings file.
     *
     * @return  bool
     */
    public function saveSettings()
    {
        $settings = json_encode($this->settings, JSON_PRETTY_PRINT);
        if (file_put_contents($this->filePath, stripslashes($settings)) !== false) {
            return true;
        }

        return false;
    }

    /**
     * Check if a key exists.
     *
     * @param $name
     */
    private function checkOptionKey($name)
    {
        if (property_exists($this->settings, $name)) {
            return true;
        }

        return false;
    }

    /**
     * Check and return a key value if exists.
     *
     * @param   name
     *
     * @return  mixed
     */
    private function getOptionValueKey($name)
    {
        if (property_exists($this->settings->{$name}, 'value')) {
            return $this->settings->{$name}->value;
        }

        return false;
    }

    /**
     * CSet a value for given name and value.
     *
     * @param   name
     * @param   value
     */
    private function setValue($name, $value)
    {
        if (is_string($value) && str_contains($value, '\\')) {
            $value = str_replace('\\', '\\\\', $value);
        }
        $this->settings->{$name}->value = $value;
    }

    /**
     * Get settings json contents.
     *
     * @return  [type]  [description]
     */
    private function getSettingsFile()
    {
        $this->filePath = storage_path('nova-resource-generator.json');

        if (file_exists($this->filePath)) {
            $jsonContents = file_get_contents($this->filePath);
            if (! $this->isJson($jsonContents)) {
                $this->setDefaultSettings();
            }
        } else {
            $this->setDefaultSettings();
        }

        $jsonContents = file_get_contents($this->filePath);
        $settings = json_decode($jsonContents, true);
        $settings = json_decode(json_encode($settings, JSON_FORCE_OBJECT), false);
        $settings = $this->cleanArrays($settings);

        return $settings;
    }

    /**
     * @param $settings
     */
    private function cleanArrays($settings)
    {
        foreach ($settings as $setting) {
            if ($setting->type == 'Multiple') {
                $setting->value = array_values((array) $setting->value);
            }
        }

        return $settings;
    }

    /**
     * Set default settings.
     */
    public function setDefaultSettings()
    {
        $settings = file_get_contents(__DIR__.'/../../../settings.json');

        return file_put_contents($this->filePath, $settings);
    }

    /**
     * @param $string
     */
    private function isJson($string)
    {
        json_decode($string);

        return json_last_error() == JSON_ERROR_NONE;
    }
}
