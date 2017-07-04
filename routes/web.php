<?php
use Illuminate\Http\Request;

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
    Route::get('/userregister', '\App\Http\Controllers\User\Auth\RegisterController@showRegistrationForm')->name('register');
});

Route::get('/', function () {
    $links = \App\Link::all();
    return view('welcome', ['links' => $links]);
});

Route::get('/submit', function () {
    return view('submit');
});

Route::post('/submit', function(Request $request) {
    $validator = Validator::make($request->all(), [
        'title' => 'required|max:255',
        'url' => 'required|max:255',
        'description' => 'required|max:255',
    ]);
    if ($validator->fails()) {
        return back()
            ->withInput()
            ->withErrors($validator);
    }
    $link = new \App\Link;
    $link->title = $request->title;
    $link->url = $request->url;
    $link->description = $request->description;
    $link->save();
    return redirect('/');
});

