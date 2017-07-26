<?php

namespace App\Http\Controllers;

use App\Repositories\ServiceAdPhotoRepository;
use Illuminate\Http\Request;

use App\Http\Requests;
use Prettus\Validator\Contracts\ValidatorInterface;
use Prettus\Validator\Exceptions\ValidatorException;
use App\Http\Requests\ServiceAdCreateRequest;
use App\Http\Requests\ServiceAdUpdateRequest;
use App\Repositories\ServiceAdRepository;
use App\Validators\ServiceAdValidator;


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
     * ServiceAdsController constructor.
     * @param ServiceAdRepository $repository
     * @param ServiceAdValidator $validator
     * @param ServiceAdPhotoRepository $adPhotoRepository
     */
    public function __construct(ServiceAdRepository $repository, ServiceAdValidator $validator, ServiceAdPhotoRepository $adPhotoRepository)
    {
        $this->repository = $repository;
        $this->validator  = $validator;
        $this->adPhotoRepository = $adPhotoRepository;
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->repository->pushCriteria(app('Prettus\Repository\Criteria\RequestCriteria'));
        $serviceAds = $this->repository->with('advertiser', 'place')->paginate(10);

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
        $serviceAd = $this->repository->with(['place' =>function($query){
            $query->select('id', 'name');
        },'advertiser', 'photos'])->with([])->find($id);

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
        $deleted = $this->repository->delete($id);

        if (request()->wantsJson()) {

            return response()->json([
                'message' => 'ServiceAd deleted.',
                'deleted' => $deleted,
            ]);
        }

        return redirect()->back()->with('message', 'ServiceAd deleted.');
    }
}
