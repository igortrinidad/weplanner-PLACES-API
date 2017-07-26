<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Models\Decoration;



class DecorationsController extends Controller
{
	/**
     * ...
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $id = $request->get('id');
        $place_id = $request->get('place_id');

        $decoration = Decoration::create($request->all());

    }
}