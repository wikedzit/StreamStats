<?php

namespace App\Integrations;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class Twitch
{
    public static function hasActiveTwitchAccess(User $user) {
        try {
            $url = sprintf("%s/%s", config('app.twitch.auth_url'), 'validate');
            $response = Http::withHeaders([
                'Authorization' => sprintf("OAuth %s", $user->access_token)
            ])->get($url);

            if ($response->ok()) {
                $client_id  = $response->json('client_id');
                $user_id    =  $response->json('user_id');

                if (!empty($client_id)  && !empty($user)) {
                    $validity = $user->twitch_id == $user_id && config('app.twitch.client_id') == $client_id;
                    return $validity;
                }
            }
        } catch (\Exception $exception) {
            Log::error("TWITCH Token Validation failed: Missing APP Access Token");
        }
        return false;
    }

    public static function revokeAccessToken(string $token) {
        try {
            $url = sprintf("%s/%s", config('app.twitch.auth_url'), 'revoke');
            $credentials = [
                'client_id' => config('app.twitch.client_id'),
                'token'     =>  $token
            ];
            $response = Http::asForm()->post($url, $credentials);
            if ($response->ok()) {
                return true;
            }
            $message = "FAILED TO REVOKE ACCSS_TOKEN:- ". $response->body();
        } catch (\Exception $exception) {
            $message = "FAILED TO REVOKE ACCSS_TOKEN:-". $exception->getMessage();
        }
        Log::error($message);
        return false;
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

    public static function getAppAccessToken() {
         $token = config('app.twitch.token');
         if(!empty($token)) {
             return $token;
         }
         return self::authorizeApp();
    }

    public static function getTagDetails($tags=[]) {
        try {
            $headers = [
                "Authorization" => "Bearer ". self::getAppAccessToken(),
                'Client-Id'     =>  config('app.twitch.client_id')
            ];

            $url = sprintf("%s/tags/streams", config('app.twitch.helix_url'));
            $taglist = "";
            foreach ($tags as $tag) {
                $taglist.=sprintf("tag_id=%s&", $tag);
            }
            $taglist = trim($taglist, '&');
            $url = sprintf("%s?%s", $url,$taglist);
            $response = Http::withHeaders($headers)->get($url);
            if ($response->ok()) {
                $tags = $response->json('data');
                if (!empty($tags)) {
                    return $tags;
                } else {
                    Log::warning("TAG DATA NOTFOUND:-", $response->body());
                }
            } else {
                Log::error("TAG FETCH FAILED:". $response->body());
            }
        } catch (\Exception $exception) {
            Log::error("TAG FETCH ERROR:". $exception->getMessage());
        }
        return [];
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
            "Authorization" => "Bearer ". self::getAppAccessToken(),
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
