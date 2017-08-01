<?php

namespace App\Console\Commands;

use App\Repositories\PlaceRepository;
use App\Repositories\PlaceReservationsRepository;
use App\Repositories\PlaceTrackingRepository;
use App\Repositories\ReservationInterestRepository;
use Illuminate\Console\Command;
use Carbon\Carbon as Carbon;

use App\Mail\WeeklyInsightMail;

class PlaceMonthlyReport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'place:monthly-report';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send monthly report';
    /**
     * @var PlaceRepository
     */
    private $placeRepository;
    /**
     * @var ReservationInterestRepository
     */
    private $reservationInterestRepository;
    /**
     * @var PlaceTrackingRepository
     */
    private $trackingRepository;
    /**
     * @var PlaceReservationsRepository
     */
    private $reservationsRepository;

    /**
     * Create a new command instance.
     *
     * @param PlaceRepository $placeRepository
     * @param ReservationInterestRepository $reservationInterestRepository
     * @param PlaceTrackingRepository $trackingRepository
     * @param PlaceReservationsRepository $reservationsRepository
     */
    public function __construct(PlaceRepository $placeRepository,
                                ReservationInterestRepository $reservationInterestRepository,
                                PlaceTrackingRepository $trackingRepository,
                                PlaceReservationsRepository $reservationsRepository
    )
    {
        parent::__construct();
        $this->placeRepository = $placeRepository;
        $this->reservationInterestRepository = $reservationInterestRepository;
        $this->trackingRepository = $trackingRepository;
        $this->reservationsRepository = $reservationsRepository;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {

        $this->info('Started send monthly report');

        $places = $this->placeRepository->scopeQuery(function ($query){

            return $query->where('confirmed', true)->select('id', 'name', 'email', 'user_id');

        })->with('user')->all();

        $reportAll = [];

        foreach($places  as $place){
            $place = $place->setHidden(['appointments_count', 'reservations_count', 'pre_reservations_count'])->toArray();

            $email = !$place['has_owner'] ? $place['email'] : $place['user']['email'];

            if(!$email || !filter_var($email, FILTER_VALIDATE_EMAIL)){
                $this->warn($place['name']. ' sem e-mail cadastrado ou não aceito.');
                continue;
            }

            //Avoid send email to test domains
            if (preg_match("/example/i", $email) || preg_match("/teste/i", $email) || preg_match("/test/i", $email)) {

                $this->warn('O email não pode ser enviado para ' . $email);
                continue;
            }

            $data = $this->getData($place, $place['id']);

            \Mail::to($email, $place['name'])->queue(new WeeklyInsightMail($data));

            $reportAll[] = ['place_name' => $place['name'], 'place_email' => $email, 'place_views' => $data['views']['last_month'] ];

            $this->info('Email enviado para ' . $email);
        }

        //Para controlar para quais emails foram enviados os reports

        $reportData = [];
        $reportData['align'] = 'left';
        $text = '';

        foreach($reportAll as $report){
            $text = $text . '<p><b>Espaço: </b>' . $report['place_name'] . ' | ' . '<b>Email: </b>' . $report['place_email'] . '</p>
            <p><b>Visualizações: <b>' . $report['place_views'] . '</p><hr>';
        }

        $reportData['messageTitle'] = 'Insights enviados';
        $reportData['messageOne'] = $text;
        $reportData['messageSubject'] = 'We Places: Insight reports.';

        \Mail::send('emails.standart-with-btn',['data' => $reportData], function ($message) use ($reportData){
            $message->from('no-reply@weplaces.com.br', 'Landing We Places');
            $message->to('comercial@weplaces.com.br', 'We Places')->subject($reportData['messageSubject']);
            $message->to('nathan.borem@weplaces.com.br', 'We Places')->subject($reportData['messageSubject']);
        });

    }

    function getData($place, $id)
    {
        $before_last_month_reference = Carbon::now()->subMonth(1)->startOfMonth()->format('Y-m-d');
        $last_month_reference = Carbon::now()->startOfMonth()->format('Y-m-d');

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


        return $data;

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
