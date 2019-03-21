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
Route::get('/favourite', 'HomeController@favourite')->name('favourite');

Route::group(['prefix'=>'contact','as'=>'contact.'], function () {

    Route::get('create', 'ContactController@createContact')->name('create');
    Route::post('store', 'ContactController@storeContact')->name('store');
    Route::get('delete/{contact}', 'ContactController@deleteContact')->name('delete');
    Route::get('show/{contact}', 'ContactController@showContact')->name('show');
    Route::get('edit/{contact}', 'ContactController@editContact')->name('edit');
    Route::post('update/{contact}', 'ContactController@updateContact')->name('update');

    Route::group(['prefix'=>'phone-number','as'=>'phone-number.'], function () {
        Route::get('create/{contact}', 'PhoneNumberController@createPhoneNumber')->name('create');
        Route::post('store/{contact}', 'PhoneNumberController@storePhoneNumber')->name('store');
        Route::get('delete/{contact}', 'PhoneNumberController@deletePhoneNumber')->name('delete');
    });
});



