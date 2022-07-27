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
        $stats = array_merge([], $median);
        return $stats;
    }

    // TODO move all the queries in this controller to a Trait/Model to keep it thin
    public static function gamesStats() {
        try {
            $games = DB::table('streams')
                ->select(DB::raw('count(*) as streams_count, game_name'))
                ->groupBy('game_name')
                ->orderBy('streams_count', 'DESC')
                ->limit(10)
                ->get()->toArray();
            return $games;
        } catch (\Exception $exception) {
            Log::error("GAMES STAT FAILED:- ". $exception->getMessage());
            return [];
        }
    }

    public static function topGames() {
        try {
            $top_games = DB::table('streams')
                ->select(DB::raw('SUM(viewer_count) as view_count, game_name'))
                ->groupBy('game_name')
                ->orderBy('view_count', 'DESC')
                ->limit(10)
                ->get()->toArray();
            return $top_games;
        } catch (\Exception $exception) {
            Log::error("TOP GAMES STATS FAILED:- ". $exception->getMessage());
            return [];
        }
    }

    public static function viewersMedian() {
        try {
            $views= Stream::pluck('viewer_count')->median();
            return ['viewers_median' => $views ];
        } catch (\Exception $exception) {
            Log::error("TOP GAMES STATS FAILED:- ". $exception->getMessage());
            return [];
        }
    }
}
