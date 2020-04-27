<?php
Route::group(['middleware' => ['web'], 'prefix' => 'easy-admin', 'namespace' => 'Raysirsharp\LaravelEasyAdmin\Controllers'], function() {
    Route::get('/', 'AdminController@home');
	Route::get('{model}/index', 'AdminController@index');
    Route::get('{model}/create', 'AdminController@create');
    Route::post('{model}', 'AdminController@store');
    Route::get('{model}/{id}/edit', 'AdminController@edit');
    Route::put('{model}/{id}', 'AdminController@update');
    Route::delete('{model}/{id}', 'AdminController@destroy');
});