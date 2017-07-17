<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;



class TestController extends Controller
{
	/**
     * Get place to show
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function testEmailWithSend($template, $email)
    {

        \Mail::send($template, [], function ($message) use ($email, $template){
            $message->from('no-reply@weplaces.com.br', 'We Places');
            $message->to($email)->subject('Teste de template de email: ' . $template);
        });

        return 'Email enviado com sucesso';

    }
}