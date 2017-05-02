<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Prettus\Validator\Contracts\ValidatorInterface;
use Prettus\Validator\Exceptions\ValidatorException;
use App\Http\Requests\PlaceCreateRequest;
use App\Http\Requests\PlaceUpdateRequest;
use App\Repositories\PlaceRepository;


class PlacesController extends Controller
{

    /**
     * @var PlaceRepository
     */
    protected $repository;


    public function __construct(PlaceRepository $repository)
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
        $places = $this->repository->findWhere(['user_id' => \Auth::user()->id]);

        return response()->json($places);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  PlaceCreateRequest $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(PlaceCreateRequest $request)
    {

        try {

            $place = $this->repository->create($request->all());

            $response = [
                'message' => 'Place created.',
                'data' => $place->toArray(),
            ];

            if ($request->wantsJson()) {

                return response()->json($response);
            }

            return redirect()->back()->with('message', $response['message']);
        } catch (ValidatorException $e) {
            if ($request->wantsJson()) {
                return response()->json([
                    'error' => true,
                    'message' => $e->getMessageBag()
                ]);
            }

            return redirect()->back()->withErrors($e->getMessageBag())->withInput();
        }
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  PlaceUpdateRequest $request
     * @param  string $id
     *
     * @return Response
     */
    public function update(PlaceUpdateRequest $request)
    {

        try {
            $place = $this->repository->update($request->all(), $request->get('id'));

            $response = [
                'message' => 'Place updated.',
                'data' => $place->toArray(),
            ];

            if ($request->wantsJson()) {

                return response()->json($response);
            }

            return redirect()->back()->with('message', $response['message']);
        } catch (ValidatorException $e) {

            if ($request->wantsJson()) {

                return response()->json([
                    'error' => true,
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
                'message' => 'Place deleted.',
                'deleted' => $deleted,
            ]);
        }

        return redirect()->back()->with('message', 'Place deleted.');
    }
}
