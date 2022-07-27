<?php

namespace App\Integrations;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class Twitch
{
    public static function get(string $uri, array $headers, array $payload) {

    }

    public static function authorizeApp() {
        try {
            $url = sprintf("%s/%s", config('app.twitch.auth_url'), 'token');
            $credentials = [
                'client_id'     => config('app.twitch.client_id'),
                'client_secret' => config('app.twitch.secret'),
                'grant_type'    => "client_credentials"
            ];
            $response = Http::asForm()->post($url, $credentials);
            if ($response->ok()) {
                $access_token = $response->json('access_token');
                if (!empty($access_token)) {
                    config(['app.twitch.token' => $access_token]);
                    return $access_token;
                } else {
                    throw new \Exception($response->body());
                }
            } else {
                Log::error("TWITCH APP Authorization failed:". $response->body());
                throw new \Exception("TWITCH APP Authorization failed:");
            }
        } catch (\Exception $exception) {
            Log::error("TWITCH APP Authorization failed:". $exception->getMessage());
        }
    }
}
