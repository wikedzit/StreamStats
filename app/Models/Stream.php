<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\LoadStreams;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class Stream extends Model
{
    use HasFactory, LoadStreams;

    public $incrementing    = false;
    protected $primaryKey   = 'stream_id';
    protected $keyType      = 'string';

    protected $dates = [
        'started_at'
    ];

    protected $fillable = [
        'stream_id', 'game_name', 'title', 'viewer_count', 'started_at', 'channel_name', 'tag_ids'
    ];

    public static function updateStreamRecords(int $totalRecord=1000, bool $shuffle=true) {
        try {
            // TODO it might be a good idea to notify consumers of this data that something is happening
            Stream::truncate();

            $data = Stream::loadStreams(false, $totalRecord);
            if ($shuffle && is_array($data)) {
                shuffle($data);
            }
            foreach ($data as $datum) {
                if (is_array($datum['tag_ids'])) {
                    $tag_id = implode(",", $datum['tag_ids']);
                } else {
                    $tag_id = $datum['tag_ids'];
                }
                $row = [
                    'stream_id'     => $datum['stream_id'],
                    'game_name'     => $datum['game_name'],
                    'title'         => $datum['title'],
                    'viewer_count'  => $datum['viewer_count'],
                    'channel_name'  => "",
                    'started_at'    => $datum['started_at'],
                    'tag_ids'       => $tag_id
                ];

                // NOTE this process could be differed to a parallel Pool making a good number of parallel Queries to the DB
                // this could optimize the performance but
                Stream::create($row);
            }
        } catch (\Exception $exception) {
            Log::error("StreamRecordsUpdate FAILED:- ". $exception->getMessage());
        }
    }

    public static function getStats() {
        $median = self::viewersMedian();
        $topgames = self::topGames();
        $gamestreams = self::gameStreams();
        $topstreams = self::getTopStreams();
        $streamcounts = self::getStreamCountPerStartHour();

        //Fetch User data once and reuse it
        $data = User::getFollowing();
        $gaptotop = self::leastStreamGapToTopStream($data);
        $followedstreams = self::userFollowedStreams($data);
        $sharedtags = self::getSharedTags($data);

        $stats = [
            'median' => $median,
            'topgames' => $topgames,
            'gamestreams' => $gamestreams,
            'gaptotop'=>$gaptotop,
            'topstreams' => $topstreams,
            'followedstreams' => $followedstreams,
            'sharedtags' => $sharedtags,
            'streamcounts' => $streamcounts
        ];

        return $stats;
    }

    // TODO move all the queries in this controller to a Trait/Model to keep it thin
    public static function gameStreams() {
        try {
            $gamestreams = DB::table('streams')
                ->select(DB::raw('count(*) as streams_count, game_name'))
                ->groupBy('game_name')
                ->orderBy('streams_count', 'DESC')
                ->limit(10)
                ->get()->toArray();
            return $gamestreams;
        } catch (\Exception $exception) {
            Log::error("GAMES STAT FAILED:- ". $exception->getMessage());
            return [];
        }
    }

    public static function topGames() {
        try {
            $topgames = DB::table('streams')
                ->select(DB::raw('SUM(viewer_count) as view_count, game_name'))
                ->groupBy('game_name')
                ->orderBy('view_count', 'DESC')
                ->limit(10)
                ->get()->toArray();
            return $topgames;
        } catch (\Exception $exception) {
            Log::error("TOP GAMES STATS FAILED:- ". $exception->getMessage());
            return [];
        }
    }

    public static function viewersMedian() {
        try {
            $viewers_median = Stream::pluck('viewer_count')->median();
            return $viewers_median;
        } catch (\Exception $exception) {
            Log::error("TOP GAMES STATS FAILED:- ". $exception->getMessage());
            return [];
        }
    }

    public static function leastStreamGapToTopStream($data=null) {
        try {
            if(is_null($data)){
                $data = User::getFollowing();
            }
            $topViewCounts = DB::table('streams')->min('viewer_count');
            $followedStream = end($data);
            return [
                'title' => $followedStream['title'],
                'gap' => $followedStream['viewer_count'] - $topViewCounts
            ];
        } catch (\Exception $exception) {
            Log::error("STREAM GAP TO TOP STATS FAILED:- ". $exception->getMessage());
            return [];
        }
    }

    public static function getTopStreams() {
        try {
            $topstreams = DB::table('streams')
                ->select(DB::raw('title, viewer_count, game_name'))
                ->orderBy('viewer_count', 'DESC')
                ->limit(10)
                ->get()->toArray();
            return $topstreams;
        } catch (\Exception $exception) {
            Log::error("TOP STREAM STATS FAILED:- ". $exception->getMessage());
            return [];
        }
    }

    public static function userFollowedStreams($data=null) {
        try {
            if(is_null($data)){
                $data = User::getFollowing();
            }
            $keys = array_keys($data);
            $results = Stream::whereIn('stream_id', $keys)->get()->toArray();
            return $results;
        } catch (\Exception $exception) {
            Log::error("USER FOLLOWED STREAM STATS FAILED:- ". $exception->getMessage());
            return [];
        }
    }

    public static function getSharedTags($data=null) {
        try {
            if(is_null($data)){
                $data = User::getFollowing();
            }
            $userTags = [];
            foreach($data as $datum) {
                if (!empty($datum['tag_ids'])) {
                    if (is_array($datum['tag_ids'])) {
                        $userTags=array_merge($userTags, $datum['tag_ids']);
                    }else {
                        $userTags[] = $datum['tag_ids'];
                    }
                }
            }

            $topTags =[];
            $tagIds = Stream::pluck('tag_ids')->toArray();
            foreach ($tagIds as $tagId) {
                if(!empty($tagId)) {
                    $tags = explode(',', $tagId);
                    $topTags=array_merge($topTags, $datum['tag_ids']);
                }
            }
            $sharedTags = array_intersect($userTags, $topTags);
            return $sharedTags;
        } catch (\Exception $exception) {
            Log::error("SHARED TAGS STATS FAILED:- ". $exception->getMessage());
            return [];
        }
    }

    public static function getStreamCountPerStartHour() {
        try {
            $stream_count = DB::table('streams')
                ->select(DB::raw('count(*) as stream_count,
                   HOUR(DATE_ADD(
                           DATE_FORMAT(streams.started_at, "%Y-%m-%d %H:00:00"),
                           INTERVAL IF(MINUTE(streams.started_at) < 30, 0, 1) HOUR
                       )) AS start_hour'))
                ->groupBy('start_hour')
                ->orderBy('start_hour', 'DESC')
                ->get()->toArray();
            return $stream_count;
        } catch (\Exception $exception) {
            Log::error("STREAM COUNT START HOUR STATS FAILED:- ". $exception->getMessage());
            return [];
        }
    }
}
