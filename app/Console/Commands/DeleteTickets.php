<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DeleteTickets extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:delete-tickets';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete tickets created on day before yesterday';

    /**
     * Execute the console command.
     */
    public function handle()
    {
         $targetDate = Carbon::now()->subDays(2)->toDateString();

        $deletedCount = DB::table('tickets')
            ->whereDate('created_on', $targetDate)  // use your actual column name
            ->delete();

        $this->info("Deleted $deletedCount ticket(s) from $targetDate.");
    
    }
}
