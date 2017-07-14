<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Prettus\Validator\Contracts\ValidatorInterface;
use Prettus\Validator\Exceptions\ValidatorException;
use App\Http\Requests\PlaceTrackingCreateRequest;
use App\Http\Requests\PlaceTrackingUpdateRequest;
use App\Repositories\PlaceTrackingRepository;
use App\Validators\PlaceTrackingValidator;
use Carbon\Carbon as Carbon;


class PlaceTrackingsController extends Controller
{

    /**
     * @var PlaceTrackingRepository
     */
    protected $repository;

    /**
     * @var PlaceTrackingValidator
     */
    protected $validator;

    public function __construct(PlaceTrackingRepository $repository, PlaceTrackingValidator $validator)
    {
        $this->repository = $repository;
        $this->validator  = $validator;
    }

    /**
     * Place tracking.
     *
     * @param  Request $request
     *
     * @return Response
     */
    public function tracker(Request $request)
    {

        try {

            //init the tracking
            if(!$request->has('id') && $request->has('place_id')){

                $request->merge([
                    'reference' => Carbon::now()->startOfMonth(),
                ]);

                $placeTracking = $this->repository->firstOrCreate($request->all());

                $placeTracking->increment('views', 1);

                $response = [
                    'message' => 'tracking started.',
                    'tracking_id'    => $placeTracking->id,
                ];
            }

            //update a existing tranking data
            if($request->has('id') && !$request->has('place_id')){
                $placeTracking = $this->repository->find(['id' => $request->get('id')])->first();

                //increment other fields
                if($request->has('info')){

                    //For keep the mobile apps compatibility
                    $columns = [
                        'contact_call' =>'call_clicks',
                        'contact_whatsapp' => 'whatsapp_clicks',
                        'contact_message' => 'contact_clicks',
                        'share_copy' => 'link_shares',
                        'share_whatsapp' => 'whatsapp_shares',
                        'share_facebook' => 'facebook_shares'
                    ];

                    $info =  array_get($columns, $request->get('info'));

                   $placeTracking->increment($info, 1);
                }

                //increment duration
                if(!$request->has('info')){
                    $placeTracking->increment('permanence', 60);
                }

                $response = [
                    'message' => 'tracking  updated.',
                ];
            }

            return response()->json($response);


        } catch (ValidatorException $e) {

            return response()->json([
                'error'   => true,
                'message' => $e->getMessageBag()
            ]);
        }
    }
}
