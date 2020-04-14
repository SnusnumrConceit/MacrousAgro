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
    return view('layouts.admin');
});

//Auth::routes();
//
//Route::get('/home', 'HomeController@index')->name('home');


/** Admin Routes */

Route::get('admin/{any}', function () {
    return view('layouts.admin');
})->where('any', '.*');

Route::get('{any}', function () {
    return view('welcome');
})->where('any', '.*');
