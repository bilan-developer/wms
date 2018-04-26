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

Route::get('/exmo', 'ExmoController@exmo');
Route::get('/candles ', 'ExmoController@candles');
Route::get('/exmo-show', 'ExmoController@index');

Route::resource('/product','ProductController')->middleware('boss');
Route::resource('/category','CategoryController')->middleware('boss');
Route::resource('/history','HistoryController')->middleware('boss');
Auth::routes();

Route::get('/', function () {
    return view('welcome');
});
Route::get('/logout', function () {
    Auth::logout();
});

Route::post('/write-off', 'ProductController@writeOff')->middleware('boss');;
Route::get('/write-off-list', 'ProductController@writeOffList')->middleware('boss');
Route::get('/show-write-off/{id}', 'ProductController@showWriteOff')->middleware('boss');
Route::get('/show-purchase/{id}', 'ProductController@showPurchase')->middleware('boss');
Route::get('/purchase', 'ProductController@purchase')->middleware('boss');


Route::get('/balance', 'ReportController@balance')->middleware('boss');
Route::get('/operations', 'OperationController@index')->middleware('boss');

Route::get('/course', 'AdapterController@course')->middleware('boss');

Route::get('/home', 'HomeController@index')->name('home');

Route::get('/get-user', function () {
    return \Illuminate\Support\Facades\Auth::user();
})->middleware('boss');





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
