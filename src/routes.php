<?php
Route::group(['middleware' => ['web'], 'prefix' => 'easy-admin', 'namespace' => 'DevsRyan\LaravelEasyAdmin\Controllers'], function() {
    
    //Auth Routes
    Route::get('/login', 'AuthController@show')->name('easy-admin-login');
    Route::post('login', 'AuthController@login');
    Route::post('logout', 'AuthController@logout')->name('easy-admin-logout');
    
    //EasyAdmin Routes
    Route::get('/', 'AdminController@home');
    Route::get('{model}/index', 'AdminController@index');
    Route::get('{model}/create', 'AdminController@create');
    Route::post('{model}', 'AdminController@store');
    Route::get('{model}/{id}/edit', 'AdminController@edit');
    Route::put('{model}/{id}', 'AdminController@update');
    Route::delete('{model}/{id}', 'AdminController@destroy');
});
