<?php
Route::group(['middleware' => ['web'], 'prefix' => env('EASY_ADMIN_BASE_URL', 'easy-admin'), 'namespace' => 'DevsRyan\LaravelEasyAdmin\Controllers'], function() {

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

Route::group(['middleware' => ['api'], 'prefix' => env('EASY_ADMIN_BASE_URL', 'easy-admin') . '/api', 'namespace' => 'DevsRyan\LaravelEasyAdmin\Controllers'], function() {

    //Image Api
    Route::get('/images', 'ImageApiController@index')->name('easy-admin-image-index');
    Route::post('/store', 'ImageApiController@store')->name('easy-admin-image-store');
    Route::get('/images/{id}', 'ImageApiController@show')->name('easy-admin-image-show');
    Route::patch('/images/{id}', 'ImageApiController@update')->name('easy-admin-image-update');
    Route::delete('/images/{id}', 'ImageApiController@destroy')->name('easy-admin-image-delete');
});
