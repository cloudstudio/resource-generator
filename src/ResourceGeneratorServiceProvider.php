<?php

namespace Cloudstudio\ResourceGenerator;

use Laravel\Nova\Nova;
use Laravel\Nova\Events\ServingNova;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Cloudstudio\ResourceGenerator\Http\Middleware\Authorize;

class ResourceGeneratorServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'resource-generator');

        $this->app->booted(function () {
            $this->routes();
        });

        $this->checkSettingsFile();

        Nova::serving(function (ServingNova $event) {
            //
        });
    }

    protected function routes()
    {
        if ($this->app->routesAreCached()) {
            return;
        }

        Route::middleware(['nova', Authorize::class])
            ->prefix('nova-vendor/resource-generator')
            ->group(__DIR__.'/../routes/api.php');
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Check Settings File.
     *
     * @return  void
     */
    private function checkSettingsFile()
    {
        $settingsFile = storage_path('nova-resource-generator.json');
        if (! file_exists($settingsFile)) {
            $settings = file_get_contents(__DIR__.'/../settings.json');

            file_put_contents($settingsFile, $settings);
        }
    }
}
