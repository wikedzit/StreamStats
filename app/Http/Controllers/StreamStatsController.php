<?php

namespace App\Http\Controllers;

use App\Models\Stream;
use Illuminate\Http\Request;

class StreamStatsController extends Controller
{
    public function getStats() {
        $stats = Stream::getStats();
        return response()->json($stats);
    }
}
