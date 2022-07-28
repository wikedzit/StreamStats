<?php

namespace App\Http\Middleware;

use App\Integrations\Twitch;
use Closure;
use Firebase\JWT\ExpiredException;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

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
        try {
            $token = $request->bearerToken();
            if (empty($token)) {
                return response()->json(
                    ['message' => "Access denied, missing credentials"], 401);
            }

            JWT::$leeway = 60; // $leeway in seconds
            $decoded = JWT::decode($token, new Key(config('auth.jwt.key'), 'HS256'));
            if(!empty($decoded) && !empty($decoded->uid)) {
                $user = User::where('twitch_id', $decoded->uid)->first();
                // At this point we also need to confirm the validity of Twitch token just to be sure that this user
                // access is still valid even if the session is not expired
                if (Twitch::hasActiveTwitchAccess($user)) {
                    // Authenticate the user so that the flow remain valid when accessing next resources
                    Auth::login($user);
                } else {
                    $user->update([
                        'access_token'  => "",
                        'refresh_token' => ""
                    ]);
                    return response()->json(['message' => "Invalid Access"], 401);
                }
            } else {
                return response()->json(['message' => "Invalid token"], 401);
            }
            return $next($request);
        } catch (ExpiredException) {
            return response()->json(['message' => "Token Expired"], 401);
        } catch (\Exception $exception) {
            Log::error("Token Error: ". $exception->getMessage());
            return response()->json(['message' => 'Broken Token'], 401);
        }
    }
}
