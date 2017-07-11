<?php

namespace App\Http\Controllers;

use App\Repositories\PlaceCalendarSettingsRepository;
use App\Repositories\PlacePhotoRepository;
use App\Validators\PlaceValidator;
use Illuminate\Http\Request;

use App\Http\Requests;
use Prettus\Validator\Contracts\ValidatorInterface;
use Prettus\Validator\Exceptions\ValidatorException;
use App\Http\Requests\PlaceCreateRequest;
use App\Http\Requests\PlaceUpdateRequest;
use App\Repositories\PlaceRepository;

use App\Models\Place;


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
     * @var PlaceCalendarSettingsRepository
     */
    private $calendarSettingsRepository;


    /**
     * PlacesController constructor.
     * @param PlaceRepository $repository
     * @param PlaceValidator $validator
     * @param PlacePhotoRepository $photoRepository
     * @param PlaceCalendarSettingsRepository $calendarSettingsRepository
     */
    public function __construct(PlaceRepository $repository,
                                PlaceValidator $validator,
                                PlacePhotoRepository $photoRepository,
                                PlaceCalendarSettingsRepository $calendarSettingsRepository
    )
    {
        $this->repository = $repository;
        $this->validator = $validator;
        $this->photoRepository = $photoRepository;
        $this->calendarSettingsRepository = $calendarSettingsRepository;
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $places = $this->repository->scopeQuery(function ($query) {
            return $query->where(['user_id' => \Auth::user()->id])->with('photos');
        })->orderBy('name', 'ASC')->paginate(10);

        return response()->json($places);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function createdBy()
    {
        $places = $this->repository->scopeQuery(function ($query) {
            return $query->where('list_common', true)->where(['created_by_id' => \Auth::user()->id])->with('photos');
        })->orderBy('name', 'ASC')->paginate(10);

        return response()->json($places);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function createdByClient()
    {
        $places = $this->repository->scopeQuery(function ($query) {
            return $query->where('list_common', true)->where(['created_by_id' => \Auth::user()->id])->with('photos');
        })->orderBy('name', 'ASC')->paginate(10);

        return response()->json($places);
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
        $place = $this->repository->findWhere(['id' => $id, 'user_id' => \Auth::user()->id])
            ->load('photos', 'documents', 'calendar_settings', 'videos')
            ->first();

        if (request()->wantsJson()) {

            if($place){
                return response()->json([
                    'data' => $place,
                ]);
            }

            return response()->json([
                'data' => 'Record not found.',
            ], 404);
        }

        return view('posts.show', compact('post'));
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

            $request->merge([
                'created_by_id' => \Auth::user()->id,
                'created_by_type' => get_class(\Auth::user())
            ]);

            $this->validator->with($request->all())->passesOrFail(ValidatorInterface::RULE_CREATE);

            $place = $this->repository->create($request->all());

            $placeSettings = $this->calendarSettingsRepository->create(array_collapse([['place_id' => $place->id],$request->get('calendar_settings')]));

            $response = [
                'message' => 'Place created.',
                'data' => $place->load('photos', 'documents','appointments', 'calendar_settings', 'reservations', 'videos')->toArray(),
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
                'data' => $place->load('photos', 'documents','appointments', 'calendar_settings', 'reservations', 'videos')->toArray(),
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
                'place_id' => $id,
            ]);
        }

        return redirect()->back()->with('message', 'Place deleted.');
    }

    /**
     * Display the specified resource.
     *
     * @param $category_slug
     * @return \Illuminate\Http\Response
     */
    public function listByCategory(Request $request, $category_slug)
    {
        $per_page = 0;

        $category_slug = $category_slug === 'cerimonia' ? 'cerimony' : 'party_space';

        $request->get('per_page') ? $per_page = $request->get('per_page') : $per_page = 8;

        $places = $this->repository->scopeQuery(function ($query) use ($category_slug) {
            return $query->where([$category_slug => true]);
        })->orderBy('name', 'ASC')
            ->with(['photos'])->orderBy('name');

        $per_page == 'all' ? $places = $places->all() : $places = $places->paginate($per_page);


        if (request()->wantsJson()) {

            return response()->json($places);
        }

        return view('posts.show', compact('post'));
    }

    /**
     * Display the specified resource.
     *
     * @param $category_slug
     * @param $place_slug
     * @return \Illuminate\Http\Response
     */
    public function showPublic($place_slug)
    {

        $place = $this->repository->findWhere(['slug' => $place_slug])
            ->load('photos','calendar_settings', 'videos')
            ->first();

        if (request()->wantsJson()) {

            if($place){
                return response()->json([
                    'data' => $place,
                ]);
            }

            return response()->json([
                'data' => 'Record not found.',
            ], 404);
        }

        return view('posts.show', compact('post'));
    }

    /**
     * Display the specified resource.
     *
     * @param Request $request
     * @param $category_slug
     * @return \Illuminate\Http\Response
     */
    public function search(Request $request)
    {
        $per_page = 0;

        $request->get('per_page') ? $per_page = $request->get('per_page') : $per_page = 8;

        $places = $this->repository
            ->scopeQuery(function ($query) use ($request) {
                return $query->where(function ($query) use ($request) {
                        foreach ($request->all() as $key => $value) {
                            if ($key === 'city') {
                                $query->where($key, $value);
                            }

                            if ($key === 'max_guests') {
                                $query->where($key, '>=', $value);
                            }

                            if ($key != 'city' && $key != 'max_guests') {
                                $query->where($key,  $value === 'true' ? true: false);
                            }
                        }
                    });
            })->with(['photos'])->orderBy('name');

        $per_page == 'all' ? $places = $places->all() : $places = $places->paginate($per_page);

        if (request()->wantsJson()) {

            return response()->json($places);
        }
    }



    /**
     * Place search by name.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function nameSearch(Request $request)
    {
        $places = $this->repository
            ->scopeQuery(function ($query) use ( $request) {
                return $query->where('name', 'LIKE', '%'.$request->get('therm').'%');
            })->with(['photos'])->all();

        if (request()->wantsJson()) {

            return response()->json(['results' => $places]);
        }
    }

    /**
     * check place unique url.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function checkUrl(Request $request)
    {
        $has_place = $this->repository->findWhere(['slug' => $request->get('slug')])->first();

        if($has_place){
            return response()->json(['has_place' => true]);
        }

        return response()->json(['has_place' => false]);
    }

        /**
     * Display the specified resource.
     *
     * @param Request $request
     * @param $category_slug
     * @return \Illuminate\Http\Response
     */
    public function searchByCity(Request $request)
    {
        $per_page = 0;

        $request->get('per_page') ? $per_page = $request->get('per_page') : $per_page = 8;

        if($request['city'] == 'todas-cidades'){
           $request['city'] = '';
        }

        $places = $this->repository
            ->scopeQuery(function ($query) use ($request) {
                return $query->where('confirmed', true)->where(function ($query) use ($request) {
                        foreach ($request->all() as $key => $value) {

                            if($key === 'page'){
                                continue;
                            }

                            if ($key === 'max_guests') {
                                $query->where($key, '>=', $value);
                            }

                            if ($key === 'name' || $key === 'city') {
                                $query->where($key, 'LIKE', '%' . $value . '%');
                            }

                            if ($key != 'max_guests' && $key != 'name' && $value && $key != 'city' && $key != 'per_page') {
                                $query->where($key, $value);
                            }
                        }
                    });
            })->with(['photos'])->orderBy('name');


        $cerimonyCount = Place::where('confirmed', true)->where('cerimony', true)->where('city', 'LIKE', '%'. $request->get('city') .'%')->count();
        $partyCount = Place::where('confirmed', true)->where('party_space', true)->where('city', 'LIKE', '%'. $request->get('city') .'%')->count();

        $per_page == 'all' ? $places = $places->all() : $places = $places->paginate($per_page);

        if (request()->wantsJson()) {

            $data = new Class{};

            $data->cerimonyCount =  $cerimonyCount;
            $data->partyCount =  $partyCount;
            $data->places = $places;

            return response()->json($data);
        }
    }

    /**
     * Display the featured places.
     *
     * @return \Illuminate\Http\Response
     */
    public function searchByCityToMap(Request $request)
    {
        $places = Place::where('confirmed', true)->where('city', 'LIKE', '%'. $request->get('city') .'%')->select('id', 'address', 'name', 'city', 'slug')->get();

        if (request()->wantsJson()) {

            return response()->json($places);
        }
    }

    /**
     * Display the featured places.
     *
     * @return \Illuminate\Http\Response
     */
    public function featuredPlaces($category_slug)
    {
        $category_slug = $category_slug === 'cerimonia' ? 'cerimony' : 'party_space';

        $places = $this->repository->scopeQuery(function ($query) use ($category_slug) {
            return $query->where([$category_slug => true])
                ->where('confirmed', true)
                ->where('featured_position', '>', 0);;
        })->orderBy('name', 'ASC')
            ->with(['photos'])->orderBy('featured_position')->all();

        if (request()->wantsJson()) {

            return response()->json($places);
        }
    }

}
