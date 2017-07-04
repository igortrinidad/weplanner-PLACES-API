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

    	if(count($place->photos)){

            $arr = $place->photos->toArray();

            $item = reset($arr);
            $photo = $item['photo_url'];

    	} else {
    		$photo = 'https://s3.amazonaws.com/weplanner-places-assets/img/presentation_1.png';
    	}

    	//dd($place);

        if($place){


                $html = '
                    <!DOCTYPE html>
                    <html>
                        <head>
                            <meta property="fb:app_id" content="151705885358217" />
                            <meta property="og:locale" content="pt_BR">
                            <meta property="og:site_name" content="We Places App">
                            <meta property="og:title" content="We Places: ' . $place['name'] .'" />
                            <meta property="og:url" content="https://weplaces.com.br">
                            <meta property="og:description" content="' . strip_tags($place['description']) .'" />
                            <meta property="og:image" content="' . $photo .'" />
                            <meta property="og:image:url" content="' . $photo .'" />
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
                        <meta property="fb:app_id" content="151705885358217" />
                        <meta property="og:locale" content="pt_BR">
                        <meta property="og:site_name" content="We Places App">
                        <meta property="og:title" content="We Places App" />
                        <meta property="og:url" content="https://weplaces.com.br">
                        <meta property="og:description" content="Encontre o espaço de sua cerimônia ou festa em um só local." />
                        <meta property="og:image" content="https://s3.amazonaws.com/weplanner-places-assets/img/presentation_1.png" />
                        <meta property="og:image:url" content="https://s3.amazonaws.com/weplanner-places-assets/img/presentation_1.png" />
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