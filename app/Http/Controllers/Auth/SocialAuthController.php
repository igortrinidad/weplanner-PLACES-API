<?php

namespace App\Http\Controllers\Auth;


use App\Models\Client;
use App\Models\ClientSocialProvider;
use App\Models\User;
use App\Models\UserSocialProvider;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Tymon\JWTAuth\JWTAuth;
use Socialite;

class SocialAuthController extends Controller
{

    /**
     * @var JWTAuth
     */
    private $JWTAuth;

    /**
     * SocialAuthController constructor.
     * @param JWTAuth $JWTAuth
     */
    function __construct(JWTAuth $JWTAuth)
    {
        $this->JWTAuth = $JWTAuth;
    }

    public function socialLogin(Request $request)
    {

        /*
         * Handling with a client user
         */
        if($request->has('role') && $request->get('role') == 'client'){

            $clientSocialProvider = ClientSocialProvider::where('provider_id', $request->get('id'))->first();

            if(!$clientSocialProvider)
            {
                //Handle user already logged and want log with facebook too
                if($request->has('user_email')){

                    $user = Client::whereEmail($request->get('user_email'))->first();

                    if($user){
                        $user->socialProviders()->create([
                            'provider' => 'facebook',
                            'provider_id' => $request->get('id'),
                            'access_token' => $request->get('access_token'),
                            'photo_url' => $request->get('photo_url')
                        ]);
                    }
                }

                if(!$request->has('user_email')){

                    //Create client
                    $user = Client::firstOrCreate([
                        'name' => $request->get('first_name'),
                        'last_name' => $request->get('last_name'),
                        'email' => $request->get('email')
                    ]);

                    $user->socialProviders()->create([
                        'provider' => 'facebook',
                        'provider_id' => $request->get('id'),
                        'access_token' =>$request->get('access_token'),
                        'photo_url' => $request->get('photo_url')
                    ]);
                }

            }else{
                $user = $clientSocialProvider->client;
            }
        }

        /*
        * Handling with a admin user
        */
        if($request->has('role') && $request->get('role') == 'admin'){

            $userSocialProvider = UserSocialProvider::where('provider_id', $request->get('id'))->first();

            if(!$userSocialProvider)
            {
                if($request->has('user_email')){

                    $user = User::whereEmail($request->get('user_email'))->first();

                    if($user){
                        $user->socialProviders()->create([
                            'provider' => 'facebook',
                            'provider_id' => $request->get('id'),
                            'access_token' =>$request->get('access_token'),
                            'photo_url' => $request->get('photo_url')
                        ]);
                    }
                }

                if(!$request->has('user_email')){

                    //Create user
                    $user = User::firstOrCreate([
                        'name' => $request->get('first_name'),
                        'last_name' => $request->get('last_name'),
                        'email' => $request->get('email')
                    ]);

                    $user->socialProviders()->create([
                        'provider' => 'facebook',
                        'provider_id' => $request->get('id'),
                        'access_token' =>$request->get('access_token'),
                        'photo_url' => $request->get('photo_url')
                    ]);
                }

            }else{
                $user = $userSocialProvider->user;
            }
        }

        if($user){
            //Verifies if the account belongs to the authenticated user.
            if($request->has('user_id') && $user->id != $request->get('user_id')){
                return response([
                    'status' => 'error',
                    'code' => 'ErrorGettingSocialUser',
                    'msg' => 'Facebook account already in use.'
                ], 400);
            }

            if ( ! $token = $this->JWTAuth->fromUser($user)) {
                throw new AuthorizationException;
            }

            return response([
                'status' => 'success',
                'msg' => 'Successfully logged in via Facebook.',
                'access_token' => $token,
                'user' => $user->load('socialProviders')
            ])->header('Authorization','Bearer '. $token);;
        }

        return response([
            'status' => 'error',
            'code' => 'ErrorGettingSocialUser',
            'msg' => 'Unable to authenticate with Facebook.'
        ], 403);
    }


    private function handleLogin(Request $request, $provider, $callback)
    {
        if ($request->has('code')) {
            $social_user = Socialite::with($provider)->stateless()->user();

            $social_user = $callback($social_user);

            if ( ! @$social_user->id) {
                return response([
                    'status' => 'error',
                    'code' => 'ErrorGettingSocialUser',
                    'msg' => 'There was an error getting the ' . $provider . ' user.'
                ], 400);
            }

            //return response()->json($request->all());


            /*
             * Handling with a admin user
             */
            if($request->has('role') && $request->get('role') == 'admin'){

                $userSocialProvider = UserSocialProvider::where('provider_id', $social_user->id)->first();

                if(!$userSocialProvider)
                {   //Split user full name
                    $splitName = explode(' ', $social_user->name);


                    if($request->has('user_email')){

                        $user = User::whereEmail($request->get('user_email'))->first();

                        if($user){
                            $user->socialProviders()->create(['provider' => $provider, 'provider_id' => $social_user->id, 'access_token' => $social_user->token]);
                        }
                    }

                    if(!$request->has('user_email')){

                        //Create user
                        $user = User::firstOrCreate([
                            'name' => $splitName[0],
                            'last_name' => $splitName[1],
                            'email' => $social_user->email
                        ]);

                        $user->socialProviders()->create(['provider' => $provider, 'provider_id' => $social_user->id, 'access_token' => $social_user->token]);
                    }

                }else{
                    $user = $userSocialProvider->user;
                }
            }

            if($request->has('role') && $request->get('role') == 'client'){

                $ClientSocialProvider = ClientSocialProvider::where('provider_id', $social_user->id)->first();

                if(!$ClientSocialProvider)
                {   //Split user full name
                    $splitName = explode(' ', $social_user->name);


                    if($request->has('user_email')){

                        $user = Client::whereEmail($request->get('user_email'))->first();

                        if($user){
                            $user->socialProviders()->create(['provider' => $provider, 'provider_id' => $social_user->id, 'access_token' => $social_user->token]);
                        }
                    }

                    if(!$request->has('user_email')){

                        //Create user
                        $user = Client::firstOrCreate([
                            'name' => $splitName[0],
                            'last_name' => $splitName[1],
                            'email' => $social_user->email
                        ]);

                        $user->socialProviders()->create(['provider' => $provider, 'provider_id' => $social_user->id, 'access_token' => $social_user->token]);
                    }

                }else{
                    $user = $ClientSocialProvider->client;
                }
            }

            if($user){
                //Verifies if the account belongs to the authenticated user.
                if($request->has('user_id') && $user->id != $request->get('user_id')){
                    return response([
                        'status' => 'error',
                        'code' => 'ErrorGettingSocialUser',
                        'msg' => ucfirst($provider).' already in use.'
                    ], 400);
                }

                if ( ! $token = $this->JWTAuth->fromUser($user)) {
                    throw new AuthorizationException;
                }

                return response([
                    'status' => 'success',
                    'msg' => 'Successfully logged in via ' . $provider . '.',
                    'access_token' => $token,
                    'user' => $user->load('socialProviders')
                ])->header('Authorization','Bearer '. $token);
            }

            return response([
                'status' => 'error',
                'code' => 'ErrorGettingSocialUser',
                'msg' => 'Unable to authenticate with '. ucfirst($provider)
            ], 403);
        }

        return response([
            'status' => 'success',
            'msg' => 'Successfully fetched token url.',
            'data' => [
                'url' => Socialite::with($provider)->stateless()->redirect()->getTargetUrl()
            ]
        ], 200);
    }

    public function user(Request $request)
    {
        $user = \Auth::user();

        $user = $user ? \Auth::guard('admin')->user() : \Auth::guard('client')->user();

        return response()->json(['status' => 'success', 'data' => $user->load('socialProviders')]);
    }

    public function refresh()
    {
        $user = \Auth::user();

        $user = $user ? \Auth::guard('admin')->user() : \Auth::guard('client')->user();
        return response([
            'status' => 'success',
            'data' => $user->load('socialProviders')
        ]);
    }

}