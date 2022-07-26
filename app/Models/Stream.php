<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stream extends Model
{
    use HasFactory;

    public $incrementing    = false;
    protected $primaryKey   = 'stream_id';
    protected $keyType      = 'string';

    protected $dates = [
        'started_at'
    ];

    protected $fillable = [
        'stream_id', 'game_name', 'title', 'viewer_count', 'started_at', 'channel_name', 'tag_ids'
    ];

}
