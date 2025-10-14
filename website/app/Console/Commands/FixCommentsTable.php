<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class FixCommentsTable extends Command
{
    protected $signature = 'fix:comments-table';
    protected $description = 'Fix comments table subtask_id nullable constraint';

    public function handle()
    {
        try {
            // Drop foreign key constraint if exists
            $this->info('Dropping foreign key constraints...');
            DB::statement('ALTER TABLE comments DROP FOREIGN KEY comments_subtask_id_foreign');
        } catch (\Exception $e) {
            $this->info('Foreign key constraint may not exist: ' . $e->getMessage());
        }

        try {
            // Modify column to be nullable
            $this->info('Modifying subtask_id column to be nullable...');
            DB::statement('ALTER TABLE comments MODIFY COLUMN subtask_id BIGINT UNSIGNED NULL');
        } catch (\Exception $e) {
            $this->error('Failed to modify column: ' . $e->getMessage());
            return 1;
        }

        try {
            // Re-add foreign key constraint
            $this->info('Re-adding foreign key constraint...');
            DB::statement('ALTER TABLE comments ADD CONSTRAINT comments_subtask_id_foreign FOREIGN KEY (subtask_id) REFERENCES subtasks(id) ON DELETE CASCADE');
        } catch (\Exception $e) {
            $this->info('Failed to re-add foreign key constraint: ' . $e->getMessage());
        }

        $this->info('Comments table fixed successfully!');
        return 0;
    }
}
