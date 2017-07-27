<?php

namespace App\Http\Controllers;

use App\Repositories\AdTrackingRepository;
use App\Repositories\ServiceAdPhotoRepository;
use Illuminate\Http\Request;

use App\Http\Requests;
use Prettus\Validator\Contracts\ValidatorInterface;
use Prettus\Validator\Exceptions\ValidatorException;
use App\Http\Requests\ServiceAdCreateRequest;
use App\Http\Requests\ServiceAdUpdateRequest;
use App\Repositories\ServiceAdRepository;
use App\Validators\ServiceAdValidator;
use Carbon\Carbon as Carbon;


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
     * Ad list to home.
     *
     * @return \Illuminate\Http\Response
     */
    public function homeAds()
    {
        $reference = Carbon::now()->startOfMonth()->format('Y-m-d');

        $serviceAd = $this->repository->makeModel()->where(function ($query) {
            return $query->where('type', 'home')->where('is_active', true);
        })->orderByRaw('RAND()')->take(1)->with('advertiser', 'photos')->first();


        $tracking = $this->adTrackingRepository
            ->firstOrCreate([
                'ad_id' => $serviceAd->id,
                'ad_type' => get_class($serviceAd),
                'reference' => $reference
            ]);

        $tracking->increment('exhibitions');

        return response()->json($serviceAd);

    }
}
