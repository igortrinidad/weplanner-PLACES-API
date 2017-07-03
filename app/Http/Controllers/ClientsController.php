<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Prettus\Validator\Contracts\ValidatorInterface;
use Prettus\Validator\Exceptions\ValidatorException;
use App\Http\Requests\ClientCreateRequest;
use App\Http\Requests\ClientUpdateRequest;
use App\Repositories\ClientRepository;
use App\Validators\ClientValidator;


class ClientsController extends Controller
{

    /**
     * @var ClientRepository
     */
    protected $repository;

    /**
     * @var ClientValidator
     */
    protected $validator;

    public function __construct(ClientRepository $repository, ClientValidator $validator)
    {
        $this->repository = $repository;
        $this->validator  = $validator;
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->repository->pushCriteria(app('Prettus\Repository\Criteria\RequestCriteria'));
        $clients = $this->repository->all();

        if (request()->wantsJson()) {

            return response()->json([
                'data' => $clients,
            ]);
        }

        return view('clients.index', compact('clients'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  ClientCreateRequest $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(ClientCreateRequest $request)
    {

        try {

            $this->validator->with($request->all())->passesOrFail(ValidatorInterface::RULE_CREATE);

            $client = $this->repository->create($request->all());

            $response = [
                'message' => 'Client created.',
                'data'    => $client->toArray(),
            ];

            if ($request->wantsJson()) {

                return response()->json($response);
            }

            return redirect()->back()->with('message', $response['message']);
        } catch (ValidatorException $e) {
            if ($request->wantsJson()) {
                return response()->json([
                    'error'   => true,
                    'message' => $e->getMessageBag()
                ]);
            }

            return redirect()->back()->withErrors($e->getMessageBag())->withInput();
        }
    }


    /**
     * Display the specified resource.
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $client = $this->repository->find($id);

        if (request()->wantsJson()) {

            return response()->json([
                'data' => $client,
            ]);
        }

        return view('clients.show', compact('client'));
    }

    /**
     * Generate new Password to the user and send the email for him.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function generateNewPass($id)
    {
        $client = $this->repository->find($id);
        $pass = substr(md5(time()), 0, 6);

        $client = $this->repository->update([
                'password' => $pass
            ], $client->id);

        //Email
        $data = [];
        $data['full_name'] = $client->full_name;
        $data['user_email'] = $client->email;

        $data['messageTitle'] = 'Olá, ' . $client->full_name;
        $data['messageOne'] = 'Alguém solicitou recentemente uma alteração na senha da sua conta do Places We-Planner. ';
        $data['messageTwo'] = 'Caso não tenha sido você, acesse sua conta vinculada a este email e altere a senha para sua segurança.';
        $data['messageThree'] = 'Nova senha:';
        $data['button_link'] = 'https://places.we-planner.com';
        $data['button_name'] = $pass;
        $data['messageFour'] = 'Para manter sua conta segura, não encaminhe este e-mail para ninguém.';
        $data['messageSubject'] = 'Alteração de senha Places We-Planner';

        \Mail::send('emails.standart-with-btn',['data' => $data], function ($message) use ($data){
            $message->from('no-reply@we-planner.com', 'Places We-Planner');
            $message->to($data['user_email'], $data['full_name'])->subject($data['messageSubject']);
        });

        if(!count(\Mail::failures())) {
            return response()->json(['alert' => ['type' => 'success', 'title' => 'Atenção!', 'message' => 'Senha alterada com sucesso.', 'status_code' => 200]], 200);
        }

        if(count(\Mail::failures())){
            return response()->json(['alert' => ['type' => 'error', 'title' => 'Atenção!', 'message' => 'Ocorreu um erro ao enviar o e-mail.', 'status_code' => 500]], 500);
        }

        return view('clients.show', compact('client'));
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

        $client = $this->repository->find($id);

        return view('clients.edit', compact('client'));
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  ClientUpdateRequest $request
     * @param  string            $id
     *
     * @return Response
     */
    public function update(ClientUpdateRequest $request)
    {

        try {

            $this->validator->with($request->all())->passesOrFail(ValidatorInterface::RULE_UPDATE);

            $user = \Auth::guard('client')->user();

            if ($request->has('current_password') && $request->has('current_password') != '') {

                if (!\Hash::check($request->get('current_password'), $user->password)) {

                    return response()->json(['error' => true, 'message' => 'Senha atual incorreta'], 200);
                }
            }

            $client = $this->repository->update($request->all(), $request->get('id'));

            $response = [
                'message' => 'Client updated.',
                'user'    => $client->load('socialProviders')->toArray(),
            ];

            if ($request->wantsJson()) {

                return response()->json($response);
            }

            return redirect()->back()->with('message', $response['message']);
        } catch (ValidatorException $e) {

            if ($request->wantsJson()) {

                return response()->json([
                    'error'   => true,
                    'message' => $e->getMessageBag()
                ]);
            }

            return redirect()->back()->withErrors($e->getMessageBag())->withInput();
        }
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $deleted = $this->repository->delete($id);

        if (request()->wantsJson()) {

            return response()->json([
                'message' => 'Client deleted.',
                'deleted' => $deleted,
            ]);
        }

        return redirect()->back()->with('message', 'Client deleted.');
    }
}
