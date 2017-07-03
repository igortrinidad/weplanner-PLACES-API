<?php

namespace App\Http\Controllers;

use App\Repositories\PlaceRepository;
use Illuminate\Http\Request;

use App\Http\Requests;
use Prettus\Validator\Contracts\ValidatorInterface;
use Prettus\Validator\Exceptions\ValidatorException;
use App\Http\Requests\ReservationInterestCreateRequest;
use App\Http\Requests\ReservationInterestUpdateRequest;
use App\Repositories\ReservationInterestRepository;
use App\Validators\ReservationInterestValidator;


class ReservationInterestsController extends Controller
{

    /**
     * @var ReservationInterestRepository
     */
    protected $repository;

    /**
     * @var ReservationInterestValidator
     */
    protected $validator;
    /**
     * @var PlaceRepository
     */
    private $placeRepository;

    /**
     * ReservationInterestsController constructor.
     * @param ReservationInterestRepository $repository
     * @param PlaceRepository $placeRepository
     * @param ReservationInterestValidator $validator
     */
    public function __construct(ReservationInterestRepository $repository, PlaceRepository $placeRepository, ReservationInterestValidator $validator)
    {
        $this->repository = $repository;
        $this->validator  = $validator;
        $this->placeRepository = $placeRepository;
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->repository->pushCriteria(app('Prettus\Repository\Criteria\RequestCriteria'));
        $reservationInterests = $this->repository->all();

        if (request()->wantsJson()) {

            return response()->json([
                'data' => $reservationInterests,
            ]);
        }

        return view('reservationInterests.index', compact('reservationInterests'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  ReservationInterestCreateRequest $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(ReservationInterestCreateRequest $request)
    {

        try {

            $this->validator->with($request->all())->passesOrFail(ValidatorInterface::RULE_CREATE);

            $place = $this->placeRepository->makeModel()->find($request->get('place_id'));

            $reservationInterest = $this->repository->create($request->all());

            //Send the e-mail
            if($place->email){

                $data = [];
                $data['place_name'] = $place->name;
                $data['place_email'] = $place->email;

                $data['messageTitle'] = 'Olá,';
                $data['messageOne'] = 'Seu espaço '. $data['place_name']. ' cadastrado no aplicativo Places We-Planner acabou de receber uma solicitação de reserva, mas ainda não está disponível para reservas online através de nosso APP.';
                $data['messageThree'] = 'Cadastre-se agora e disponibilize para todos os clientes a agenda online exclusiva para espaços de cerimônia e festa.';
                $data['button_link'] = 'https://places.we-planner.com/#/'.$place->id.'/cadastre-se';
                $data['button_name'] = 'Cadastrar agora';
                $data['messageFour'] = 'Uma facilidade para você e seus clientes.';
                $data['messageSubject'] = 'Solicitação de reserva para '. $data['place_name'];

                \Mail::send('emails.standart-with-btn',['data' => $data], function ($message) use ($data){
                    $message->from('comercial@we-planner.com', 'App Places We-Planner');
                    $message->to($data['place_email'], $data['place_name'])->subject($data['messageSubject']);
                });

                if(!count(\Mail::failures())) {
                    return response()->json(['alert' => ['type' => 'success', 'title' => 'Atenção!', 'message' => 'E-mail enviado com sucesso.', 'status_code' => 200]], 200);
                }

                if(count(\Mail::failures())){
                    return response()->json(['alert' => ['type' => 'error', 'title' => 'Atenção!', 'message' => 'Ocorreu um erro ao enviar o e-mail.', 'status_code' => 500]], 500);
                }
            }

            $response = [
                'message' => 'ReservationInterest created.',
                'data'    => $reservationInterest->toArray(),
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
        $reservationInterest = $this->repository->find($id);

        if (request()->wantsJson()) {

            return response()->json([
                'data' => $reservationInterest,
            ]);
        }

        return view('reservationInterests.show', compact('reservationInterest'));
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

        $reservationInterest = $this->repository->find($id);

        return view('reservationInterests.edit', compact('reservationInterest'));
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  ReservationInterestUpdateRequest $request
     * @param  string            $id
     *
     * @return Response
     */
    public function update(ReservationInterestUpdateRequest $request, $id)
    {

        try {

            $this->validator->with($request->all())->passesOrFail(ValidatorInterface::RULE_UPDATE);

            $reservationInterest = $this->repository->update($request->all(), $id);

            $response = [
                'message' => 'ReservationInterest updated.',
                'data'    => $reservationInterest->toArray(),
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
                'message' => 'ReservationInterest deleted.',
                'deleted' => $deleted,
            ]);
        }

        return redirect()->back()->with('message', 'ReservationInterest deleted.');
    }
}
