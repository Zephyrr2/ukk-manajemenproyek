<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Time_Log;

class FixTimeLogDurations extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'timelog:fix-durations';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fix duration_minutes for all time logs that have 0 or negative values';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting to fix time log durations...');

        // Get all time logs that need fixing
        $timeLogs = Time_Log::whereNotNull('end_time')
            ->where(function($query) {
                $query->where('duration_minutes', '<=', 0)
                      ->orWhereNull('duration_minutes');
            })
            ->get();

        if ($timeLogs->count() === 0) {
            $this->info('No time logs need fixing!');
            return 0;
        }

        $this->info("Found {$timeLogs->count()} time logs to fix...");

        $fixed = 0;
        $failed = 0;

        foreach ($timeLogs as $log) {
            try {
                if ($log->start_time && $log->end_time) {
                    // Calculate correct duration (use abs to handle any ordering issues)
                    $duration = abs($log->start_time->diffInMinutes($log->end_time, false));
                    $duration = round($duration); // Round to nearest minute
                    $duration = max(0, $duration); // Ensure non-negative

                    $log->update([
                        'duration_minutes' => $duration,
                        'description' => "Work completed - " . $this->formatDuration($duration)
                    ]);

                    $fixed++;
                    $this->line("Fixed time log #{$log->id}: {$duration} minutes");
                }
            } catch (\Exception $e) {
                $failed++;
                $this->error("Failed to fix time log #{$log->id}: " . $e->getMessage());
            }
        }

        $this->info("\n✅ Fixed: {$fixed} time logs");
        if ($failed > 0) {
            $this->error("❌ Failed: {$failed} time logs");
        }

        return 0;
    }

    private function formatDuration($minutes)
    {
        if ($minutes == 0) {
            return '0m';
        }

        $hours = intdiv($minutes, 60);
        $remainingMinutes = $minutes % 60;

        if ($hours > 0) {
            return $remainingMinutes > 0 ? "{$hours}h {$remainingMinutes}m" : "{$hours}h";
        }

        return "{$remainingMinutes}m";
    }
}
