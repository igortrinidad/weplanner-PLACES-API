<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Prettus\Validator\Contracts\ValidatorInterface;
use Prettus\Validator\Exceptions\ValidatorException;
use App\Http\Requests\AdvertiserCreateRequest;
use App\Http\Requests\AdvertiserUpdateRequest;
use App\Repositories\AdvertiserRepository;
use App\Validators\AdvertiserValidator;


class AdvertisersController extends Controller
{

    /**
     * @var AdvertiserRepository
     */
    protected $repository;

    /**
     * @var AdvertiserValidator
     */
    protected $validator;

    public function __construct(AdvertiserRepository $repository, AdvertiserValidator $validator)
    {
        $this->repository = $repository;
        $this->validator  = $validator;
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->repository->pushCriteria(app('Prettus\Repository\Criteria\RequestCriteria'));

        $advertisers = $this->repository->orderBy('name', 'asc')->paginate(10);

        if (request()->wantsJson()) {

            return response()->json($advertisers);
        }

        return view('advertisers.index', compact('advertisers'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  AdvertiserCreateRequest $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(AdvertiserCreateRequest $request)
    {

        try {

            $this->validator->with($request->all())->passesOrFail(ValidatorInterface::RULE_CREATE);

            $advertiser = $this->repository->create($request->all());

            $response = [
                'message' => 'Advertiser created.',
                'data'    => $advertiser->toArray(),
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
        $advertiser = $this->repository->with('ads', 'decorations')->find($id);

        if (request()->wantsJson()) {

            return response()->json($advertiser);
        }

        return view('advertisers.show', compact('advertiser'));
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  AdvertiserUpdateRequest $request
     * @param  string            $id
     *
     * @return Response
     */
    public function update(AdvertiserUpdateRequest $request)
    {

        try {

            $this->validator->with($request->all())->passesOrFail(ValidatorInterface::RULE_UPDATE);

            $advertiser = $this->repository->update($request->all(), $request->get('id'));

            $response = [
                'message' => 'Advertiser updated.',
                'data'    => $advertiser->toArray(),
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
                'message' => 'Advertiser deleted.',
                'deleted' => $deleted,
            ]);
        }

        return redirect()->back()->with('message', 'Advertiser deleted.');
    }

    /**
     * Advertiser search by name.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function search(Request $request)
    {
        $advertisers = $this->repository
            ->scopeQuery(function ($query) use ($request) {
                return $query->where('name', 'LIKE', '%' . $request->get('therm') . '%');
            })->all();

        if (request()->wantsJson()) {

            return response()->json(['results' => $advertisers]);
        }
    }
}
