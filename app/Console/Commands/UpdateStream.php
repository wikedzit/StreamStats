<?php

namespace App\Console\Commands;

use App\Models\Stream;
use Illuminate\Console\Command;

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

        $this->info(" ***** Start Updating Stream data ****");
        $limit = $this->option('limit');
        if (is_integer($limit)) {
            $limit = 1000;
        }
        $shuffle = empty($this->option('no-shuffle'));
        Stream::updateStreamRecords($limit, $shuffle);
        $this->info(" ***** End Updating Stream data ****");
        return 0;
    }
}
