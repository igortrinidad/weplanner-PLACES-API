<?php

namespace App\Http\Controllers;

use App\Validators\PlacePhotoValidator;
use Illuminate\Http\Request;

use App\Http\Requests;
use Prettus\Validator\Contracts\ValidatorInterface;
use Prettus\Validator\Exceptions\ValidatorException;
use App\Http\Requests\PlacePhotoCreateRequest;
use App\Http\Requests\PlacePhotoUpdateRequest;
use App\Repositories\PlacePhotoRepository;


class PlacePhotosController extends Controller
{

    /**
     * @var PlacePhotoRepository
     */
    protected $repository;
    /**
     * @var PlacePhotoValidator
     */
    private $validator;

    /**
     * PlacePhotosController constructor.
     * @param PlacePhotoRepository $repository
     * @param PlacePhotoValidator $validator
     */
    public function __construct(PlacePhotoRepository $repository, PlacePhotoValidator $validator)
    {
        $this->repository = $repository;
        $this->validator = $validator;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  PlacePhotoCreateRequest $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(PlacePhotoCreateRequest $request)
    {
        try {
            $this->validator->with($request->all())->passesOrFail(ValidatorInterface::RULE_CREATE);

            $image = $request->file('image');

            $fileName = bin2hex(random_bytes(16)) . '.' . $image->getClientOriginalExtension();

            $filePath = 'places/media/' . $fileName;

            \Storage::disk('media')->put($filePath, file_get_contents($image), 'public');

            //merge file path on request
            $request->merge(['path' => $filePath]);

            $placePhoto = $this->repository->create($request->all());

            $response = [
                'message' => 'PlacePhoto created.',
                'photo'    => $placePhoto->fresh()->toArray(),
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
        $photo = $this->repository->find($id);

        \Storage::disk('media')->delete($photo->path);

        $deleted = $photo->delete();

        if (request()->wantsJson()) {

            return response()->json([
                'message' => 'PlacePhoto deleted.',
                'deleted' => $deleted,
            ]);
        }

        return redirect()->back()->with('message', 'PlacePhoto deleted.');
    }
}
