<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Prettus\Validator\Contracts\ValidatorInterface;
use Prettus\Validator\Exceptions\ValidatorException;
use App\Http\Requests\OwnerRequestCreateRequest;
use App\Http\Requests\OwnerRequestUpdateRequest;
use App\Repositories\OwnerRequestRepository;
use App\Validators\OwnerRequestValidator;


class OwnerRequestsController extends Controller
{

    /**
     * @var OwnerRequestRepository
     */
    protected $repository;

    /**
     * @var OwnerRequestValidator
     */
    protected $validator;

    public function __construct(OwnerRequestRepository $repository, OwnerRequestValidator $validator)
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

        $ownerRequests = $this->repository->with(['documents', 'place', 'user'])->paginate(10);

        if (request()->wantsJson()) {

            return response()->json($ownerRequests);
        }

        return view('ownerRequests.index', compact('ownerRequests'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  OwnerRequestCreateRequest $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(OwnerRequestCreateRequest $request)
    {

        try {

            $this->validator->with($request->all())->passesOrFail(ValidatorInterface::RULE_CREATE);

            $ownerRequest = $this->repository->create($request->all());

            $response = [
                'message' => 'OwnerRequest created.',
                'data'    => $ownerRequest->load('documents')->toArray(),
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
        $ownerRequest = $this->repository->find($id);

        if (request()->wantsJson()) {

            return response()->json([
                'data' => $ownerRequest,
            ]);
        }

        return view('ownerRequests.show', compact('ownerRequest'));
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

        $ownerRequest = $this->repository->find($id);

        return view('ownerRequests.edit', compact('ownerRequest'));
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  ownerRequestUpdateRequest $request
     * @param  string            $id
     *
     * @return Response
     */
    public function update(OwnerRequestUpdateRequest $request)
    {

        try {

            $this->validator->with($request->all())->passesOrFail(ValidatorInterface::RULE_UPDATE);

            $ownerRequest = $this->repository->update($request->all(), $request->get('id'));

            $response = [
                'message' => 'OwnerRequest updated.',
                'data'    => $ownerRequest->load('documents')->toArray(),
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
                'message' => 'OwnerRequest deleted.',
                'deleted' => $deleted,
            ]);
        }

        return redirect()->back()->with('message', 'OwnerRequest deleted.');
    }
}
