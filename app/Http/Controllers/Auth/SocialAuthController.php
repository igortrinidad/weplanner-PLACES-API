<?php

namespace App\Http\Controllers\Auth;


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

    public function socialLogin(Request $request, $provider)
    {
        return $this->handleLogin($request, $provider, function ($user) {
            return (object) [
                'id' => $user->id,
                'email' => $user->email,
                'name' => $user->name,
                'photo_url' => $user->avatar . '&width=1200',
                'token' => $user->token . '&width=1200'
            ];
        });
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

            /*
             * find user by social credentials
             */
            $socialProvider = UserSocialProvider::where('provider_id', $social_user->id)->first();

            if(!$socialProvider)
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
                $user = $socialProvider->user;
            }

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
                'token' => $token,
                'user' => $user->load('socialProviders')
            ])
                ->header('Authorization', $token);
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

        return response()->json(['status' => 'success', 'data' => $user]);
    }

    public function refresh()
    {
        return response([
            'status' => 'success'
        ]);
    }

}