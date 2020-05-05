<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::group(['is' => 'customer'], function () {
    Route::get('/cart/orders', 'OrderController@getUserOrders');
    Route::resource('orders', 'OrderController')->only('store');
});

Route::group(['namespace' => 'Api'], function () {
    Route::post('/login', 'AuthController@login');
    Route::post('/register', 'AuthController@register');
    Route::post('/logout', 'AuthController@logout');
    Route::group(['middleware' => 'auth:sanctum'], function () {
        Route::get('/user', 'AuthController@user');
    });
});

//Auth::routes();
//
//Route::get('/home', 'HomeController@index')->name('home');


/** Admin Routes */

Route::group(['is' => 'admin|manager'], function () {
    Route::get('admin/{any}', function () {
        return view('layouts.admin');
    })->where('any', '.*');
});

Route::get('{any}', function () {
    return view('welcome');
})->where('any', '.*');
