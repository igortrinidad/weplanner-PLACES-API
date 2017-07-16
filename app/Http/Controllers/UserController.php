<?php

namespace App\Http\Controllers;

use App\Repositories\UserRepository;
use App\Serializers\DataArraySerializer;
use App\Transformers\UserTransformer;
use Illuminate\Http\Request;
use League\Fractal\Pagination\IlluminatePaginatorAdapter;

class UserController extends Controller
{

    /**
     * @var UserRepository
     */
    protected $repository;

    /**
     * UserController constructor.
     * @param UserRepository $repository
     */
    public function __construct(UserRepository $repository){
        $this->repository = $repository;
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $limit = $request->get('limit');

        $paginator = $this->repository->paginate($limit);
        $users = $paginator->getCollection();

        if (request()->wantsJson()) {

            return response()->json([
                'data' => $users,
            ]);
        }

        return view('clients.index', compact('users'));

    }

    /**
     * Show the form for creating a new resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $user = $this->repository->create( $request->all() );

        return fractal()
            ->item($user, new UserTransformer(), 'user')
            ->serializeWith(new DataArraySerializer())
            ->toArray();
    }

    /**
     * Display the specified resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = $this->repository->with(['places', 'created_by'])->find($id);

        if (request()->wantsJson()) {

            return response()->json([
                'data' => $user,
            ]);
        }

        return view('oracleUsers.show', compact('user'));
    }

    /**
     * Generate new Password to the user and send the email for him.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function generateNewPass($email)
    {
        $user = $this->repository->findWhere(['email' => $email])->first();

        if(!$user){
            return response()->json(['alert' => ['type' => 'success', 'title' => 'Atenção!', 'message' => 'Email não localizado', 'status_code' => 404]], 404);
        }

        $pass = substr(md5(time()), 0, 6);

        $user = $this->repository->update([
                'password' => $pass
            ], $user->id);

        //Email
        $data = [];
        $data['full_name'] = $user->full_name;
        $data['user_email'] = $user->email;

        $data['messageTitle'] = 'Olá, ' . $user->full_name;
        $data['messageOne'] = 'Alguém solicitou recentemente uma alteração na senha da sua conta do We Places.';
        $data['messageTwo'] = 'Caso não tenha sido você, acesse sua conta vinculada a este email e altere a senha para sua segurança.';
        $data['messageThree'] = 'Nova senha:';
        $data['button_link'] = 'https://weplaces.com.br';
        $data['button_name'] = $pass;
        $data['messageFour'] = 'Para manter sua conta segura, não encaminhe este e-mail para ninguém.';
        $data['messageSubject'] = 'Alteração de senha We Places';

        \Mail::send('emails.standart-with-btn',['data' => $data], function ($message) use ($data){
            $message->from('no-reply@we-planner.com', 'We Places');
            $message->to($data['user_email'], $data['full_name'])->subject($data['messageSubject']);
        });

        if(!count(\Mail::failures())) {
            return response()->json(['alert' => ['type' => 'success', 'title' => 'Atenção!', 'message' => 'Senha alterada com sucesso.', 'status_code' => 200]], 200);
        }

        if(count(\Mail::failures())){
            return response()->json(['alert' => ['type' => 'error', 'title' => 'Atenção!', 'message' => 'Ocorreu um erro ao enviar o e-mail.', 'status_code' => 500]], 500);
        }

        return view('users.show', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        if($request->has('current_password') && $request->has('current_password') != ''){

            $user = \Auth::user();

            if(!\Hash::check($request->get('current_password'), $user->password)){

                return response()->json(['error' => true, 'message' => 'Senha atual incorreta'], 200);
            }
        }

        $user = $this->repository->update($request->all(), $request->get('id'));

        return fractal()
            ->item($user->load('socialProviders'), new UserTransformer(), 'user')
            ->serializeWith(new DataArraySerializer())
            ->toArray();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $deleted = $this->repository->delete($id);

        if (request()->wantsJson()) {

            return response()->json([
                'message' => 'User deleted.',
                'deleted' => $deleted,
            ]);
        }

        return redirect()->back()->with('message', 'Client deleted.');
    }
}
