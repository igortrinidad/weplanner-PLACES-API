<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;

use App\Http\Requests;
use Prettus\Validator\Contracts\ValidatorInterface;
use Prettus\Validator\Exceptions\ValidatorException;
use App\Repositories\PlaceRepository;


class OracleController extends Controller
{

    /**
     * @var PlaceRepository
     */
    protected $placeRepository;
    /**
     * @var PlaceValidator
     */

    /**
     * OracleController constructor.
     * @param PlaceRepository $placeRepository
     */
    public function __construct(PlaceRepository $placeRepository)
    {
        $this->placeRepository = $placeRepository;
    }

    /**
     * Display a listing of the places.
     *
     * @return \Illuminate\Http\Response
     */
    public function placesList(Request $request)
    {

        $places = $this->placeRepository->scopeQuery(function ($query) use ( $request) {
                return $query->where('confirmed', '=', $request->get('confirmed'))->orderBy('name', 'ASC');
            })->paginate(10);

        return response()->json($places);
    }

    /**
     * Display a place by ID.
     *
     * @param $id
     * @return \Illuminate\Http\Response
     */
    public function placeShow($id)
    {
        $place = $this->placeRepository->findWhere(['id' => $id])
            ->load('photos', 'documents', 'appointments', 'calendar_settings', 'user')
            ->first();


        if (request()->wantsJson()) {

            if ($place) {
                return response()->json([
                    'place' => $place,
                ]);
            }

            return response()->json([
                'data' => 'Record not found.',
            ], 404);
        }
    }


    /**
     * Place search by name.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function search(Request $request)
    {
        $places = $this->placeRepository
            ->scopeQuery(function ($query) use ( $request) {
                return $query->where('name', 'LIKE', '%'.$request->get('therm').'%');
            })->with(['photos', 'documents', 'appointments', 'calendar_settings', 'user'])->paginate(10);

        if (request()->wantsJson()) {

            return response()->json($places);
        }
    }
}
