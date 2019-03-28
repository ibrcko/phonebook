<?php

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::group(['prefix' => 'contacts', 'as' => 'contacts.'], function () {
    Route::get('/search', 'API\ContactController@search')->middleware('APIkey')->name('search');
    Route::post('/{contact}/photo', 'API\ContactController@updateImage')->middleware('APIkey')->name('photo.update');

    Route::group(['prefix' => 'favourite', 'as' => 'favourite.'], function () {
        Route::get('/', 'API\ContactController@favourite')->middleware('APIkey')->name('index');
        Route::get('/search', 'API\ContactController@searchFavourite')->middleware('APIkey')->name('search');
    });
});

Route::middleware('APIkey')->group(function () {
    Route::resource('contacts', 'API\ContactController');
});

Route::middleware('APIkey')->group(function () {
    Route::resource('phone-numbers', 'API\PhoneNumberController');
});

