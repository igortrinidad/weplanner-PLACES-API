<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Prettus\Validator\Contracts\ValidatorInterface;
use Prettus\Validator\Exceptions\ValidatorException;
use App\Http\Requests\DecorationVideoCreateRequest;
use App\Http\Requests\DecorationVideoUpdateRequest;
use App\Repositories\DecorationVideoRepository;



class DecorationVideosController extends Controller
{

    /**
     * @var DecorationVideoRepository
     */
    protected $repository;

    /**
     * @var DecorationVideoValidator
     */
    protected $validator;

    public function __construct(DecorationVideoRepository $repository)
    {
        $this->repository = $repository;
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->repository->pushCriteria(app('Prettus\Repository\Criteria\RequestCriteria'));
        $decorationVideos = $this->repository->all();

        if (request()->wantsJson()) {

            return response()->json([
                'data' => $decorationVideos,
            ]);
        }

        return view('decorationVideos.index', compact('decorationVideos'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  DecorationVideoCreateRequest $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(DecorationVideoCreateRequest $request)
    {

        try {

            $decorationVideo = $this->repository->create($request->all());

            $response = [
                'message' => 'DecorationVideo created.',
                'data'    => $decorationVideo->toArray(),
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
        $decorationVideo = $this->repository->find($id);

        if (request()->wantsJson()) {

            return response()->json([
                'data' => $decorationVideo,
            ]);
        }

        return view('decorationVideos.show', compact('decorationVideo'));
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

        $decorationVideo = $this->repository->find($id);

        return view('decorationVideos.edit', compact('decorationVideo'));
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  DecorationVideoUpdateRequest $request
     * @param  string            $id
     *
     * @return Response
     */
    public function update(DecorationVideoUpdateRequest $request)
    {

        try {

            $decorationVideo = $this->repository->update($request->all(), $request->get('id'));

            $response = [
                'message' => 'DecorationVideo updated.',
                'data'    => $decorationVideo->toArray(),
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
                'message' => 'DecorationVideo deleted.',
                'deleted' => $deleted,
            ]);
        }

        return redirect()->back()->with('message', 'DecorationVideo deleted.');
    }
}
