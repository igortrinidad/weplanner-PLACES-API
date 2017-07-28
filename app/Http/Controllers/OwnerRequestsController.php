<?php

namespace App\Http\Controllers;

use App\Repositories\OwnerRequestDocumentRepository;
use App\Repositories\PlaceDocumentRepository;
use App\Repositories\PlaceRepository;
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
    /**
     * @var PlaceRepository
     */
    private $placeRepository;
    /**
     * @var PlaceDocumentRepository
     */
    private $placeDocumentRepository;

    /**
     * OwnerRequestsController constructor.
     * @param OwnerRequestRepository $repository
     * @param PlaceRepository $placeRepository
     * @param PlaceDocumentRepository $placeDocumentRepository
     * @param OwnerRequestDocumentRepository $ownerRequestDocumentRepository
     * @param OwnerRequestValidator $validator
     */
    public function __construct(
        OwnerRequestRepository $repository,
        PlaceRepository $placeRepository,
        PlaceDocumentRepository $placeDocumentRepository,
        OwnerRequestDocumentRepository $ownerRequestDocumentRepository,
        OwnerRequestValidator $validator
    )
    {
        $this->repository = $repository;
        $this->validator  = $validator;
        $this->placeRepository = $placeRepository;
        $this->placeDocumentRepository = $placeDocumentRepository;
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->repository->pushCriteria(app('Prettus\Repository\Criteria\RequestCriteria'));

        $ownerRequests = $this->repository->with(['place', 'user'])->paginate(10);

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
                'data' => $ownerRequest->load('documents', 'place', 'user'),
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

    /**
     * Cancel the specified owner request.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     *
     */
    public function cancel(Request $request)
    {
        $owner_request = $this->repository->find($request->get('id'));

        $owner_request->canceled = true;
        $owner_request->save();

        if (request()->wantsJson()) {

            return response()->json([
                'message' => 'OwnerRequest canceled.',
                'data' => $owner_request->load('documents', 'place', 'user'),
            ]);
        }

        return redirect()->back()->with('message', 'OwnerRequest canceled.');
    }

    /**
     * Cancel the specified owner request.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     *
     */
    public function confirm(Request $request)
    {
        $owner_request = $this->repository->find($request->get('id'))->load('documents', 'place', 'user');
        $place = $this->placeRepository->find($owner_request->place_id);

        //transfer the place to the user
        $place->user_id = $owner_request->user_id;
        $place->save();

        // copy the file to place documents
        foreach ($owner_request->documents as $document){

            $new_file_path = 'places/documents/'.basename($document->path);

            if(! \Storage::disk('media')->has($new_file_path )) {
                \Storage::disk('media')->copy($document->path, $new_file_path );

                $new_document = $document->toArray();
                $new_document['path'] = $new_file_path;
                $new_document['place_id'] = $place->id;

                $this->placeDocumentRepository->create($new_document);
            }
        }

        //save the owner request
        $owner_request->confirmed = true;
        $owner_request->save();

        if (request()->wantsJson()) {

            return response()->json([
                'message' => 'OwnerRequest confirmed.',
                'data' => $owner_request->load('documents', 'place', 'user'),
            ]);
        }

        return redirect()->back()->with('message', 'OwnerRequest confirmed.');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function adminList()
    {

        $ownerRequests = $this->repository->scopeQuery(function ($query){
            return $query->where('user_id', \Auth::user()->id);
        })->with(['place'])->paginate(10);

        return response()->json($ownerRequests);
    }
}
