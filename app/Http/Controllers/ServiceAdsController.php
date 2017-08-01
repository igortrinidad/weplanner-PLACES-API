<?php

namespace App\Http\Controllers;

use App\Repositories\AdTrackingRepository;
use App\Repositories\ServiceAdPhotoRepository;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Redis;
use Prettus\Validator\Contracts\ValidatorInterface;
use Prettus\Validator\Exceptions\ValidatorException;
use App\Http\Requests\ServiceAdCreateRequest;
use App\Http\Requests\ServiceAdUpdateRequest;
use App\Repositories\ServiceAdRepository;
use App\Validators\ServiceAdValidator;
use Carbon\Carbon as Carbon;

use App\Models\ServiceAd;


class ServiceAdsController extends Controller
{

    /**
     * @var ServiceAdRepository
     */
    protected $repository;

    /**
     * @var ServiceAdValidator
     */
    protected $validator;
    /**
     * @var ServiceAdPhotoRepository
     */
    private $adPhotoRepository;
    /**
     * @var AdTrackingRepository
     */
    private $adTrackingRepository;

    /**
     * ServiceAdsController constructor.
     * @param ServiceAdRepository $repository
     * @param ServiceAdValidator $validator
     * @param ServiceAdPhotoRepository $adPhotoRepository
     * @param AdTrackingRepository $adTrackingRepository
     */
    public function __construct(
        ServiceAdRepository $repository,
        ServiceAdValidator $validator,
        ServiceAdPhotoRepository $adPhotoRepository,
        AdTrackingRepository $adTrackingRepository
    )
    {
        $this->repository = $repository;
        $this->validator  = $validator;
        $this->adPhotoRepository = $adPhotoRepository;
        $this->adTrackingRepository = $adTrackingRepository;
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->repository->pushCriteria(app('Prettus\Repository\Criteria\RequestCriteria'));
        $serviceAds = $this->repository->with('advertiser', 'place')->orderBy('created_at', 'desc')->paginate(10);

        if (request()->wantsJson()) {

            return response()->json($serviceAds);
        }

        return view('serviceAds.index', compact('serviceAds'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  ServiceAdCreateRequest $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(ServiceAdCreateRequest $request)
    {

        try {

            $this->validator->with($request->all())->passesOrFail(ValidatorInterface::RULE_CREATE);

            $serviceAd = $this->repository->create($request->all());

            //Atualiza a lista de anuncios para fazer a rotatividade
            $this->updateRedisAdList();


            //update photos
            if (array_key_exists('photos', $request->all())) {
                foreach ($request->get('photos') as $photo) {
                    $this->adPhotoRepository->update($photo, $photo['id']);
                }
            }

            $response = [
                'message' => 'ServiceAd created.',
                'data'    => $serviceAd->toArray(),
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
        $serviceAd = $this->repository->with(['place' => function($query){
            $query->select('id', 'name');
        },'advertiser', 'photos', 'tracking'])->with([])->find($id);

        if (request()->wantsJson()) {

            return response()->json([
                'data' => $serviceAd,
            ]);
        }

        return view('serviceAds.show', compact('serviceAd'));
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

        $serviceAd = $this->repository->find($id);

        return view('serviceAds.edit', compact('serviceAd'));
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  ServiceAdUpdateRequest $request
     * @param  string            $id
     *
     * @return Response
     */
    public function update(ServiceAdUpdateRequest $request)
    {

        try {

            $this->validator->with($request->all())->passesOrFail(ValidatorInterface::RULE_UPDATE);

            //update photos
            if (array_key_exists('photos', $request->all())) {
                foreach ($request->get('photos') as $photo) {
                    $this->adPhotoRepository->update($photo, $photo['id']);
                }
            }

            $serviceAd = $this->repository->update($request->all(), $request->get('id'));

            //Atualiza a lista de anuncios para fazer a rotatividade
            $this->updateRedisAdList();

            $response = [
                'message' => 'ServiceAd updated.',
                'data'    => $serviceAd->toArray(),
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

        $serviceAd = $this->repository->find($id);

        //Remove photos
        foreach ($serviceAd->photos as $photo) {
            \Storage::disk('media')->delete($photo->path);
            $photo->delete();
        }

        $serviceAd->delete();

        if (request()->wantsJson()) {

            return response()->json([
                'message' => 'ServiceAd deleted.',
                'deleted' => $serviceAd,
            ]);
        }

        return redirect()->back()->with('message', 'ServiceAd deleted.');
    }
    /**
     * Contact form
     *
     * @return \Illuminate\Http\Response
     */
    public function contactForm(Request $request)
    {

        if(empty($request['client_name']) || empty($request['client_email']) || empty($request['message']) || empty($request['phone']) || empty($request['advertiser_email']) || empty($request['advertiser_name']) ) {

            return response()->json([
                'data' => 'no data provided',
            ]);
        }

        //Email
        $data = [];
        $data['client_name'] = $request['client_name'];
        $data['client_email'] = $request['client_email'];
        $data['advertiser_email'] = $request['advertiser_email'];
        $data['advertiser_name'] = $request['advertiser_name'];
        $data['align'] = 'left';

        $data['messageTitle'] = 'Olá,';
        $data['messageOne'] = 'Você acabou de receber a mensagem abaixo através do Aplicativo We Places:';
        $data['messageTwo'] = 'Enviada por: ' . $data['client_name'];
        $data['messageThree'] = 'Email: ' . $data['client_email'];
        $data['messageFour'] = 'Mensagem: ' . $request['message'];
        $data['messageSubject'] = 'Mensagem recebida no We Places';

        \Mail::send('emails.standart-with-btn',['data' => $data], function ($message) use ($data){
            $message->from('no-reply@weplaces.com.br', 'We Places');
            $message->to($data['advertiser_email'], $data['advertiser_name'])->subject($data['messageSubject']);
        });

        if(!count(\Mail::failures())) {
            return response()->json(['alert' => ['type' => 'success', 'title' => 'Atenção!', 'message' => 'Mensagem enviada com sucesso', 'status_code' => 200]], 200);
        }

        if(count(\Mail::failures())){
            return response()->json(['alert' => ['type' => 'error', 'title' => 'Atenção!', 'message' => 'Ocorreu um erro ao enviar o e-mail.', 'status_code' => 500]], 500);
        }

    }


    /**
     * Ad list to home.
     *
     * @return \Illuminate\Http\Response
     */
    public function homeAds()
    {
        $reference = Carbon::now()->startOfMonth()->format('Y-m-d');

        // Check for ads stored on redis
        $home_ads = json_decode(\Redis::get('home_ads'));

        if(!$home_ads){

             $this->updateRedisAdList();

             $home_ads = json_decode(\Redis::get('home_ads'));
        }

        // get the first ad on list
        $first_ad = array_first($home_ads);

        //remove first ad from list
        $home_ads = array_except($home_ads, [0]);

        //set the first ad to last
        array_push($home_ads, $first_ad);

        // store the new list on redis
        \Redis::set('home_ads', json_encode(array_flatten($home_ads)));

        // get the ad on DB
        $serviceAd = $this->repository->find($first_ad);

        //update tracking exhibitions
        $tracking = $this->adTrackingRepository
            ->firstOrCreate([
                'ad_id' => $serviceAd->id,
                'ad_type' => get_class($serviceAd),
                'reference' => $reference
            ]);

        $tracking->increment('exhibitions');

        return response()->json($serviceAd->load('advertiser', 'photos'));

    }

    /**
     * Ad list to city.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function cityAds(Request $request)
    {
        //List by city eg: belo_horizonte_ads
        $city_prefix = snake_case($request->get('city')).'_ads';

        $reference = Carbon::now()->startOfMonth()->format('Y-m-d');

        // Check for ads stored on redis
        $city_ads = json_decode(\Redis::get($city_prefix));

        if(!$city_ads){

            $this->updateRedisAdList();

            $city_ads = json_decode(\Redis::get($city_prefix));
        }

        if($city_ads){

            // get the first ad on list
            $first_ad = array_first($city_ads);

            //remove first ad from list
            $city_ads = array_except($city_ads, [0]);

            //set the first ad to last
            array_push($city_ads, $first_ad);

            // store the new list on redis
            \Redis::set($city_prefix, json_encode(array_flatten($city_ads)));

            // get the ad on DB
            $serviceAd = $this->repository->find($first_ad);

            //update tracking exhibitions
            $tracking = $this->adTrackingRepository
                ->firstOrCreate([
                    'ad_id' => $serviceAd->id,
                    'ad_type' => get_class($serviceAd),
                    'reference' => $reference
                ]);

            $tracking->increment('exhibitions');

            return response()->json($serviceAd->load('advertiser', 'photos'));
        }

        return response()->json(['status' => 'no-ads']);
    }

    /**
    * Ad list to place.
    *
    * @param Request $request
    * @return \Illuminate\Http\Response
    */
    public function placeAds(Request $request)
    {

        //List by place-id_ads
        $place_prefix = snake_case($request->get('place')).'_ads';

        $reference = Carbon::now()->startOfMonth()->format('Y-m-d');

        // Check for ads stored on redis
        $place_ads = json_decode(\Redis::get($place_prefix));

        if(!$place_ads){

            $this->updateRedisAdList();

            $place_ads = json_decode(\Redis::get($place_prefix));

        }

        if($place_ads){

            // get the first ad on list
            $first_ad = array_first($place_ads);

            //remove first ad from list
            $place_ads = array_except($place_ads, [0]);

            //set the first ad to last
            array_push($place_ads, $first_ad);

            // store the new list on redis
            \Redis::set($place_prefix, json_encode(array_flatten($place_ads)));

            // get the ad on DB
            $serviceAd = $this->repository->find($first_ad);

            //update tracking exhibitions
            $tracking = $this->adTrackingRepository
                ->firstOrCreate([
                    'ad_id' => $serviceAd->id,
                    'ad_type' => get_class($serviceAd),
                    'reference' => $reference
                ]);

            $tracking->increment('exhibitions');

            return response()->json($serviceAd->load('advertiser', 'photos'));
        }

        return response()->json(['status' => 'no-ads']);
    }

    /*
     * Update the ads list on redis after create/update or redis is empty
     */
    function updateRedisAdList(){

        \Redis::flushAll();

        //Get ads
        $serviceAds = $this->repository->makeModel()->where(function ($query) {
            return $query->where('is_active', true);
        })->get();

        //Home Ads
        $home_ads = $serviceAds->where('type', 'home')->pluck('id')->all();
        \Redis::set('home_ads', json_encode($home_ads));

        //Cities Ads
        $cities_ads = collect($serviceAds->where('type', 'city')->all());
        foreach($cities_ads as $city_ad){
            $city_prefix = snake_case($city_ad->city.'_ads');

            $city_ads = $cities_ads->where('city', $city_ad->city)->pluck('id')->all();

            \Redis::set($city_prefix, json_encode($city_ads));
        }

        //Places Ads
        $places_ads = collect($serviceAds->where('type', 'place')->all());
        foreach($places_ads as $place_ad){

            $place_prefix = snake_case($place_ad->place_id).'_ads';

            $place_ads = $places_ads->where('place_id', $place_ad->place_id)->pluck('id')->all();

            \Redis::set($place_prefix, json_encode($place_ads));

        }
    }
}
