<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class CleanupOldNotifications extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notifications:cleanup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clean up notifications older than 24 hours';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $deletedCount = DB::table('notifications')
            ->where('created_at', '<', now()->subHours(24))
            ->delete();

        $this->info("Cleaned up {$deletedCount} old notifications.");
        
        return Command::SUCCESS;
    }
}

