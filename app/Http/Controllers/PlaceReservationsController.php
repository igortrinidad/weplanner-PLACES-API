<?php

namespace App\Http\Controllers;

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

    public function __construct(PlaceReservationsRepository $repository, PlaceReservationsValidator $validator)
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

        $placeReservations = $this->repository->scopeQuery(function ($query) {
            return $query->where(['client_id' => \Auth::guard('client')->user()->id])->with('place');
        })->all();

        if (request()->wantsJson()) {

            return response()->json([
                'data' => $placeReservations,
            ]);
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

            $placeReservation = $this->repository->create($request->all());

            $response = [
                'message' => 'PlaceReservations created.',
                'data'    => $placeReservation->toArray(),
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
     * @param  string            $id
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
                'data'    => $placeReservation->toArray(),
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
        $reservation = $this->repository->update(['is_canceled' => true], $id);

        if (request()->wantsJson()) {

            return response()->json([
                'message' => 'PlaceReservations canceled.',
                'canceled' => $reservation,
            ]);
        }

        return redirect()->back()->with('message', 'PlaceReservations deleted.');
    }
}
