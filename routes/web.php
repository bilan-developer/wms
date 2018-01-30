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
Route::get('/get-products', 'ProductController@getProducts');
Route::post('pay', 'ProductController@pay');
Route::get('/home', 'HomeController@index')->name('home');

Route::get('/get-user', function () {
    return \Illuminate\Support\Facades\Auth::user();
});

Route::resource('/product','ProductController');
Route::resource('/purchase','PurchaseController');
Auth::routes();



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

    return response()
        ->json([
            '$realIP' => $realIP,
//        'SERVER' => json_encode($_SERVER)
        ])
        ->withCallback(request()->input('callback'));
});
