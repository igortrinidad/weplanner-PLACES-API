<?php

namespace App\Http\Controllers;


use App\Repositories\AdvertiserRepository;
use App\Repositories\ClientRepository;
use App\Repositories\OracleUserRepository;
use App\Repositories\OwnerRequestRepository;
use App\Repositories\ServiceAdRepository;
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
     * @var OracleUserRepository
     */
    private $oracleUserRepository;
    /**
     * @var OwnerRequestRepository
     */
    private $ownerRequestRepository;
    /**
     * @var AdvertiserRepository
     */
    private $advertiserRepository;
    /**
     * @var ServiceAdRepository
     */
    private $adRepository;

    /**
     * OracleController constructor.
     * @param PlaceRepository $placeRepository
     * @param ClientRepository $clientRepository
     * @param UserRepository $userRepository
     * @param OracleUserRepository $oracleUserRepository
     * @param OwnerRequestRepository $ownerRequestRepository
     * @param AdvertiserRepository $advertiserRepository
     * @param ServiceAdRepository $adRepository
     */
    public function __construct(
        PlaceRepository $placeRepository,
        ClientRepository $clientRepository,
        UserRepository $userRepository,
        OracleUserRepository $oracleUserRepository,
        OwnerRequestRepository $ownerRequestRepository,
        AdvertiserRepository $advertiserRepository,
        ServiceAdRepository $adRepository
    )
    {
        $this->placeRepository = $placeRepository;
        $this->clientRepository = $clientRepository;
        $this->userRepository = $userRepository;
        $this->oracleUserRepository = $oracleUserRepository;
        $this->ownerRequestRepository = $ownerRequestRepository;
        $this->advertiserRepository = $advertiserRepository;
        $this->adRepository = $adRepository;
    }

    /**
     * Display a listing of the places.
     *
     * @return \Illuminate\Http\Response
     */
    public function placesList(Request $request)
    {

        $places = $this->placeRepository->scopeQuery(function ($query) use ($request) {
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
            ->load('photos', 'documents', 'reservations', 'tracking', 'calendar_settings', 'user', 'videos')
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
            ->scopeQuery(function ($query) use ($request) {
                return $query->where('confirmed', '=', $request->get('confirmed'))
                    ->where('name', 'LIKE', '%' . $request->get('therm') . '%')
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
        foreach ($trashed_place->photos as $photo) {
            \Storage::disk('media')->delete($photo->path);
            $photo->delete();
        }

        //Remove Documents
        foreach ($trashed_place->documents as $document) {
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
        $oracles = $this->oracleUserRepository->all()->count();

        $owner_requests = $this->ownerRequestRepository->all()->count();
        $advertisers = $this->advertiserRepository->all()->count();
        $service_ads = $this->adRepository->all()->count();

        $data = [
            'confirmed_places' => $confirmed_places,
            'unconfirmed_places' => $unconfirmed_places,
            'trashed_places' => $trashed_places,
            'clients' => $clients,
            'admins' => $admins,
            'oracles' => $oracles,
            'owner_requests' => $owner_requests,
            'advertisers' => $advertisers,
            'service_ads' => $service_ads
        ];

        if (request()->wantsJson()) {

            return response()->json($data);
        }
    }

    public function filter(Request $request)
    {

        $places = $this->placeRepository->scopeQuery(function ($query) use ($request) {

            return $query->where('confirmed', $request->get('confirmed'))->where(function ($query) use ($request) {

                foreach ($request->get('filters') as $key => $value) {

                    if ($key === 'name' && $value) {
                        return $query->where('name', 'LIKE', '%' . $value . '%');
                    }

                    if ($key === 'city' && $value) {
                        $query->where($key, 'LIKE', '%' . $value . '%');
                    }

                    if ($key === 'has_owner' && $value) {
                        $query->orWhere('user_id', '<>', null);
                    }

                    if ($key === 'has_not_owner' && $value) {
                        $query->orWhere('user_id', null);
                    }

                    if ($key === 'is_active' && $value) {

                        $query->orWhere('is_active', true);
                    }

                    if ($key === 'is_not_active' && $value) {
                        $query->orWhere('is_active', false);
                    }

                    if($value && $key != 'has_owner' && $key != 'has_not_owner' && $key != 'is_active' && $key != 'is_not_active' && $key != 'name' && $key != 'city'){
                        $query->where($key, $value);
                    }

                }

                $query->whereIn('plan', $request->get('plans'));

            });

        })->orderBy($request->get('order_by'), $request->get('direction'))->paginate(10);

        if (request()->wantsJson()) {

            return response()->json($places);
        }
    }

    public function filterTrackingUpdated(Request $request){

        $places = $this->placeRepository->makeModel()->where(function ($query)  use($request){

            return $query->where('confirmed', $request->get('confirmed'))->where(function ($query) use ($request) {

                foreach($request->get('filters') as $key => $value){

                    if ($key === 'name' && $value) {
                        return $query->where($key, 'LIKE', '%' . $value . '%');
                    }

                    if ($key === 'city' && $value) {
                        $query->where($key, 'LIKE', '%' . $value . '%');
                    }

                    if ($key === 'has_owner' && $value) {
                        $query->orWhere('user_id', '<>', null);
                    }

                    if ($key === 'has_not_owner' && $value) {
                        $query->orWhere('user_id', null);
                    }

                    if ($key === 'is_active' && $value) {

                        $query->orWhere('is_active', true);
                    }

                    if ($key === 'is_not_active' && $value) {
                        $query->orWhere('is_active', false);
                    }

                    if($value && $key != 'has_owner' && $key != 'has_not_owner' && $key != 'is_active' && $key != 'is_not_active'){
                        $query->where($key, $value);
                    }

                }

                $query->whereIn('plan', $request->get('plans'));

            });

        })->join('place_trackings', 'place_trackings.place_id', '=', 'places.id')
            ->where('reference', \Carbon\Carbon::now()->startOfMonth())
            ->select('places.*', 'place_trackings.views as views')
            ->orderBy('place_trackings.views', 'DESC')
            ->paginate(10);

        if (request()->wantsJson()) {

            return response()->json($places);
        }
    }


}
