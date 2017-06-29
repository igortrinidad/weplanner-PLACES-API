<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Prettus\Validator\Contracts\ValidatorInterface;
use Prettus\Validator\Exceptions\ValidatorException;
use App\Http\Requests\PlaceVideoCreateRequest;
use App\Http\Requests\PlaceVideoUpdateRequest;
use App\Repositories\PlaceVideoRepository;
use App\Validators\PlaceVideoValidator;


class PlaceVideosController extends Controller
{

    /**
     * @var PlaceVideoRepository
     */
    protected $repository;

    /**
     * @var PlaceVideoValidator
     */
    protected $validator;

    public function __construct(PlaceVideoRepository $repository, PlaceVideoValidator $validator)
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
        $placeVideos = $this->repository->all();

        if (request()->wantsJson()) {

            return response()->json([
                'data' => $placeVideos,
            ]);
        }

        return view('placeVideos.index', compact('placeVideos'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  PlaceVideoCreateRequest $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(PlaceVideoCreateRequest $request)
    {

        try {

            $this->validator->with($request->all())->passesOrFail(ValidatorInterface::RULE_CREATE);

            $placeVideo = $this->repository->create($request->all());

            $response = [
                'message' => 'PlaceVideo created.',
                'data'    => $placeVideo->toArray(),
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
        $placeVideo = $this->repository->find($id);

        if (request()->wantsJson()) {

            return response()->json([
                'data' => $placeVideo,
            ]);
        }

        return view('placeVideos.show', compact('placeVideo'));
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

        $placeVideo = $this->repository->find($id);

        return view('placeVideos.edit', compact('placeVideo'));
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  PlaceVideoUpdateRequest $request
     * @param  string            $id
     *
     * @return Response
     */
    public function update(PlaceVideoUpdateRequest $request)
    {

        try {

            $this->validator->with($request->all())->passesOrFail(ValidatorInterface::RULE_UPDATE);

            $placeVideo = $this->repository->update($request->all(), $request->get('id'));

            $response = [
                'message' => 'PlaceVideo updated.',
                'data'    => $placeVideo->toArray(),
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
                'message' => 'PlaceVideo deleted.',
                'deleted' => $deleted,
            ]);
        }

        return redirect()->back()->with('message', 'PlaceVideo deleted.');
    }
}
