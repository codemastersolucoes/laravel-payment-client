<?php

Route::group(['middleware' => ['api', \Barryvdh\Cors\HandleCors::class], 'as' => 'payment', 'prefix' => 'payment',
    'namespace' => 'Payments\Client\Http\Controllers'], function() {

    Route::post('/', 'ReturnController@receive');
});
