<?php

namespace App\Http\Controllers;

use App\Repositories\PlaceRepository;
use App\Repositories\PlaceReservationsRepository;
use App\Repositories\PlaceTrackingRepository;
use App\Repositories\ReservationInterestRepository;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Requests;


class TestController extends Controller
{
    /**
     * @var PlaceRepository
     */
    private $placeRepository;
    /**
     * @var PlaceTrackingRepository
     */
    private $trackingRepository;
    /**
     * @var ReservationInterestRepository
     */
    private $reservationInterestRepository;
    /**
     * @var PlaceReservationsRepository
     */
    private $reservationsRepository;

    /**
     * TestController constructor.
     * @param PlaceTrackingRepository $trackingRepository
     * @param PlaceRepository $placeRepository
     * @param ReservationInterestRepository $reservationInterestRepository
     * @param PlaceReservationsRepository $reservationsRepository
     */
    function __construct(
        PlaceTrackingRepository $trackingRepository,
        PlaceRepository $placeRepository,
        ReservationInterestRepository $reservationInterestRepository,
        PlaceReservationsRepository $reservationsRepository
    )
    {
        $this->trackingRepository = $trackingRepository;
        $this->placeRepository = $placeRepository;
        $this->reservationInterestRepository = $reservationInterestRepository;
        $this->reservationsRepository = $reservationsRepository;
    }

    /**
     * Get place to show
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function testEmailWithSend($template, $email)
    {

        \Mail::send($template, [], function ($message) use ($email, $template) {
            $message->from('no-reply@weplaces.com.br', 'We Places');
            $message->to($email)->subject('Teste de template de email: ' . $template);
        });

        return 'Email enviado com sucesso';

    }

    public function testEmailData($id)
    {
        $before_last_month_reference = Carbon::now()->subMonth(1)->startOfMonth()->format('Y-m-d');
        $last_month_reference = Carbon::now()->startOfMonth()->format('Y-m-d');

        $place = $this->placeRepository->makeModel()
            ->where('id', $id)->select('id', 'name', 'email', 'user_id')
            ->with('user')
            ->first();

        if (!$place) {

            if (request()->wantsJson()) {

                return response()->json(['message' => 'Este local não existe']);
            }

            return 'Este local não existe';
        } else {
            $place = $place->setHidden(['appointments_count', 'reservations_count', 'pre_reservations_count'])->toArray();
        }

        $reservationInterests = $this->reservationInterestRepository->findWhere(['place_id' => $id])->all();

        $before_last_month = $this->trackingRepository->makeModel()
            ->where('place_id', $id)
            ->where('reference', $before_last_month_reference)
            ->first();


        $last_month = $this->trackingRepository->makeModel()
            ->where('place_id', $id)
            ->where('reference', $last_month_reference)
            ->first();


        $reservations_data = $this->handleReservations($id);

        $empty_data = [
            'views' => 0,
            'permanence' => 0,
            'call_clicks' => 0,
            'whatsapp_clicks' => 0,
            'contact_clicks' => 0,
            'link_shares' => 0,
            'facebook_shares' => 0,
            'whatsapp_shares' => 0,
        ];

        if (!$last_month) {
            $last_month = $empty_data;
        } else {
            $last_month = $last_month->setHidden(['id', 'place_id', 'reference', 'created_at', 'updated_at'])->toArray();
        }

        if (!$before_last_month) {
            $before_last_month = $empty_data;
        } else {
            $before_last_month = $before_last_month->setHidden(['id', 'place_id', 'reference', 'created_at', 'updated_at'])->toArray();
        }

        $data = [];

        foreach ($before_last_month as $key => $value) {
            $last_month_value = $last_month[$key];

            if ($key == 'permanence' && $value && $last_month[$key]) {
                $last_month_value = round($last_month[$key] / $last_month['views'] / 60, 2);
            }

            if ($key == 'permanence' && !$value && $last_month[$key]) {
                $last_month_value = round($last_month[$key] / $last_month['views'] / 60, 2);
            }

            $data[$key] = [
                'last_month' => $last_month_value,
                'stats' => $this->calcDiff($last_month[$key], $value)
            ];

        };


        if (!$place['has_owner']) {
            $data['reservation_interests'] = count($reservationInterests);
        }

        $data['reservations'] = $reservations_data['reservations'];
        $data['pre_reservations'] = $reservations_data['pre_reservations'];

        $data['place'] = $place;

        $email = !$place['has_owner'] ? $place['email'] : $place['user']['email'];

        if(!$email){
            if (request()->wantsJson()) {

                return response()->json(['message' => 'Nenhum email cadastrado.']);
            }
            return 'Nenhum email cadastrado.';
        }

        //Avoid send email to test domains
        if (preg_match("/example/i", $email) || preg_match("/teste/i", $email) || preg_match("/test/i", $email)) {

            if (request()->wantsJson()) {

                return response()->json(['message' => 'O email não pode ser enviado para ' . $email]);
            }
            return 'O email não pode ser enviado para ' . $email;
        }

        \Mail::send('emails.templatetwo', ['data' => $data], function ($message) use ($data, $email) {
            $message->from('no-reply@weplaces.com.br', 'We Places');
            $message->to($email)->subject('Relatório mensal We Places');
        });


        if (request()->wantsJson()) {

            return response()->json(['message' => 'Email enviado com sucesso']);
        }

        return 'Email enviado com sucesso para: ' . $email . ' | local: ' . $place['name'];
    }

    function calcDiff($last_month, $before_last_month)
    {

        $result = null;

        $result = @(($last_month - $before_last_month) / $before_last_month * 100);

        $result = is_nan($result) || is_infinite($result) ? 0 : $result;

        $is_positive = $result > 0;

        $no_data = $before_last_month <= 0;

        return ['value' => abs(round($result, 2)), 'is_positive' => $is_positive, 'no_data' => $no_data];
    }

    /*
     * Handle with Reservations data
     */
    function handleReservations($id){

        $month_reservations = $this->reservationsRepository->scopeQuery(function ($query) use ($id) {

            $start = Carbon::now()->startOfMonth()->format('Y-m-d');
            $end = Carbon::now()->endOfMonth()->format('Y-m-d');
            return $query->where('place_id', $id)->whereBetween('date', [$start, $end]);

        })->all();

        $last_month_reservations = $this->reservationsRepository->scopeQuery(function ($query) use ($id) {

            $start = Carbon::now()->subMonth(1)->startOfMonth()->format('Y-m-d');
            $end = Carbon::now()->subMonth(1)->endOfMonth()->format('Y-m-d');
            return $query->where('place_id', $id)->whereBetween('date', [$start, $end]);

        })->all();


        $month_reservations_data = [
            'pre_reservations' => [
                'total' => 0,
                'confirmed' => 0,
                'canceled' => 0,
                'waiting' => 0
            ],

            'reservations' => [
                'total' => 0,
                'confirmed' => 0,
                'canceled' => 0,
                'waiting' => 0
            ]
        ];

        foreach ($month_reservations as $key => $reservation){

            //Pre reservations
            if($reservation->is_pre_reservation){
                if(!isset($month_reservations_data['pre_reservations']['total'])){
                    $month_reservations_data['pre_reservations']['total'] = 1;
                }else{
                    $month_reservations_data['pre_reservations']['total'] += 1;
                }
            }

            if($reservation->is_pre_reservation && $reservation->is_canceled){
                if(!isset($month_reservations_data['pre_reservations']['canceled'])){
                    $month_reservations_data['pre_reservations']['canceled'] = 1;
                }else{
                    $month_reservations_data['pre_reservations']['canceled'] += 1;
                }
            }

            if($reservation->is_pre_reservation && $reservation->is_confirmed){
                if(!isset($month_reservations_data['pre_reservations']['confirmed'])){
                    $month_reservations_data['pre_reservations']['confirmed'] = 1;
                }else{
                    $month_reservations_data['pre_reservations']['confirmed'] += 1;
                }
            }

            if($reservation->is_pre_reservation && !$reservation->is_confirmed && !$reservation->is_canceled){
                if(!isset($month_reservations_data['pre_reservations']['waiting'])){
                    $month_reservations_data['pre_reservations']['waiting'] = 1;
                }else{
                    $month_reservations_data['pre_reservations']['waiting'] += 1;
                }
            }

            //Reservations
            if(!$reservation->is_pre_reservation){
                if(!isset($month_reservations_data['reservations']['total'])){
                    $month_reservations_data['reservations']['total'] = 1;
                }else{
                    $month_reservations_data['reservations']['total'] += 1;
                }
            }

            if(!$reservation->is_pre_reservation && $reservation->is_canceled){
                if(!isset($month_reservations_data['reservations']['canceled'])){
                    $month_reservations_data['reservations']['canceled'] = 1;
                }else{
                    $month_reservations_data['reservations']['canceled'] += 1;
                }
            }

            if(!$reservation->is_pre_reservation && $reservation->is_confirmed){
                if(!isset($month_reservations_data['reservations']['confirmed'])){
                    $month_reservations_data['reservations']['confirmed'] = 1;
                }else{
                    $month_reservations_data['reservations']['confirmed'] += 1;
                }
            }

            if(!$reservation->is_pre_reservation && !$reservation->is_confirmed && !$reservation->is_canceled){
                if(!isset($month_reservations_data['reservations']['waiting'])){
                    $month_reservations_data['reservations']['waiting'] = 1;
                }else{
                    $month_reservations_data['reservations']['waiting'] += 1;
                }
            }

        }

        $last_month_reservations_data = [
            'pre_reservations' => [
                'total' => 0,
                'confirmed' => 0,
                'canceled' => 0,
                'waiting' => 0
            ],

            'reservations' => [
                'total' => 0,
                'confirmed' => 0,
                'canceled' => 0,
                'waiting' => 0
            ]
        ];

        foreach ($last_month_reservations as $key => $reservation){

            //Pre reservations
            if($reservation->is_pre_reservation){
                if(!isset($last_month_reservations_data['pre_reservations']['total'])){
                    $last_month_reservations_data['pre_reservations']['total'] = 1;
                }else{
                    $last_month_reservations_data['pre_reservations']['total'] += 1;
                }
            }

            if($reservation->is_pre_reservation && $reservation->is_canceled){
                if(!isset($last_month_reservations_data['pre_reservations']['canceled'])){
                    $last_month_reservations_data['pre_reservations']['canceled'] = 1;
                }else{
                    $last_month_reservations_data['pre_reservations']['canceled'] += 1;
                }
            }

            if($reservation->is_pre_reservation && $reservation->is_confirmed){
                if(!isset($last_month_reservations_data['pre_reservations']['confirmed'])){
                    $last_month_reservations_data['pre_reservations']['confirmed'] = 1;
                }else{
                    $last_month_reservations_data['pre_reservations']['confirmed'] += 1;
                }
            }

            if($reservation->is_pre_reservation && !$reservation->is_confirmed && !$reservation->is_canceled){
                if(!isset($last_month_reservations_data['pre_reservations']['waiting'])){
                    $last_month_reservations_data['pre_reservations']['waiting'] = 1;
                }else{
                    $last_month_reservations_data['pre_reservations']['waiting'] += 1;
                }
            }

            //Reservations
            if(!$reservation->is_pre_reservation){
                if(!isset($last_month_reservations_data['reservations']['total'])){dd($month_reservations_data, $last_month_reservations_data);
                    $last_month_reservations_data['reservations']['total'] = 1;
                }else{
                    $last_month_reservations_data['reservations']['total'] += 1;
                }
            }

            if(!$reservation->is_pre_reservation && $reservation->is_canceled){
                if(!isset($last_month_reservations_data['reservations']['canceled'])){
                    $last_month_reservations_data['reservations']['canceled'] = 1;
                }else{
                    $last_month_reservations_data['reservations']['canceled'] += 1;
                }
            }

            if(!$reservation->is_pre_reservation && $reservation->is_confirmed){
                if(!isset($last_month_reservations_data['reservations']['confirmed'])){
                    $last_month_reservations_data['reservations']['confirmed'] = 1;
                }else{
                    $last_month_reservations_data['reservations']['confirmed'] += 1;
                }
            }

            if(!$reservation->is_pre_reservation && !$reservation->is_confirmed && !$reservation->is_canceled){
                if(!isset($last_month_reservations_data['reservations']['waiting'])){
                    $last_month_reservations_data['reservations']['waiting'] = 1;
                }else{
                    $last_month_reservations_data['reservations']['waiting'] += 1;
                }
            }

        }

        $data = [];
        foreach ($month_reservations_data as $key => $value) {

            foreach($value as $item_key => $item_value ){
                $data [$key][$item_key] = [
                    'last_month' => $item_value,
                    'stats' => $this->calcDiff($item_value, $last_month_reservations_data[$key][$item_key])
                ];
            }

        };

        return ['reservations' => $data['reservations'],'pre_reservations' => $data['pre_reservations']];

    }
}

