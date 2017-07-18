<?php

namespace App\Http\Controllers;

use App\Repositories\PlaceRepository;
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
     * TestController constructor.
     * @param PlaceTrackingRepository $trackingRepository
     * @param PlaceRepository $placeRepository
     * @param ReservationInterestRepository $reservationInterestRepository
     */
    function __construct(PlaceTrackingRepository $trackingRepository,
                         PlaceRepository $placeRepository,
                         ReservationInterestRepository $reservationInterestRepository
    )
    {
        $this->trackingRepository = $trackingRepository;
        $this->placeRepository = $placeRepository;
        $this->reservationInterestRepository = $reservationInterestRepository;
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

        $place = $this->placeRepository->makeModel()
            ->where('id', $id)->select('id', 'name', 'email', 'user_id')
            ->with('user')
            ->first();

        if(!$place){

            if (request()->wantsJson()) {

                return response()->json(['message' => 'Este local não existe']);
            }

            return 'Este local não existe';
        }else{
            $place = $place->setHidden(['appointments_count', 'reservations_count', 'pre_reservations_count'])->toArray();
        }

        $reservationInterests = $this->reservationInterestRepository->findWhere(['place_id' => $id])->all();

        $before_last_month = $this->trackingRepository->makeModel()
            ->where('place_id', $id)
            ->where('reference', Carbon::now()->subMonth(2)->startOfMonth()->format('Y-m-d'))
            ->first();


        $last_month = $this->trackingRepository->makeModel()
            ->where('place_id', $id)
            ->where('reference', Carbon::now()->subMonth(1)->startOfMonth()->format('Y-m-d'))
            ->first();

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

            if ($key == 'permanence' && $value) {
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

        if(!$place['has_owner']){
            $data['reservation_interests'] = count($reservationInterests);
        }

        $data ['place'] = $place;

        //dd($data);

        $email = !$place['has_owner'] ? $place['email'] : $place['user']['email'];

        \Mail::send('emails.templatetwo', ['data' => $data], function ($message) use ($data, $email) {
            $message->from('no-reply@weplaces.com.br', 'We Places');
            $message->to($email)->subject('Teste de template de email');
        });


        if (request()->wantsJson()) {

            return response()->json(['message' => 'Email enviado com sucesso']);
        }

        return 'Email enviado com sucesso';
    }

    function calcDiff($last_month, $before_last_month)
    {

        $result = null;

        if (!empty($last_month) && !empty($before_last_month)) {

            $result = ($last_month - $before_last_month) / $before_last_month * 100;

            $is_positive = $result > 0;

            return ['value' => abs(round($result, 2)), 'is_positive' => $is_positive, 'no_data' => is_null($result)];

        } else {

            return ['value' => $before_last_month, 'is_positive' => true, 'no_data' => is_null($result)];
        }
    }
}

