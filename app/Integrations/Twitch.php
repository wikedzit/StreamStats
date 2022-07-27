<?php

namespace App\Integrations;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class Twitch
{
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
                    Log::error("TWITCH APP Authorization failed: Missing APP Access Token");
                    throw new \Exception($response->body());
                }
            } else {
                Log::error("TWITCH APP Authorization failed:". $response->body());
                throw new \Exception("TWITCH APP Authorization failed:");
            }
        } catch (\Exception $exception) {
            Log::error("TWITCH APP Authorization failed:". $exception->getMessage());
            throw $exception;
        }
    }

    public static function getStreams(string $endpoint, $headers=[], $params=[]) {
        try {
            $endpoint = trim($endpoint, "/");
            if (empty(config('app.twitch.helix_url'))) {
                throw new \Exception("Fetch Stream Failed: Missing Helix Base URL");
            }
            $url = sprintf("%s/%s", config('app.twitch.helix_url'), $endpoint);
            if ($params) {
                $url = sprintf("%s?%s", $url, http_build_query($params));
            }

            $response = Http::withHeaders($headers)->get($url);
            return $response;
        } catch (\Exception $exception) {
            throw $exception;
        }
    }

    public static function getGeneralStreams(int $first = 100, string $cursor="") {
        $headers = [
            "Authorization" => "Bearer ". config('app.twitch.token'),
            'Client-Id'     =>  config('app.twitch.client_id')
        ];
        $params = ["first" => $first];
        if (!empty($cursor)) {
            $params['after'] = $cursor;
        }
        return self::getStreams('/streams',$headers, $params);
    }

    public static function getUserFollowedStreams(int $first = 100, string $cursor="") {
        $user = Auth::user();
        $headers = [
            "Authorization" => "Bearer ". $user->access_token,
            'Client-Id'     =>  config('app.twitch.client_id')
        ];
        $params = [
            "user_id"   => $user->twitch_id,
            "first"     => $first
        ];

        if (!empty($cursor)) {
            $params['after'] = $cursor;
        }
        return self::getStreams('/streams/followed', $headers, $params);
    }

    public static function loadStreams(bool $isFollowed, int $first = 100, string $cursor="") {
        if ($isFollowed) {
            return Twitch::getUserFollowedStreams($first, $cursor);
        } else {
            return Twitch::getGeneralStreams($first, $cursor);
        }
    }
}
