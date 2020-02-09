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

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::group([
    'prefix' => 'admin'
], function () {
    Route::get('/categories/search', 'CategoryController@index');
    Route::post('/categories/store', 'CategoryController@store');
    Route::resource('categories', 'CategoryController');

    Route::get('/articles/search', 'ArticleController@index');
    Route::resource('articles', 'ArticleController');

    Route::get('/users/search', 'UserController@index');
    Route::resource('users', 'UserController');

    Route::post('videos/upload', 'VideoController@upload');
    Route::post('videos/remove_tmp_video', 'VideoController@removeContent');

    Route::resource('videos', 'VideoController');

    Route::post('photos/upload', 'PhotoController@upload');
    Route::post('photos/remove_tmp_photo', 'PhotoController@removeContent');
    Route::resource('photos', 'PhotoController');

});
