<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Requests;

use App\Models\Post;


class LandingController extends Controller
{


    /**
     * Index
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $posts = Post::where('post_status', 'publish')
            ->where('comment_status', 'open')
            ->limit(5)
            ->orderBy('post_date', 'desc')
            ->get();

        return view('landing.index', compact('posts'));

    }

    /**
     * Index teste
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function sendLandingContactForm(Request $request)
    {
        //Email
        $data = [];
        $data['align'] = 'left';

        $data['messageTitle'] = 'Formulário Landing We Places';
        $data['messageOne'] = 'Nome: ' . $request['name'];
        $data['messageTwo'] = 'Email: ' . $request['email'];
        $data['messageThree'] = 'Assunto: ' . $request['subject'];
        $data['messageFour'] = 'Mensagem: ' . $request['message'];
        $data['messageSubject'] = 'We Places: Formulário landing recebido';

        \Mail::send('emails.standart-with-btn',['data' => $data], function ($message) use ($data){
            $message->from('no-reply@weplaces.com.br', 'Landing We Places');
            $message->to('comercial@weplaces.com.br', 'We Places')->subject($data['messageSubject']);
        });

        return 'Mensagem enviada com sucesso';

    }

    
}

