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

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
Route::get('contact/create-new', 'HomeController@contactCreateForm')->name('contact.create.form');

Route::post('contact/create-new', 'ContactController@createContact')->name('contact.create');
Route::get('contact/delete-contact/{contact}', 'ContactController@deleteContact')->name('contact.delete');


