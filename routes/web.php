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

/*
|--------------------------------------------------------------------------
| PasteLink web application v1 (BETA)
| Created by: https://github.com/superXdev
| Created at: 08/25/2020
|--------------------------------------------------------------------------
|
| Thank you for using this web application which is a personal poject for
| learning laravel.
|
| This is a web application that allows users to anonymously save text and
| links with several customizable options.
|
*/

// Show home page
Route::view('/', 'welcome')->name('home');

// Show submit page and for processing information to database
Route::get('/submit', 'LinkController@index')->name('submit');
Route::post('/store', 'LinkController@store')->name('submit.store');

// Authentication process
Route::post('/auth', 'LinkController@auth')->name('auth');
// Show edit menu
Route::get('/{link:slug}/edit', 'LinkController@edit')->middleware('verify.edit')->name('edit');
// Update process
Route::patch('/{link:slug}/update', 'LinkController@update')->name('edit.update');

// Show about page
Route::view('/about', 'about')->name('about');

// Show content for every URL
Route::get('/{link:slug}', 'LinkController@get')->name('get');

