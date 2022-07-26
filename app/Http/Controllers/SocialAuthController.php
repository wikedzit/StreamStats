<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Laravel\Socialite\Facades\Socialite;

class SocialAuthController extends Controller
{

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

            Auth::login($user);

            $token = "";
            $payload = [
                'user'          => $user->getProfile(),
                'access_token'  => $token,
            ];
            return response()->json($payload, 200);
        } catch (\Exception $exception) {
            // Log the detailed error and return a user friendly message
            // It is also good to track exception base on their types to avoid returning generic messages
            Log::error("SocialAuthController: ". $exception->getMessage());
            return response()->json(['message' => 'Login Failed'], 200);
        }
    }
}
