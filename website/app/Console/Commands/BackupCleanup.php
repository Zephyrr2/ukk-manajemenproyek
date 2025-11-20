<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Carbon\Carbon;

class BackupCleanup extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'backup:cleanup {--days=7} {--path=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clean up old backup files (default: older than 7 days)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        try {
            $retentionDays = (int) $this->option('days');
            $backupPath = $this->option('path') ?: storage_path('backups');

            $this->info("Starting backup cleanup...");
            $this->info("Retention period: {$retentionDays} days");
            $this->info("Backup path: {$backupPath}");

            if (!file_exists($backupPath)) {
                $this->warn("Backup directory does not exist: {$backupPath}");
                return 0;
            }

            $cutoffDate = Carbon::now()->subDays($retentionDays);
            $this->info("Deleting backups older than: " . $cutoffDate->format('Y-m-d H:i:s'));

            $deletedCount = 0;
            $totalSize = 0;

            // Scan backup directory recursively
            $files = File::allFiles($backupPath);

            foreach ($files as $file) {
                $fileTime = Carbon::createFromTimestamp($file->getMTime());

                if ($fileTime->lessThan($cutoffDate)) {
                    $filesize = $file->getSize();
                    $totalSize += $filesize;

                    $this->line("Deleting: " . $file->getFilename() . " (" . $this->formatBytes($filesize) . ")");

                    try {
                        unlink($file->getPathname());
                        $deletedCount++;
                    } catch (\Exception $e) {
                        $this->error("Failed to delete: " . $file->getFilename() . " - " . $e->getMessage());
                    }
                }
            }

            $this->newLine();
            $this->info("Cleanup Summary:");
            $this->info("================");
            $this->info("Files deleted: {$deletedCount}");
            $this->info("Space freed: " . $this->formatBytes($totalSize));

            if ($deletedCount > 0) {
                $this->info("✓ Cleanup completed successfully!");
            } else {
                $this->info("No old backups found to delete.");
            }

            return 0;

        } catch (\Exception $e) {
            $this->error('Cleanup failed: ' . $e->getMessage());
            return 1;
        }
    }

    /**
     * Format bytes to human readable format
     */
    private function formatBytes($bytes, $precision = 2)
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        $bytes /= (1 << (10 * $pow));

        return round($bytes, $precision) . ' ' . $units[$pow];
    }
}
