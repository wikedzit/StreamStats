<?php

namespace App\Console\Commands;

use App\Models\Stream;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class UpdateStream extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'stream:update
                            {--limit=1000 : Specify the max number of records you want to fetch}
                            {--no-shuffle : Prevents the system from shuffling fetch data}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Updating the current list of streams with an updated one from Twitch';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        try {
            Cache::put('updating_streams',true);
            $this->info(" ***** Start Updating Stream data ****");

            $limit = $this->option('limit');
            $limit = intval($limit) > 0 ? $limit :1000;

            $shuffle = empty($this->option('no-shuffle'));
            Stream::updateStreamRecords($limit, $shuffle);
            Cache::put('updating_streams',false);
            $this->info(" ***** End Updating Stream data ****");
            return 1;
        } catch (\Exception $exception) {
            Log::error("Command Stream:update FAILED:- ". $exception->getMessage());
            return 0;
        }
    }
}
