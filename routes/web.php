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

Route::get('/', 'LandingController@index');
Route::get('/cadastre-se/{choosed_payment}', 'LandingController@signup');
Route::get('/parabens', 'LandingController@congrats');
Route::post('/sendLandingContactForm', 'LandingController@sendLandingContactForm');
Route::post('/sendSignupForm', 'LandingController@sendSignupForm');

Route::group(['prefix' => 'buscar', 'as' => 'companies.'], function () {

    Route::get('', 'LandingController@list_index');
    Route::get('/{slug}', 'LandingController@show_public');

});

Route::get('/web/test-email/{template}', function ($template) {
    return view($template);
});


Route::get('/web/test-email-data/place/{id}', 'TestController@testEmailData');

Route::get('/web/test-email-with-send/{template}/{email}', 'TestController@testEmailWithSend');

/*
 * Auth
 */
Route::group(['prefix' => 'share'], function () {

    Route::get('/places/{place_slug}', 'ShareController@placeShow');

});
