<?php

namespace App\Http\Controllers;

use App\Repositories\ClientRepository;
use Carbon\Carbon;
use Illuminate\Http\Request;

use App\Http\Requests;
use Prettus\Validator\Contracts\ValidatorInterface;
use Prettus\Validator\Exceptions\ValidatorException;
use App\Http\Requests\PlaceReservationsCreateRequest;
use App\Http\Requests\PlaceReservationsUpdateRequest;
use App\Repositories\PlaceReservationsRepository;
use App\Validators\PlaceReservationsValidator;


class PlaceReservationsController extends Controller
{

    /**
     * @var PlaceReservationsRepository
     */
    protected $repository;

    /**
     * @var PlaceReservationsValidator
     */
    protected $validator;
    /**
     * @var ClientRepository
     */
    private $clientRepository;

    /**
     * PlaceReservationsController constructor.
     * @param PlaceReservationsRepository $repository
     * @param ClientRepository $clientRepository
     * @param PlaceReservationsValidator $validator
     */
    public function __construct(PlaceReservationsRepository $repository, PlaceReservationsValidator $validator, ClientRepository $clientRepository)
    {
        $this->repository = $repository;
        $this->validator = $validator;
        $this->clientRepository = $clientRepository;
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->repository->pushCriteria(app('Prettus\Repository\Criteria\RequestCriteria'));

        $placeReservations = $this->repository->scopeQuery(function ($query) {
            return $query->where(['client_id' => \Auth::guard('client')->user()->id]);
        })->with(['place' => function ($query) {
            $query->select('id', 'name', 'slug');
        }])->paginate(10);

        if (request()->wantsJson()) {

            return response()->json($placeReservations);
        }

        return view('placeReservations.index', compact('placeReservations'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  PlaceReservationsCreateRequest $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(PlaceReservationsCreateRequest $request)
    {

        try {

            $this->validator->with($request->all())->passesOrFail(ValidatorInterface::RULE_CREATE);

            //Check if the client exist
            if ($request->has('client')) {
                $client_data = $request->get('client');

                $client_exists = $this->clientRepository->findWhere(['email' => $client_data['email']])->first();

                if ($client_exists) {
                    $request->merge(['client_id' => $client_exists->id]);
                }

                if (!$client_exists) {
                    $new_client = $this->clientRepository->create($client_data);
                    $request->merge(['client_id' => $new_client->id]);
                }
            }

            $log = [
                'full_name' => \Auth::user()->full_name,
                'action' => 'create',
                'label' => 'Criou a reserva',
                'date' => Carbon::now()->format('Y-m-d H:i:s')
            ];
            
            $request->merge(['history' => [$log]]);

            $placeReservation = $this->repository->create($request->all());

            $response = [
                'message' => 'PlaceReservations created.',
                'reservation' => $placeReservation->load('place', 'client'),
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
     * Display the specified resource.
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $placeReservation = $this->repository->find($id);

        if (request()->wantsJson()) {

            return response()->json([
                'data' => $placeReservation,
            ]);
        }

        return view('placeReservations.show', compact('placeReservation'));
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

        $placeReservation = $this->repository->find($id);

        return view('placeReservations.edit', compact('placeReservation'));
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  PlaceReservationsUpdateRequest $request
     * @param  string $id
     *
     * @return Response
     */
    public function update(PlaceReservationsUpdateRequest $request, $id)
    {

        try {

            $this->validator->with($request->all())->passesOrFail(ValidatorInterface::RULE_UPDATE);

            $placeReservation = $this->repository->update($request->all(), $id);

            $response = [
                'message' => 'PlaceReservations updated.',
                'data' => $placeReservation->toArray(),
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
                'message' => 'PlaceReservations deleted.',
                'deleted' => $deleted,
            ]);
        }

        return redirect()->back()->with('message', 'PlaceReservations deleted.');
    }

    /**
     * Cancel the specified reservation.
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function cancel($id)
    {
        $reservation = $this->repository->makeModel()->find($id);

        $reservation->is_canceled = true;
        $reservation->canceled_at = Carbon::now();

        $reservation->is_confirmed = null;
        $reservation->confirmed_at = null;

        $history = $reservation->history;

        $log = [
            'full_name' => \Auth::user()->full_name,
            'action' => 'cancel',
            'label' => 'Cancelou a reserva',
            'date' => Carbon::now()->format('Y-m-d H:i:s')
        ];

        if (!$history) {
            $history = [$log];
        }else{
            $history =  array_prepend($history, $log);
        }

        $reservation->history = $history;

        $reservation->save();

        if (request()->wantsJson()) {

            return response()->json([
                'message' => 'PlaceReservations canceled.',
                'canceled' => $reservation->load('client'),
            ]);
        }

        return redirect()->back()->with('message', 'PlaceReservations deleted.');
    }

    /**
     * Place reservations paginated list
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function reservationsList(Request $request)
    {
        $id = $request->get('id');
        $search = $request->get('search');

        $placeReservations = $this->repository->scopeQuery(function ($query) use ($id) {
            return $query->where(['place_id' => $id, 'is_pre_reservation' => false])->orderBy('date', 'ASC');
        })->with('client')->whereHas('client', function($q) use ($search){
            $q->where('name', 'LIKE', '%'.$search.'%');
            $q->orWhere('last_name', 'LIKE', '%'.$search.'%');
        })->paginate(10);

        if (request()->wantsJson()) {

            return response()->json($placeReservations);
        }
    }

    /**
     * Place pré reservations paginated list
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function PreReservationsList(Request $request)
    {

        $id = $request->get('id');
        $search = $request->get('search');

        $placeReservations = $this->repository->scopeQuery(function ($query) use ($id) {
            return $query->where(['place_id' => $id, 'is_pre_reservation' => true])->orderBy('date', 'ASC');
        })->with('client')->whereHas('client', function($q) use ($search){
            $q->where('name', 'LIKE', '%'.$search.'%');
            $q->orWhere('last_name', 'LIKE', '%'.$search.'%');
        })->all();

        if (request()->wantsJson()) {

            return response()->json($placeReservations);
        }
    }


    /**
     * Place Reservations
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     *
     */
    public function monthReservationsPublic(Request $request)
    {

        $reservations = $this->repository->scopeQuery(function ($query) use ($request) {
            return $query->where(['place_id' => $request->get('place_id'), 'is_confirmed' => true])
                ->whereBetween('date', [$request->get('start'), $request->get('end')])
                ->select('date', 'all_day')
                ->orderBy('date', 'ASC');
        })->all();


        if (request()->wantsJson()) {

            return response()->json($reservations);
        }
    }

    /**
     * Confirm the specified reservation.
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function confirm($id)
    {
        $reservation = $this->repository->makeModel()->find($id);

        $reservation->is_confirmed = true;
        $reservation->confirmed_at = Carbon::now();

        $reservation->is_canceled = null;
        $reservation->canceled_at = null;

        $history = $reservation->history;

        $log = [
            'full_name' => \Auth::user()->full_name,
            'action' => 'confirm',
            'label' => 'Confirmou a reserva',
            'date' => Carbon::now()->format('Y-m-d H:i:s')
        ];


        if (!$history) {
            $history = [$log];
        }else{
            $history =  array_prepend($history, $log);
        }


        $reservation->history = $history;

        $reservation->save();

        if (request()->wantsJson()) {

            return response()->json([
                'message' => 'PlaceReservations canceled.',
                'confirmed' => $reservation->load('client'),
            ]);
        }

        return redirect()->back()->with('message', 'PlaceReservations confirmed.');
    }

    /**
     * Reservations by month.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function monthReservations(Request $request)
    {

        $reservations = $this->repository->scopeQuery(function ($query) use ($request) {
            return $query->where('place_id', $request->get('place_id'))
                ->whereBetween('date', [$request->get('start'), $request->get('end')])
                ->orderBy('date', 'ASC');
        })->with('client')->all();

        if (request()->wantsJson()) {

            return response()->json([
                'reservations' => $reservations,
            ]);
        }

        return view('placeReservations.index', compact('placeReservations'));
    }

    /**
     * All client reservations.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function clientReservationsList(Request $request)
    {

        $reservations = $this->repository->scopeQuery(function ($query) use ($request) {
            return $query->where('client_id', \Auth::user()->id)
                ->select('place_id', 'date', 'is_pre_reservation', 'is_confirmed', 'is_canceled')
                ->orderBy('date', 'ASC');
        })->all();

        return response()->json($reservations);
    }
}
