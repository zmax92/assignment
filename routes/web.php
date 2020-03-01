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

Route::get('/', 'BookController@index');
Route::get('/create', 'BookController@create');
Route::post('/', 'BookController@store');
Route::delete('/delete/{bookId}', 'BookController@destroy');
Route::patch('/{bookId}', 'BookController@update');
Route::get('/download', 'BookController@download');
