<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Prettus\Validator\Contracts\ValidatorInterface;
use Prettus\Validator\Exceptions\ValidatorException;
use App\Http\Requests\OwnerRequestDocumentCreateRequest;
use App\Http\Requests\OwnerRequestDocumentUpdateRequest;
use App\Repositories\OwnerRequestDocumentRepository;
use App\Validators\OwnerRequestDocumentValidator;


class OwnerRequestDocumentsController extends Controller
{

    /**
     * @var OwnerRequestDocumentRepository
     */
    protected $repository;

    /**
     * @var OwnerRequestDocumentValidator
     */
    protected $validator;

    public function __construct(OwnerRequestDocumentRepository $repository, OwnerRequestDocumentValidator $validator)
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
        $ownerRequestDocuments = $this->repository->all();

        if (request()->wantsJson()) {

            return response()->json([
                'data' => $ownerRequestDocuments,
            ]);
        }

        return view('ownerRequestDocuments.index', compact('ownerRequestDocuments'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  OwnerRequestDocumentCreateRequest $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(OwnerRequestDocumentCreateRequest $request)
    {

        try {

            $this->validator->with($request->all())->passesOrFail(ValidatorInterface::RULE_CREATE);

            $document = $request->file('file');

            $originalName = $document->getClientOriginalName();
            $extension = $document->getClientOriginalExtension();
            $fileName = bin2hex(random_bytes(16)) . '.' . $extension;

            $filePath = 'places/owner_requests/documents/' . $fileName;

            \Storage::disk('media')->put($filePath, file_get_contents($document), 'public');

            //merge file path on request
            $request->merge(['path' => $filePath, 'filename' => $originalName, 'extension' => $extension]);

            $ownerRequestDocument = $this->repository->create($request->all());

            $response = [
                'message' => 'OwnerRequestDocument created.',
                'document'    => $ownerRequestDocument->toArray(),
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
        $ownerRequestDocument = $this->repository->find($id);

        if (request()->wantsJson()) {

            return response()->json([
                'data' => $ownerRequestDocument,
            ]);
        }

        return view('ownerRequestDocuments.show', compact('ownerRequestDocument'));
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

        $ownerRequestDocument = $this->repository->find($id);

        return view('ownerRequestDocuments.edit', compact('ownerRequestDocument'));
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  OwnerRequestDocumentUpdateRequest $request
     * @param  string            $id
     *
     * @return Response
     */
    public function update(OwnerRequestDocumentUpdateRequest $request, $id)
    {

        try {

            $this->validator->with($request->all())->passesOrFail(ValidatorInterface::RULE_UPDATE);

            $ownerRequestDocument = $this->repository->update($request->all(), $id);

            $response = [
                'message' => 'OwnerRequestDocument updated.',
                'data'    => $ownerRequestDocument->toArray(),
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
        $document = $this->repository->find($id);

        \Storage::disk('media')->delete($document->path);

        $deleted = $document->delete();

        if (request()->wantsJson()) {

            return response()->json([
                'message' => 'OwnerRequestDocument deleted.',
                'deleted' => $deleted,
            ]);
        }

        return redirect()->back()->with('message', 'OwnerRequestDocument deleted.');
    }
}
