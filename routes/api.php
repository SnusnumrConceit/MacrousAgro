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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['prefix' => 'auth'], function () {
    Route::post('logout', 'AuthController@logout');
});

\App\Models\OrderStatusCode::apiRoutes();

Route::get('category/{category}/products', 'CategoryController@products');

Route::resource('categories', 'CategoryController')->only(['index']);

Route::resource('photos', 'PhotoController')->only(['index']);

Route::resource('videos', 'VideoController')->only(['index']);

Route::get('products/search', 'ProductController@index');
Route::resource('products', 'ProductController')->only(['show']);

Route::group([
    'prefix' => 'admin'
], function () {
    Route::get('/', function () {
        return view('layouts.admin');
    });

    Route::get('/categories/search', 'CategoryController@index');
    Route::post('/categories/store', 'CategoryController@store');
    Route::resource('categories', 'CategoryController');

    Route::get('/articles/search', 'ArticleController@index');
    Route::resource('articles', 'ArticleController');

    \App\User::apiRoutes();
    Route::resource('users', 'UserController');

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

    Route::resource('orders', 'OrderController');
});
