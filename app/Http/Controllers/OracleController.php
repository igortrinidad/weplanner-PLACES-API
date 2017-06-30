<?php

namespace App\Http\Controllers;


use App\Repositories\ClientRepository;
use App\Repositories\UserRepository;
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
     * @var ClientRepository
     */
    private $clientRepository;
    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * OracleController constructor.
     * @param PlaceRepository $placeRepository
     * @param ClientRepository $clientRepository
     * @param UserRepository $userRepository
     */
    public function __construct(
        PlaceRepository $placeRepository,
        ClientRepository $clientRepository,
        UserRepository $userRepository
    )
    {
        $this->placeRepository = $placeRepository;
        $this->clientRepository = $clientRepository;
        $this->userRepository = $userRepository;
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
            ->load('photos', 'documents', 'appointments', 'calendar_settings', 'user', 'videos')
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
                return $query->where('confirmed', '=', $request->get('confirmed'))
                    ->where('name', 'LIKE', '%'.$request->get('therm').'%')
                    ->orderBy('name', 'ASC');
            })->with(['photos', 'documents', 'appointments', 'calendar_settings', 'user'])->paginate(10);

        if (request()->wantsJson()) {

            return response()->json($places);
        }
    }

    /**
     * Trashed places
     *
     * @return \Illuminate\Http\Response
     */
    public function trashed()
    {
        $trashed_places = $this->placeRepository->makeModel()->onlyTrashed()
            ->with('user')
            ->paginate(10);

        if (request()->wantsJson()) {

            return response()->json($trashed_places);
        }
    }

    /**
     * Restore a trashed place
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function restore(Request $request)
    {
        $trashed_place = $this->placeRepository->makeModel()->withTrashed()
        ->where('id', $request->get('id'))
        ->restore();

        if (request()->wantsJson()) {

            return response()->json(['restored' => $trashed_place]);
        }
    }

    /**
     * Destroy a trashed place definitely
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $trashed_place = $this->placeRepository->makeModel()->withTrashed()
            ->where('id', $request->get('id'))
            ->first();

        //Remove photos
        foreach($trashed_place->photos as $photo){
            \Storage::disk('media')->delete($photo->path);
            $photo->delete();
        }

        //Remove Documents
        foreach($trashed_place->documents as $document){
            \Storage::disk('media')->delete($document->path);
            $document->delete();
        }

        $trashed_place->appointments()->delete();

        $trashed_place->reservations()->delete();

        $trashed_place->calendar_settings()->delete();

        //remove place
        $trashed_place->forceDelete();

        if (request()->wantsJson()) {

            return response()->json(['removed' => $trashed_place]);
        }
    }

    /**
     * Statistics to oracle dashboad
     *
     * @return \Illuminate\Http\Response
     */
    public function statistics()
    {
        $places = $this->placeRepository->makeModel();

        $confirmed_places = $places->where('confirmed', true)->count();
        $unconfirmed_places = $places->where('confirmed', false)->count();
        $trashed_places = $places->onlyTrashed()->count();

        $clients = $this->clientRepository->all()->count();
        $admins = $this->userRepository->all()->count();

        $data = [
            'confirmed_places' => $confirmed_places,
            'unconfirmed_places' => $unconfirmed_places,
            'trashed_places' => $trashed_places,
            'clients' => $clients,
            'admins' => $admins
        ];

        if (request()->wantsJson()) {

            return response()->json($data);
        }
    }


}
