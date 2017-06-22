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
        $oracleUsers = $this->repository->all();

        if (request()->wantsJson()) {

            return response()->json([
                'data' => $oracleUsers,
            ]);
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
        $oracleUser = $this->repository->find($id);

        if (request()->wantsJson()) {

            return response()->json([
                'data' => $oracleUser,
            ]);
        }

        return view('oracleUsers.show', compact('oracleUser'));
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
    public function update(OracleUserUpdateRequest $request, $id)
    {

        try {

            $this->validator->with($request->all())->passesOrFail(ValidatorInterface::RULE_UPDATE);

            $oracleUser = $this->repository->update($request->all(), $id);

            $response = [
                'message' => 'OracleUser updated.',
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
}
