<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Requests;

use App\Models\Post;
use App\Models\Place;


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

        return view('landing.index', compact('posts'));

    }

    /**
     * Lista empresas
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function list_index(Request $request)
    {

        $places = Place::where('name', 'LIKE', '%'.$request->query('name') . '%')->where('city', 'LIKE', '%'.$request->query('city') . '%')->orderBy('featured_position', 'DESC')->paginate(16);

        return view('landing.companies.list', compact('places'));

    }

    /**
     * Index
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function show_public($slug)
    {

        $place = Place::where('slug', $slug)->first();

        return view('landing.companies.show', compact('place'));

    }

    /**
     * SIGNUP
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function signup()
    {

        return view('signup.index');

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

        /**
     * Index teste
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function sendSignupForm(Request $request)
    {
        //Email
        $data = [];
        $data['align'] = 'left';


        $nome = $request['name'];
        $cpf = $request['cpf'];
        $address = $request['address'];
        $phone = $request['phone'];
        $email = $request['email'];
        $plan = $request['plan_selected'];
        $company_name = $request['company_name'];

        $messageOne =
        '<p><b>Nome completo: </b>' . $nome . '</p>
        <p><b>CPF: </b>' .  $cpf . '</p>
        <p><b>Endereço: </b>' . $address . '</p>
        <p><b>Telefone: </b>' . $phone . '</p>
        <p><b>Email: </b>' . $email . '</p>
        <p><b>Plano: </b>' . $plan . '</p>
        <p><b>Nome empresa: </b>' . $company_name . '</p>';

        $data['messageTitle'] = 'CADASTRO WE PLACES';
        $data['messageOne'] = $messageOne;
        $data['button_link'] = 'https://app.weplaces.com.br/#/oracle/login';
        $data['button_name'] = 'Acesse o Oracle Agora';
        $data['messageSubject'] = 'WE PLACES CADASTRO: ' . $plan;

        \Mail::send('emails.standart-with-btn',['data' => $data], function ($message) use ($data){
            $message->from('no-reply@weplaces.com.br', 'Landing We Places');
            $message->to('comercial@weplaces.com.br', 'We Places')->subject($data['messageSubject']);
            $message->to('jessica.santos@weplaces.com.br', 'We Places')->subject($data['messageSubject']);
            $message->to('nathan.borem@weplaces.com.br', 'We Places')->subject($data['messageSubject']);
        });

        return redirect('/parabens');

    }

    /**
     * CONGRATULATION PAGE
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function congrats()
    {

        return view('signup.congrats.index');

    }

}
