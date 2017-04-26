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

        return fractal()
            ->collection($users, new UserTransformer(), 'users')
            ->serializeWith(new DataArraySerializer())
            ->paginateWith(new IlluminatePaginatorAdapter($paginator))
            ->toArray();

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
    public function show(Request $request)
    {
        $user = $this->repository->find($request->get('id'));

        return fractal()
            ->item($user, new UserTransformer(), 'user')
            ->serializeWith(new DataArraySerializer())
            ->toArray();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        if($request->has('current_password')){

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
    public function destroy(Request $request)
    {
       $destroy = $this->repository->delete($request->get('id'));

       if($destroy){
           return response()->json(['success' => $destroy]);
       }
    }
}
