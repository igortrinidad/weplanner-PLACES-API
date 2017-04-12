<?php

namespace App\Http\Controllers\Auth;


use App\Models\User;
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

    public function facebookLogin(Request $request)
    {
        return $this->_social($request, 'facebook', function ($user) {
            return (object) [
                'id' => $user->id,
                'email' => $user->user['email'],
                'name' => $user->user['name'],
                'photo_url' => $user->avatar . '&width=1200'
            ];
        });
    }

    public function facebookReturn(Request $request)
    {
        $user = Socialite::with('facebook')
            ->stateless()
            ->user();

        return response()->json(['user' => $user]);
    }

    private function _social(Request $request, $type, $cb)
    {
        if ($request->has('code')) {
            $new_user = false;

            $social_user = Socialite::with($type)->stateless()->user();

            $social_user = $cb($social_user);

            if ( ! @$social_user->id) {
                return response([
                    'status' => 'error',
                    'code' => 'ErrorGettingSocialUser',
                    'msg' => 'There was an error getting the ' . $type . ' user.'
                ], 400);
            }

            /*
             * find user with social credentials
             */
            $user = User::where($type . '_id', $social_user->id)->first();


            if ( ! ($user instanceof User)) {
                //Find user by social email
                $user = User::where('email', $social_user->email)->first();

                // If user not exists create a new user instance
                if ( ! ($user instanceof User)) {
                    $new_user = true;
                    $user = new User();
                }

                $user->{$type . '_id'} = $social_user->id;
            }
            $full_name = explode(' ', $social_user->name);

            // Update info and save.
            if (empty($user->email)) { $user->email = $social_user->email; }
            if (empty($user->name)) { $user->name = $full_name[0]; }
            if (empty($user->last_name)) { $user->last_name = $full_name[1]; }
            if (empty($user->{$type . '_email'})) { $user->{$type . '_email'} = $social_user->email; }
            if (empty($user->password)) { $user->password = bcrypt(str_random(8)); }

            $user->save();

            if ( ! $token = $this->JWTAuth->fromUser($user)) {
                throw new AuthorizationException;
            }

            return response([
                'status' => 'success',
                'msg' => 'Successfully logged in via ' . $type . '.',
                'token' => $token,
                'user' => $user
            ])
                ->header('Authorization', $token);
        }

        return response([
            'status' => 'success',
            'msg' => 'Successfully fetched token url.',
            'data' => [
                'url' => Socialite::with($type)->stateless()->redirect()->getTargetUrl()
            ]
        ], 200);
    }

    public function user(Request $request)
    {
        $user = User::find(\Auth::user()->id);

        return response([
            'status' => 'success',
            'data' => $user
        ]);
    }

    public function refresh()
    {
        return response([
            'status' => 'success'
        ]);
    }

}