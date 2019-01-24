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

Route::get('/', 'PageController@home');
Route::post('/', 'PostController@store');
Route::get('/api/station-info/{ip_address}', 'PageController@stationInfo');
Route::get('/api/edge-width/{src_ip}/{dst_ip}', 'PageController@edgeWidth');
Route::get('/api/edge-info/{edgeInfo}', 'PageController@edgeInfo');