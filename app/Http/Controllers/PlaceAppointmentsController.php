<?php

namespace App\Http\Controllers;

use App\Repositories\PlaceReservationsRepository;
use Illuminate\Http\Request;

use App\Http\Requests;
use Prettus\Validator\Contracts\ValidatorInterface;
use Prettus\Validator\Exceptions\ValidatorException;
use App\Http\Requests\PlaceAppointmentCreateRequest;
use App\Http\Requests\PlaceAppointmentUpdateRequest;
use App\Repositories\PlaceAppointmentRepository;
use App\Validators\PlaceAppointmentValidator;


class PlaceAppointmentsController extends Controller
{

    /**
     * @var PlaceAppointmentRepository
     */
    protected $repository;

    /**
     * @var PlaceAppointmentValidator
     */
    protected $validator;
    /**
     * @var PlaceReservationsRepository
     */
    private $reservationsRepository;

    /**
     * PlaceAppointmentsController constructor.
     * @param PlaceAppointmentRepository $repository
     * @param PlaceReservationsRepository $reservationsRepository
     * @param PlaceAppointmentValidator $validator
     */
    public function __construct(PlaceAppointmentRepository $repository, PlaceReservationsRepository $reservationsRepository , PlaceAppointmentValidator $validator)
    {
        $this->repository = $repository;
        $this->validator  = $validator;
        $this->reservationsRepository = $reservationsRepository;
    }


    /**
     * Display a listing of the resource.
     *
     * @param $id
     * @return \Illuminate\Http\Response
     */
    public function index($id)
    {
        $appointments = $this->repository->scopeQuery(function ($query) use($id) {
            return $query->where(['place_id' => $id]);
        })->all();

        if (request()->wantsJson()) {

            return response()->json([
                'data' => $appointments,
            ]);
        }

        return view('placeAppointments.index', compact('placeAppointments'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  PlaceAppointmentCreateRequest $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(PlaceAppointmentCreateRequest $request)
    {

        try {

            $this->validator->with($request->all())->passesOrFail(ValidatorInterface::RULE_CREATE);

            $placeAppointment = $this->repository->create($request->all());

            if($placeAppointment){
                //confirm reservation
                $this->reservationsRepository->update(['is_confirmed' => true], $request->get('reservation_id'));
            }

            $response = [
                'message' => 'PlaceAppointment created.',
                'data'    => $placeAppointment->toArray(),
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
        $placeAppointment = $this->repository->find($id);

        if (request()->wantsJson()) {

            return response()->json([
                'data' => $placeAppointment,
            ]);
        }

        return view('placeAppointments.show', compact('placeAppointment'));
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

        $placeAppointment = $this->repository->find($id);

        return view('placeAppointments.edit', compact('placeAppointment'));
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  PlaceAppointmentUpdateRequest $request
     * @param  string            $id
     *
     * @return Response
     */
    public function update(PlaceAppointmentUpdateRequest $request, $id)
    {

        try {

            $this->validator->with($request->all())->passesOrFail(ValidatorInterface::RULE_UPDATE);

            $placeAppointment = $this->repository->update($request->all(), $id);

            $response = [
                'message' => 'PlaceAppointment updated.',
                'data'    => $placeAppointment->toArray(),
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
                'message' => 'PlaceAppointment deleted.',
                'deleted' => $deleted,
            ]);
        }

        return redirect()->back()->with('message', 'PlaceAppointment deleted.');
    }
}
