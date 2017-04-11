<?php

namespace App\Http\Controllers\Auth;


use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Socialite;

class SocialAuthController extends Controller
{
    public function facebookLogin()
    {
        return Socialite::with('facebook')->stateless()->redirect()->getTargetUrl();
    }

    public function facebookReturn(Request $request)
    {
        $user = Socialite::with('facebook')
            ->stateless()
            ->user();

        return response()->json(['user' => $user]);
    }
}