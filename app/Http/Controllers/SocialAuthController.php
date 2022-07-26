<?php

namespace App\Http\Controllers;

use App\Integrations\Twitch;
use App\Models\User;
use Carbon\Carbon;
use Firebase\JWT\JWT;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Laravel\Socialite\Facades\Socialite;

class SocialAuthController extends Controller
{
    public function __construct()
    {
        $this->skipAuthenticate = ['callback'];
        parent::__construct();
    }

    public function callback() {
        try {
            $twitchUser = Socialite::driver('twitch')
                ->stateless()
                ->user();

            $user = User::firstOrNew(['twitch_id' =>  $twitchUser->id]);
            $user->name             =  $twitchUser->name;
            $user->email            =  $twitchUser->email;
            $user->avatar           =  $twitchUser->avatar;
            $user->twitch_id        =  $twitchUser->id;
            $user->access_token     =  $twitchUser->token;
            $user->refresh_token    =  $twitchUser->refreshToken;
            $user->save();

            $tokenPayload = [
                "uid" => $user->twitch_id,
                'iat' => time(),
                'exp' => time() + config('auth.jwt.ttl')
            ];
            Auth::login($user);
            $token = JWT::encode($tokenPayload, config('auth.jwt.key'), 'HS256');
            $payload = [
                'username'      => $user->name,
                'access_token'  => $token,
                'avatar'        => $user->avatar
            ];

            return response()->json($payload, 200);
        } catch (\Exception $exception) {
            // Log the detailed error and return a user friendly message
            // It is also good to track exception base on their types to avoid returning generic messages
            Log::error("SocialAuthController: ". $exception->getMessage());
            return response()->json(['message' => 'Authentication Failed'], 200);
        }
    }

    public function logout() {
        if (Auth::check()) {
            $user = Auth::user();
            Twitch::revokeAccessToken($user->access_token);
        }
        Auth::logout();
        return response()->json(['message'=>'Session Closed'], 200);
    }
}
