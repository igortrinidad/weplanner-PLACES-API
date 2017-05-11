<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Prettus\Validator\Contracts\ValidatorInterface;
use Prettus\Validator\Exceptions\ValidatorException;
use App\Http\Requests\PlaceDocumentCreateRequest;
use App\Http\Requests\PlaceDocumentUpdateRequest;
use App\Repositories\PlaceDocumentRepository;
use App\Validators\PlaceDocumentValidator;


class PlaceDocumentsController extends Controller
{

    /**
     * @var PlaceDocumentRepository
     */
    protected $repository;

    /**
     * @var PlaceDocumentValidator
     */
    protected $validator;

    public function __construct(PlaceDocumentRepository $repository, PlaceDocumentValidator $validator)
    {
        $this->repository = $repository;
        $this->validator  = $validator;
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  PlaceDocumentCreateRequest $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(PlaceDocumentCreateRequest $request)
    {

        try {

            $this->validator->with($request->all())->passesOrFail(ValidatorInterface::RULE_CREATE);

            $document = $request->file('file');

            $originalName = $document->getClientOriginalName();
            $extension = $document->getClientOriginalExtension();
            $fileName = bin2hex(random_bytes(16)) . '.' . $extension;

            $filePath = 'places/documents/' . $fileName;

            \Storage::disk('media')->put($filePath, file_get_contents($document), 'public');

            //merge file path on request
            $request->merge(['path' => $filePath, 'filename' => $originalName, 'extension' => $extension]);

            $placeDocument = $this->repository->create($request->all());

            $response = [
                'message' => 'PlaceDocument created.',
                'document'    => $placeDocument->fresh()->toArray(),
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
     * Update the specified resource in storage.
     *
     * @param  PlaceDocumentUpdateRequest $request
     * @param  string            $id
     *
     * @return Response
     */
    public function update(PlaceDocumentUpdateRequest $request, $id)
    {

        try {

            $this->validator->with($request->all())->passesOrFail(ValidatorInterface::RULE_UPDATE);

            $placeDocument = $this->repository->update($request->all(), $id);

            $response = [
                'message' => 'PlaceDocument updated.',
                'data'    => $placeDocument->toArray(),
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
                'message' => 'PlaceDocument deleted.',
                'deleted' => $deleted,
            ]);
        }

        return redirect()->back()->with('message', 'PlaceDocument deleted.');
    }
}
