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

Route::prefix('user')->namespace('User')->group(function () {
    Auth::routes();
    Route::get('/', 'HomeController@index');
    Route::get('/home', 'HomeController@index')->name('home');
});

Route::prefix('admin')->namespace('Admin')->group(function () {
    Auth::routes();
    Route::get('/', 'HomeController@index');
    Route::get('/home', 'HomeController@index')->name('home');
});

Route::get('/', function () {
    $links = \App\Link::all();
    return view('welcome', ['links' => $links]);
});

