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
    Route::get('/categories/search', 'CategoryController@store');
    Route::post('/categories/create', 'CategoryController@create');
    Route::resource('categories', 'CategoryController');

    Route::get('/news/search', 'NewsController@store');
    Route::post('/news/create', 'NewsController@create');
    Route::resource('news', 'NewsController');

    Route::get('/users/search', 'UserController@store');
    Route::post('/users/create', 'UserController@create');
    Route::resource('users', 'UserController');

    Route::resource('videos', 'VideoController');

    Route::resource('photos', 'PhotoController');

});
