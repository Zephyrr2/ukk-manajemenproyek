<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Time_Log;
use App\Models\Card;
use App\Models\User;
use Carbon\Carbon;

class TimeLogSeeder extends Seeder
{
    /**
     * Run the database seeder.
     */
    public function run(): void
    {
        // Get some cards and users
        $cards = Card::take(5)->get();
        $users = User::whereIn('role', ['user', 'leader'])->take(3)->get();

        if ($cards->count() > 0 && $users->count() > 0) {
            // Create sample time logs for the last 30 days
            for ($i = 0; $i < 20; $i++) {
                $startDate = Carbon::now()->subDays(rand(1, 30));
                $duration = rand(30, 480); // 30 minutes to 8 hours
                $endDate = $startDate->copy()->addMinutes($duration);

                Time_Log::create([
                    'card_id' => $cards->random()->id,
                    'subtask_id' => null,  // Set subtask_id as null for now
                    'user_id' => $users->random()->id,
                    'start_time' => $startDate,
                    'end_time' => $endDate,
                    'duration_minutes' => $duration,
                    'description' => $this->getRandomDescription(),
                ]);
            }
        }
    }

    private function getRandomDescription(): string
    {
        $descriptions = [
            'Working on feature implementation',
            'Bug fixing and testing',
            'Code review and optimization',
            'Meeting and discussion',
            'Research and documentation',
            'Frontend development',
            'Backend API development',
            'Database optimization',
            'UI/UX improvements',
            'Testing and debugging',
        ];

        return $descriptions[array_rand($descriptions)];
    }
}
