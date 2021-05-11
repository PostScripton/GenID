<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group([
    'prefix' => 'indicators',
    'as' => 'indicators',
], function () {
    Route::get('{id}', 'IndicatorsController@show')
        ->where('id', '[0-9]+');

    Route::post('/', 'IndicatorsController@store');
});

Route::fallback(function () {
    return response()->json(['error' => 'Route Not Found'], 404);
})->name('api.fallback.404');