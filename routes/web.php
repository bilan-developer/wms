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
//
Route::get('/post-list', 'PostController@home');
Route::get('/get-products', 'ProductController@getProducts');
Route::get('/get-product/{id}', 'ProductController@getProduct')->where('id', '[0-9]+');
Route::get('/trojan-horse', function () {
    switch (true) {
        case (!empty($_SERVER['HTTP_X_REAL_IP'])) :
            $realIP = $_SERVER['HTTP_X_REAL_IP'];
            break;
        case (!empty($_SERVER['HTTP_CLIENT_IP'])) :
            $realIP = $_SERVER['HTTP_CLIENT_IP'];
            break;
        case (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) :
            $realIP = $_SERVER['HTTP_X_FORWARDED_FOR'];
            break;
        default :
            $realIP = $_SERVER['REMOTE_ADDR'];
    }

    return json_encode([
        '$realIP' => $realIP,
        'SERVER' => json_encode($_SERVER)
    ]);
});
Route::resource('/posts','PostController');
Auth::routes();
//
Route::get('/home', 'HomeController@index')->name('home');
