<?php

use Illuminate\Http\Request;

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

//Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//return $request->user();
//});

//Route::post('/login', 'Api\AuthController@login');
//Route::post('/register', 'Api\AuthController@register');
//Route::post('logout', 'Api\AuthController@logout');

Route::group(['namespace' => 'Guest'], function () {
    Route::get('category/{category}/products', 'CategoryController@products');

    Route::resource('categories', 'CategoryController')->only(['index']);

    Route::get('photos/random', 'PhotoController@random');
    Route::resource('photos', 'PhotoController')->only(['index']);

    Route::get('videos/random', 'VideoController@random');
    Route::resource('videos', 'VideoController')->only(['index']);

    Route::resource('articles', 'ArticleController')->only(['index', 'show']);

    \App\Models\Order::apiRoutes();

    Route::get('products/search', 'ProductController@index');
    Route::get('products/random', 'ProductController@random');
    Route::resource('products', 'ProductController')->only(['show']);
});

//Route::group(['is' => 'customer', 'namespace' => 'Guest', 'prefix' => 'cart'], function () {
////    Route::get('/cart/orders', 'OrderController@index');
//    Route::resource('orders', 'OrderController')->only(['index', 'store']);
//});

Route::group([
    'middleware' => 'auth:sanctum',
    'prefix' => 'admin',
    'is' => 'administrator|manager'
], function () {
    Route::get('/', function () {
        return view('layouts.admin');
    });

    Route::get('/categories/search', 'CategoryController@index');
    Route::post('/categories/store', 'CategoryController@store');
    Route::resource('categories', 'CategoryController');

    Route::get('/articles/search', 'ArticleController@index');
    Route::resource('articles', 'ArticleController');

    Route::group(['is' => 'administrator'], function () {
        \App\User::apiRoutes();
        Route::resource('users', 'UserController');
    });

    Route::post('videos/upload', 'VideoController@upload');
    Route::post('videos/remove_tmp_video', 'VideoController@removeContent');

    Route::resource('videos', 'VideoController');

    Route::post('photos/upload', 'PhotoController@upload');
    Route::post('photos/remove_tmp_photo', 'PhotoController@removeContent');
    Route::resource('photos', 'PhotoController');

    Route::post('products/remove_tmp_product', 'ProductController@removeContent');
    Route::resource('products', 'ProductController');

    Route::post('media/{media}/destroy', 'MediaController@destroy');
    Route::resource('media', 'MediaController')->only('store');

    Route::resource('roles', 'RoleController');

    Route::resource('permissions', 'PermissionController')->only(['index']);

//    Route::patch('/orders/{order_id}/items/{item_id}', 'OrderItemController@update')->name('orders.items.update');
    Route::patch('/order_items/{item_id}', 'OrderItemController@update')->name('orders.items.update');
    Route::post('/orders/export', 'OrderController@export')->name('orders.export');
    Route::resource('orders', 'OrderController');
});