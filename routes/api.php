<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Tool API Routes
|--------------------------------------------------------------------------
|
| Here is where you may register API routes for your tool. These routes
| are loaded by the ServiceProvider of your tool. They are protected
| by your tool's "Authorize" middleware by default. Now, go build!
|
 */
Route::any('/get-tables', \Cloudstudio\ResourceGenerator\Http\Controllers\ResourceGeneratorController::class.'@getTables');
Route::any('/get-columns', \Cloudstudio\ResourceGenerator\Http\Controllers\ResourceGeneratorController::class.'@getColumns');
Route::post('/check-file', \Cloudstudio\ResourceGenerator\Http\Controllers\ResourceGeneratorController::class.'@checkFile');
Route::post('/generate-resource', \Cloudstudio\ResourceGenerator\Http\Controllers\ResourceGeneratorController::class.'@generateFile');

Route::get('/settings-get', \Cloudstudio\ResourceGenerator\Http\Controllers\ResourceGeneratorOptionsController::class.'@getSettings');
Route::post('/settings-set', \Cloudstudio\ResourceGenerator\Http\Controllers\ResourceGeneratorOptionsController::class.'@setSettings');
Route::post('/settings-reset', \Cloudstudio\ResourceGenerator\Http\Controllers\ResourceGeneratorOptionsController::class.'@resetDefault');
