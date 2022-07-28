<?php

namespace App\Http\Controllers;

use App\Models\Stream;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class StreamStatsController extends Controller
{
    public function getStats() {
        $stats = Stream::getStats();
        if (Cache::get('updating_streams')) {
            $stats = ["message" => "loading"];
        }
        return response()->json($stats);
    }
}
