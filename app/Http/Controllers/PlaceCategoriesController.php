<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Prettus\Validator\Contracts\ValidatorInterface;
use Prettus\Validator\Exceptions\ValidatorException;
use App\Http\Requests\PlaceCategoryCreateRequest;
use App\Http\Requests\PlaceCategoryUpdateRequest;
use App\Repositories\PlaceCategoryRepository;


class PlaceCategoriesController extends Controller
{

    /**
     * @var PlaceCategoryRepository
     */
    protected $repository;


    public function __construct(PlaceCategoryRepository $repository)
    {
        $this->repository = $repository;
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $placeCategories = $this->repository->all();

        return response()->json($placeCategories);
    }
}
