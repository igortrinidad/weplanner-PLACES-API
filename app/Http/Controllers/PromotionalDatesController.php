<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Prettus\Validator\Contracts\ValidatorInterface;
use Prettus\Validator\Exceptions\ValidatorException;
use App\Http\Requests\PromotionalDateCreateRequest;
use App\Http\Requests\PromotionalDateUpdateRequest;
use App\Repositories\PromotionalDateRepository;
use App\Validators\PromotionalDateValidator;
use Carbon\Carbon as Carbon;


class PromotionalDatesController extends Controller
{

    /**
     * @var PromotionalDateRepository
     */
    protected $repository;

    /**
     * @var PromotionalDateValidator
     */
    protected $validator;

    public function __construct(PromotionalDateRepository $repository, PromotionalDateValidator $validator)
    {
        $this->repository = $repository;
        $this->validator  = $validator;
    }


    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        $promotionalDates = $this->repository->scopeQuery(function ($query) use($request){
            return $query->where(['place_id' => $request->get('place_id')])->orderBy('date', 'ASC');
        })->paginate(10);

        if (request()->wantsJson()) {

            return response()->json($promotionalDates);
        }

        return view('promotionalDates.index', compact('promotionalDates'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  PromotionalDateCreateRequest $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(PromotionalDateCreateRequest $request)
    {

        try {

            $this->validator->with($request->all())->passesOrFail(ValidatorInterface::RULE_CREATE);

            $promotionalDate = $this->repository->create($request->all());

            $response = [
                'message' => 'PromotionalDate created.',
                'data'    => $promotionalDate->toArray(),
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
        $promotionalDate = $this->repository->find($id);

        if (request()->wantsJson()) {

            return response()->json([
                'data' => $promotionalDate,
            ]);
        }

        return view('promotionalDates.show', compact('promotionalDate'));
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

        $promotionalDate = $this->repository->find($id);

        return view('promotionalDates.edit', compact('promotionalDate'));
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  PromotionalDateUpdateRequest $request
     *
     * @return Response
     */
    public function update(PromotionalDateUpdateRequest $request)
    {

        try {

            $this->validator->with($request->all())->passesOrFail(ValidatorInterface::RULE_UPDATE);

            $promotionalDate = $this->repository->update($request->all(), $request->get('id'));

            $response = [
                'message' => 'PromotionalDate updated.',
                'data'    => $promotionalDate->toArray(),
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
                'message' => 'PromotionalDate deleted.',
                'deleted' => $deleted,
            ]);
        }

        return redirect()->back()->with('message', 'PromotionalDate deleted.');
    }


    /**
     * List of promotional dates to display in home
     *
     * @return \Illuminate\Http\Response
     */
    public function homeList()
    {
<<<<<<< HEAD
        
=======
>>>>>>> development
        $promotionalDates = $this->repository->makeModel()
            ->orderByRaw('RAND()')
            ->take(8) // take 8 records randomly
            ->with(['place' => function ($query) {
                $query->select('id', 'name', 'city', 'state', 'slug', 'informations');
            }])
            ->get();

        if (request()->wantsJson()) {

            return response()->json($promotionalDates);
        }
    }

    /**
     * List of promotional dates by city
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function byCity(Request $request)
    {

        $promotionalDates = $this->repository->makeModel()->whereHas('place', function($query) use ($request){

            if($request->get('city') != 'todas-cidades'){
                $query->where('confirmed', true)->where('city', $request->get('city'));
            }

            if($request->get('city') == 'todas-cidades'){
                $query->where('confirmed', true);
            }
        })->with(['place' => function ($query) {
            $query->select('id', 'name', 'city', 'state', 'slug', 'informations');
        }])->get();

        if (request()->wantsJson()) {

            return response()->json($promotionalDates);
        }
    }
}
