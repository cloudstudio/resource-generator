<?php

namespace Cloudstudio\ResourceGenerator\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Cloudstudio\ResourceGenerator\Http\Services\Settings;

class ResourceGeneratorOptionsController extends Controller
{
    /**
     * @var mixed
     */
    protected $settings;

    /**
     * @param Settings $settings
     */
    public function __construct(Settings $settings)
    {
        $this->settings = $settings;
    }

    /**
     * @param Settings $settings
     */
    public function getSettings()
    {
        return response()->json($this->settings->getSettings());
    }

    /**
     * @param Settings $settings
     */
    public function setSettings(Request $request)
    {
        if (! $request->has('settings')) {
            return response()->json(['message' => 'Error'], 404);
        }

        $newSettings = $request->get('settings');

        foreach ($newSettings as $key => $option) {
            $this->settings->set($key, $option['value']);
        }

        if (! $this->settings->saveSettings()) {
            return response()->json(['message' => 'Error'], 404);
        }

        return response()->json(['success' => true]);
    }

    /**
     * Set default settings.
     *
     * @return  response
     */
    public function resetDefault()
    {
        if ($this->settings->setDefaultSettings()) {
            return response()->json(['success' => true]);
        }

        return response()->json(['message' => 'Error'], 404);
    }
}
