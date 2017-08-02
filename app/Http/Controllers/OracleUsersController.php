<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Prettus\Validator\Contracts\ValidatorInterface;
use Prettus\Validator\Exceptions\ValidatorException;
use App\Http\Requests\OracleUserCreateRequest;
use App\Http\Requests\OracleUserUpdateRequest;
use App\Repositories\OracleUserRepository;
use App\Validators\OracleUserValidator;


class OracleUsersController extends Controller
{

    /**
     * @var OracleUserRepository
     */
    protected $repository;

    /**
     * @var OracleUserValidator
     */
    protected $validator;

    public function __construct(OracleUserRepository $repository, OracleUserValidator $validator)
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

        $oracleUsers = $this->repository->orderBy('name', 'asc')->paginate(10);

        if (request()->wantsJson()) {

            return response()->json($oracleUsers);
        }

        return view('oracleUsers.index', compact('oracleUsers'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  OracleUserCreateRequest $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(OracleUserCreateRequest $request)
    {

        try {

            $this->validator->with($request->all())->passesOrFail(ValidatorInterface::RULE_CREATE);

            $oracleUser = $this->repository->create($request->all());

            $response = [
                'message' => 'OracleUser created.',
                'data'    => $oracleUser->toArray(),
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
        $oracleUser = $this->repository->with(['created_by'])->find($id);

        if (request()->wantsJson()) {

            return response()->json([
                'data' => $oracleUser,
            ]);
        }

        return view('oracleUsers.show', compact('oracleUser'));
    }

     /**
     * Generate new Password to the user and send the email for him.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function generateNewPass($email)
    {
        $oracle = $this->repository->findWhere(['email' => $email])->first();

        if(!$oracle){
            return response()->json(['alert' => ['type' => 'success', 'title' => 'Atenção!', 'message' => 'Email não localizado', 'status_code' => 404]], 404);
        }

        $pass = substr(md5(time()), 0, 6);

        $oracle = $this->repository->update([
                'password' => $pass
            ], $oracle->id);

        //Email
        $data = [];
        $data['full_name'] = $oracle->full_name;
        $data['oracle_email'] = $oracle->email;

        $data['messageTitle'] = 'Olá, ' . $oracle->full_name;
        $data['messageOne'] = 'Alguém solicitou recentemente uma alteração na senha da sua conta do We Places.';
        $data['messageTwo'] = 'Caso não tenha sido você, acesse sua conta vinculada a este email e altere a senha para sua segurança.';
        $data['messageThree'] = 'Nova senha:';
        $data['button_link'] = 'https://weplaces.com.br';
        $data['button_name'] = $pass;
        $data['messageFour'] = 'Para manter sua conta segura, não encaminhe este e-mail para ninguém.';
        $data['messageSubject'] = 'Alteração de senha We Places';

        \Mail::send('emails.standart-with-btn',['data' => $data], function ($message) use ($data){
            $message->from('no-reply@weplaces.com.br', 'We Places');
            $message->to($data['oracle_email'], $data['full_name'])->subject($data['messageSubject']);
        });

        if(!count(\Mail::failures())) {
            return response()->json(['alert' => ['type' => 'success', 'title' => 'Atenção!', 'message' => 'Senha alterada com sucesso.', 'status_code' => 200]], 200);
        }

        if(count(\Mail::failures())){
            return response()->json(['alert' => ['type' => 'error', 'title' => 'Atenção!', 'message' => 'Ocorreu um erro ao enviar o e-mail.', 'status_code' => 500]], 500);
        }

        return view('oracles.show', compact('oracle'));
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

        $oracleUser = $this->repository->find($id);

        return view('oracleUsers.edit', compact('oracleUser'));
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  OracleUserUpdateRequest $request
     * @param  string            $id
     *
     * @return Response
     */
    public function update(OracleUserUpdateRequest $request)
    {

        try {

            $this->validator->with($request->all())->passesOrFail(ValidatorInterface::RULE_UPDATE);

            $user = \Auth::guard('oracle')->user();

            if ($request->has('current_password') && $request->has('current_password') != '') {

                if (!\Hash::check($request->get('current_password'), $user->password)) {

                    return response()->json(['error' => true, 'message' => 'Senha atual incorreta'], 200);
                }
            }

            $oracleUser = $this->repository->update($request->all(), $request->get('id'));

            $response = [
                'message' => 'OracleUser updated.',
                'user'    => $oracleUser->load('socialProviders')->toArray(),
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
                'message' => 'OracleUser deleted.',
                'deleted' => $deleted,
            ]);
        }

        return redirect()->back()->with('message', 'OracleUser deleted.');
    }

    public function search(Request $request)
    {
        $users = $this->repository->scopeQuery(function ($query) use ($request){
            return $query->where('name', 'LIKE', '%'.$request->get('search').'%')
                ->orWhere('last_name', 'LIKE', '%'.$request->get('search').'%')
                ->orderBy('name', 'asc');
        });

        return response()->json($users->paginate(10));
    }
}
