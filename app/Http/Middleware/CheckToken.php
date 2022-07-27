<?php

namespace App\Http\Middleware;

use Closure;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $token = $request->bearerToken();
        if (empty($token)) {
            return response()->json(
                ['message' => "Access denied, missing credentials"], 403);
        }

        JWT::$leeway = 60; // $leeway in seconds
        $decoded = JWT::decode($token, new Key(env('JWT_KEY'), 'HS256'));
        if(!empty($decoded) && !empty($decoded->user_id)) {
            $user = User::where('twitch_id', $decoded->user_id)->first();

            // At this point we also need to confirm the validity of Twitch token just to be user this user access is
            // still valid even if the session is not expired

            // Authenitcate the user so that the flow remain valid when accessing next resources
            Auth::login($user);
        } else {
            return response()->json(['message' => "Invalid token"], 403);
        }
        return $next($request);
    }
}
