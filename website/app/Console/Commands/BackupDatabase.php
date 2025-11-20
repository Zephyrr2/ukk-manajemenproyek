<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class BackupDatabase extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'backup:database {--compress} {--path=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Backup database to storage folder';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        try {
            $this->info('Starting database backup...');

            // Get database configuration
            $dbHost = config('database.connections.mysql.host');
            $dbPort = config('database.connections.mysql.port');
            $dbName = config('database.connections.mysql.database');
            $dbUser = config('database.connections.mysql.username');
            $dbPassword = config('database.connections.mysql.password');

            // Create backup directory if not exists
            $backupPath = $this->option('path') ?: storage_path('backups/database');

            if (!file_exists($backupPath)) {
                mkdir($backupPath, 0755, true);
                $this->info("Created backup directory: {$backupPath}");
            }

            // Generate backup filename with timestamp
            $timestamp = Carbon::now()->format('Y-m-d_His');
            $filename = "backup_{$dbName}_{$timestamp}.sql";
            $filepath = $backupPath . '/' . $filename;

            // Build mysqldump command
            $command = sprintf(
                'mysqldump --user=%s --password=%s --host=%s --port=%s %s > %s',
                escapeshellarg($dbUser),
                escapeshellarg($dbPassword),
                escapeshellarg($dbHost),
                escapeshellarg($dbPort),
                escapeshellarg($dbName),
                escapeshellarg($filepath)
            );

            // Execute backup
            $this->info('Executing mysqldump...');
            exec($command, $output, $returnVar);

            if ($returnVar !== 0) {
                $this->error('Backup failed! Please check database credentials and mysqldump availability.');
                return 1;
            }

            // Check if file was created
            if (!file_exists($filepath)) {
                $this->error('Backup file was not created!');
                return 1;
            }

            $filesize = filesize($filepath);
            $this->info("Backup created successfully: {$filename}");
            $this->info("File size: " . $this->formatBytes($filesize));

            // Compress if option is set
            if ($this->option('compress')) {
                $this->info('Compressing backup...');
                $compressedFile = $filepath . '.gz';

                $fp = gzopen($compressedFile, 'w9');
                gzwrite($fp, file_get_contents($filepath));
                gzclose($fp);

                // Remove uncompressed file
                unlink($filepath);

                $compressedSize = filesize($compressedFile);
                $this->info("Compressed successfully: {$filename}.gz");
                $this->info("Compressed size: " . $this->formatBytes($compressedSize));
                $this->info("Space saved: " . $this->formatBytes($filesize - $compressedSize));
            }

            $this->info('✓ Database backup completed successfully!');
            return 0;

        } catch (\Exception $e) {
            $this->error('Backup failed: ' . $e->getMessage());
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
