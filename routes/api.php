<?php

use Illuminate\Http\Request;

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

Route::middleware('APIkey')->group( function () {
    Route::resource('contacts', 'API\ContactController');
});

Route::middleware('APIkey')->group( function () {
    Route::resource('phone-numbers', 'API\PhoneNumberController');
});

Route::post('/contacts/{contact}/photo', 'API\ContactController@updateImage')->middleware('APIkey')->name('contacts.photo.update');
