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

Route::get('/web/test-email/{template}', function ($template) {
    return view($template);
});

Route::get('/web/test-email-with-send/{template}/{email}', 'TestController@testEmailWithSend');

/*
 * Auth
 */
Route::group(['prefix' => 'share'], function () {

    Route::get('/places/{place_slug}', 'ShareController@placeShow');

});
