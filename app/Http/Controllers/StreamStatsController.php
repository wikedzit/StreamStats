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
            // TODO at this point we can even retry N times before sending feedback to user
            // This logic could even be places in a Middleware
            $stats = ["message" => "loading"];
        }
        return response()->json($stats);
    }
}
