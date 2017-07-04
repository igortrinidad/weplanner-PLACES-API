<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Models\Place;



class ShareController extends Controller
{
	/**
     * Get place to show
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function placeShow($place_slug)
    {

    	$place = Place::whereSlug($place_slug)->with('photos')->first();

    	if(is_array($place->photos)){
    		$photo = $place->photos[0]['photo_url'];
    	} else {
    		$photo = 'https://s3.amazonaws.com/weplanner-places-assets/img/presentation_1.png';
    	}

    	//dd($place);

        if($place){


                $html = '
                    <!DOCTYPE html>
                    <html>
                        <head>
                            <meta property="og:app_id" content="210359702307953" />
                            <meta property="og:title" content="We Places: ' . $place['name'] .'" />
                            <meta property="og:description" content="' . strip_tags($place['description']) .'" />
                            <meta property="og:image" content="' . $place['photo_url'] .'" />
                        </head>
                    <body style="text-align:center;font-family:sans;">
                        <h1>' . $place['name'] .'</h1>
                        <p>' . $place['description'] .'</p>
                        <img src="' . $photo  .'" width="70%">
                        <script>
                            setTimeout(function(){
                                window.location.replace("https://weplaces.com.br/#/lista/'. $place['city'] . '/' . $place['slug'] .'");
                            },50)
                        </script>
                    </body>
                    </html>';

                    return $html;
        } else {
            $html = '
                <!DOCTYPE html>
                <html>
                    <head>
                        <meta property="og:app_id" content="210359702307953" />
                        <meta property="og:title" content="We Places App" />
                        <meta property="og:description" content="Encontre o espaço de sua cerimônia ou festa em um só local." />
                        <meta property="og:image" content="https://s3.amazonaws.com/weplanner-places-assets/img/presentation_1.png" />
                    </head>
                <body style="text-align:center;font-family:sans;">
                    <h1>Aplicativo We Places</h1>
                    <p>Encontre o espaço de sua cerimônia ou festa em um só local.</p>
                    <img width="70%">
                    <script>
                        setTimeout(function(){
                            window.location.replace("https://weplaces.com.br");
                        },50)
                    </script>
                </body>
                </html>';

                return $html;
        }

    }
}