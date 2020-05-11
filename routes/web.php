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

//Route::group([
//    'middleware' => 'auth:sanctum',
//    'prefix' => 'admin',
//    'is' => 'administrator|manager'
//], function () {
//    Route::get('/', function () {
//        return view('layouts.admin');
//    });
//
//    Route::get('/categories/search', 'CategoryController@index');
//    Route::post('/categories/store', 'CategoryController@store');
//    Route::resource('categories', 'CategoryController');
//
//    Route::get('/articles/search', 'ArticleController@index');
//    Route::resource('articles', 'ArticleController');
//
//    Route::group(['is' => 'administrator'], function () {
//        \App\User::apiRoutes();
//        Route::resource('users', 'UserController');
//    });
//
//    Route::post('videos/upload', 'VideoController@upload');
//    Route::post('videos/remove_tmp_video', 'VideoController@removeContent');
//
//    Route::resource('videos', 'VideoController');
//
//    Route::post('photos/upload', 'PhotoController@upload');
//    Route::post('photos/remove_tmp_photo', 'PhotoController@removeContent');
//    Route::resource('photos', 'PhotoController');
//
//    Route::post('products/remove_tmp_product', 'ProductController@removeContent');
//    Route::resource('products', 'ProductController');
//
//    Route::post('media/{media}/destroy', 'MediaController@destroy');
//    Route::resource('media', 'MediaController')->only('store');
//
//    Route::resource('roles', 'RoleController');
//
//    Route::resource('permissions', 'PermissionController')->only(['index']);
//
////    Route::patch('/orders/{order_id}/items/{item_id}', 'OrderItemController@update')->name('orders.items.update');
//    Route::patch('/order_items/{item_id}', 'OrderItemController@update')->name('orders.items.update');
//    Route::post('/orders/export', 'OrderController@export')->name('orders.export');
//    Route::resource('orders', 'OrderController');
//});

/** Admin Routes */

Route::group(['is' => 'admin|manager'], function () {
    Route::get('admin/{any}', function () {
        return view('layouts.admin');
    })->where('any', '.*');
});

Route::get('{any}', function () {
    return view('welcome');
})->where('any', '.*');