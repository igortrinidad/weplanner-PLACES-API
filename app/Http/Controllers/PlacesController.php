<?php

namespace App\Http\Controllers;

use App\Repositories\PlacePhotoRepository;
use App\Validators\PlaceValidator;
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
    /**
     * @var PlaceValidator
     */
    private $validator;
    /**
     * @var PlacePhotoRepository
     */
    private $photoRepository;


    /**
     * PlacesController constructor.
     * @param PlaceRepository $repository
     * @param PlaceValidator $validator
     * @param PlacePhotoRepository $photoRepository
     */
    public function __construct(PlaceRepository $repository, PlaceValidator $validator, PlacePhotoRepository $photoRepository)
    {
        $this->repository = $repository;
        $this->validator = $validator;
        $this->photoRepository = $photoRepository;
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
            $this->validator->with($request->all())->passesOrFail(ValidatorInterface::RULE_CREATE);

            $place = $this->repository->create($request->all());

            $response = [
                'message' => 'Place created.',
                'data' => $place->load('photos', 'documents')->toArray(),
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
            $this->validator->with($request->all())->passesOrFail(ValidatorInterface::RULE_UPDATE);

            //update photos
            if(array_key_exists('photos', $request->all())){
                foreach ($request->get('photos') as $photo){
                    $this->photoRepository->update($photo, $photo['id']);
                }
            }

            $place = $this->repository->update($request->all(), $request->get('id'));

            $response = [
                'message' => 'Place updated.',
                'data' => $place->load('photos', 'documents')->toArray(),
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
