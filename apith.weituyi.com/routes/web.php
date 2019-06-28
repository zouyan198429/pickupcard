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

Route::get('/test/geoHash', 'TestController@geoHash');// 测试
Route::get('/test/h3', 'TestController@h3');// 测试
Route::get('/test/s2', 'TestController@s2');// 测试

Route::get('/', function () {
    return view('welcome');
});

Route::get('/{token}', function ($token) {
    return view('welcome');
})->middleware('checktoken');

