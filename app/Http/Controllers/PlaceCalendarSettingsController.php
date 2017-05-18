<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Prettus\Validator\Contracts\ValidatorInterface;
use Prettus\Validator\Exceptions\ValidatorException;
use App\Http\Requests\PlaceCalendarSettingsCreateRequest;
use App\Http\Requests\PlaceCalendarSettingsUpdateRequest;
use App\Repositories\PlaceCalendarSettingsRepository;
use App\Validators\PlaceCalendarSettingsValidator;


class PlaceCalendarSettingsController extends Controller
{

    /**
     * @var PlaceCalendarSettingsRepository
     */
    protected $repository;

    /**
     * @var PlaceCalendarSettingsValidator
     */
    protected $validator;

    public function __construct(PlaceCalendarSettingsRepository $repository, PlaceCalendarSettingsValidator $validator)
    {
        $this->repository = $repository;
        $this->validator  = $validator;
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
        $placeCalendarSetting = $this->repository->find($id);

        if (request()->wantsJson()) {

            return response()->json([
                'data' => $placeCalendarSetting,
            ]);
        }

        return view('placeCalendarSettings.show', compact('placeCalendarSetting'));
    }





    /**
     * Update the specified resource in storage.
     *
     * @param  PlaceCalendarSettingsUpdateRequest $request
     * @param  string            $id
     *
     * @return Response
     */
    public function update(PlaceCalendarSettingsUpdateRequest $request)
    {

        try {

            $this->validator->with($request->all())->passesOrFail(ValidatorInterface::RULE_UPDATE);

            $placeCalendarSetting = $this->repository->update($request->all(), $request->get('id'));

            $response = [
                'message' => 'PlaceCalendarSettings updated.',
                'data'    => $placeCalendarSetting->toArray(),
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
}
