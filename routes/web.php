<?php

use Illuminate\Support\Facades\Route;

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

Route::view('/', 'welcome')->name('home');


Route::get('/submit', 'LinkController@index')->name('submit');
Route::post('/store', 'LinkController@store')->name('submit.store');

Route::post('/auth', 'LinkController@auth')->name('auth');
Route::get('/{link:slug}/edit', 'LinkController@edit')->middleware('verify.edit')->name('edit');
Route::patch('/{link:slug}/update', 'LinkController@update')->name('edit.update');

Route::view('/about', 'about')->name('about');

Route::get('/{link:slug}', 'LinkController@get')->name('get');

