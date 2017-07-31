<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Prettus\Validator\Contracts\ValidatorInterface;
use Prettus\Validator\Exceptions\ValidatorException;
use App\Http\Requests\DecorationPhotoCreateRequest;
use App\Http\Requests\DecorationPhotoUpdateRequest;
use App\Repositories\DecorationPhotoRepository;


class DecorationPhotosController extends Controller
{

    /**
     * @var ServiceAdPhotoRepository
     */
    protected $repository;

    /**
     * @var ServiceAdPhotoValidator
     */
    protected $validator;

    public function __construct(DecorationPhotoRepository $repository)
    {
        $this->repository = $repository;

        \Tinify\setKey(env('TINIFY_APY_KEY', null));
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  DecorationPhotoCreateRequest $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(DecorationPhotoCreateRequest $request)
    {
        try {

            $image = $request->file('image');

            $fileName = bin2hex(random_bytes(16)) . '.' . $image->getClientOriginalExtension();

            $filePath = 'decorations/' . $fileName;

            //tinify
            $sourceData = file_get_contents($image);
            $resultData = \Tinify\fromBuffer($sourceData)
                ->resize([
                    "method" => "scale",
                    "width" => 1400,
                ])->toBuffer();

            \Storage::disk('media')->put($filePath, $resultData, 'public');

            //merge file path on request
            $request->merge(['path' => $filePath]);

            $serviceAdPhoto = $this->repository->create($request->all());

            $response = [
                'message' => 'ServiceAdPhoto created.',
                'photo'    => $serviceAdPhoto->fresh()->toArray(),
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
                'message' => 'ServiceAdPhoto deleted.',
                'deleted' => $deleted,
            ]);
        }

        return redirect()->back()->with('message', 'ServiceAdPhoto deleted.');
    }
}
