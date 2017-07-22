<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Requests;

use App\Models\Post;


class LandingController extends Controller
{


    /**
     * Index
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        return view('landing.index', compact('posts'));

    }

    /**
     * Index teste
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function indextwo()
    {
        $posts = Post::where('post_status', 'publish')->get();

        dd($posts);

        return view('landing.index', compact('posts'));

    }

    
}

