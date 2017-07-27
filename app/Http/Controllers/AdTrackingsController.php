<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Requests\AdTrackingCreateRequest;
use App\Http\Requests\AdTrackingUpdateRequest;
use App\Repositories\AdTrackingRepository;
use App\Validators\AdTrackingValidator;
use Carbon\Carbon as Carbon;


class AdTrackingsController extends Controller
{

    /**
     * @var AdTrackingRepository
     */
    protected $repository;

    public function __construct(AdTrackingRepository $repository)
    {
        $this->repository = $repository;
    }

    public function tracker(Request $request)
    {
        $reference = Carbon::now()->startOfMonth()->format('Y-m-d');

        $placeTracking = $this->repository->findWhere(['ad_id' => $request->get('id'), 'reference' => $reference])->first();

        $placeTracking->increment($request->get('info'));

        return response()->json(['message' => 'ad tracking updated.',]);
    }
}
