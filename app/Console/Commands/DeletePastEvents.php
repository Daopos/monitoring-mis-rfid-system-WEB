<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class DeletePastEvents extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'events:delete-past';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete events where the end datetime is past';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        // Get the current datetime
        $now = Carbon::now();

        // Delete events with `end` datetime in the past
        DB::table('eventdos')->where('end', '<', $now)->delete();

        // Output success message
        $this->info('Past events have been deleted.');

        return 0;
    }
}
