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

Route::get('/', ['as' => 'landing.index', 'uses' => 'LandingController@index']);
Route::get('/cadastre-se/{choosed_payment}', 'LandingController@signup');
Route::get('/parabens', 'LandingController@congrats');
Route::post('/sendLandingContactForm', 'LandingController@sendLandingContactForm');
Route::post('/sendSignupForm', 'LandingController@sendSignupForm');

Route::get('/buscar', ['as' => 'search', 'uses' => 'LandingController@list_index']);

Route::group(['prefix' => 'espacos', 'as' => 'places.'], function () {

    Route::get('/{slug}', ['as' => 'show', 'uses' => 'LandingController@show_public']);

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


Route::get('sitemap', function(){

    // create new sitemap object
    $sitemap = App::make("sitemap");

    $root = \Request::root();

    // set cache key (string), duration in minutes (Carbon|Datetime|int), turn on/off (boolean)
    // by default cache is disabled
    //$sitemap->setCache('laravel.sitemap', 60);

    // check if there is cached sitemap and build new only if is not
    if (!$sitemap->isCached())
    {
        // add item to the sitemap (url, date, priority, freq)
        $sitemap->add($root . '', \Carbon\Carbon::now(), '1.0', 'monthly');
        $sitemap->add($root . '/buscar', \Carbon\Carbon::now(), '1.0', 'monthly');

        $places = \App\Models\Place::all();

        foreach($places as $place){

            $photos = [];
            foreach ($place->photos as $photo) {
                $photos[] = [
                    'url' => $photo->photo_url,
                    'title' => 'Imagem de '. $place->name ,
                    'caption' => 'Imagem de '. $place->name
                ];
            }

            $sitemap->add($root . '/espacos/'. $place->slug, \Carbon\Carbon::now(), '1.0', 'daily', $photos);
        }

        $cities = \App\Models\Place::select('city')->groupBy('city')->get();

        foreach($cities as $city){
            $sitemap->add($root . '/buscar?city='. $city->city, \Carbon\Carbon::now(), '1.0', 'daily');
        }

    }

    return $sitemap->render('xml');


    //Generate and store the xml file
    /*$sitemap->store('xml', 'sitemap');

    //Send the new sitemap to Google
    $url = 'http://www.google.com/webmasters/sitemaps/ping?sitemap='.\Config('app.url').'/sitemap.xml';
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);*/
});
