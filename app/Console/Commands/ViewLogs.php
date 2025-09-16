<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ViewLogs extends Command
{


    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'logs:tail {lines=50}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'View recent log entries';

    /**
     * Execute the console command.
     */

    public function handle()
    {
        $lines = $this->argument('lines');
        $logPath = storage_path('logs/laravel.log');

        if (!file_exists($logPath)) {
            $this->error('Log file not found: ' . $logPath);
            return;
        }

        $this->info("Showing last {$lines} lines from laravel.log:");
        $this->line('----------------------------------------');

        $command = "tail -{$lines} {$logPath}";
        $output = shell_exec($command);

        $this->line($output);
    }
}
