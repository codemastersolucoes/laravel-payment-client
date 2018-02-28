<?php

Route::group(['middleware' => 'web', 'as' => 'payment', 'prefix' => 'payment',
    'namespace' => 'Payments\Client\Http\Controllers'], function() {

    Route::post('/', 'ReturnController@receive');
});
