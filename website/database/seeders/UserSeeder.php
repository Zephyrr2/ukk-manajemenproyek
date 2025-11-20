<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Admin Users
        User::create([
            'name' => 'Admin',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('11111111'),
            'role' => 'admin',
            'status' => 'free',
        ]);

        User::create([
            'name' => 'Siti Nurhaliza',
            'email' => 'siti@gmail.com',
            'password' => Hash::make('11111111'),
            'role' => 'admin',
            'status' => 'free',
        ]);

        // Team Leaders
        User::create([
            'name' => 'Leader',
            'email' => 'leader@gmail.com',
            'password' => Hash::make('11111111'),
            'role' => 'leader',
            'status' => 'free',
        ]);

        User::create([
            'name' => 'Rina Kusuma',
            'email' => 'rina@gmail.com',
            'password' => Hash::make('11111111'),
            'role' => 'leader',
            'status' => 'free',
        ]);

        User::create([
            'name' => 'Dedi Supriadi',
            'email' => 'dedi@gmail.com',
            'password' => Hash::make('11111111'),
            'role' => 'leader',
            'status' => 'free',
        ]);

        // Regular Users (Developers/Designers)
        User::create([
            'name' => 'Ahmad Fauzi',
            'email' => 'ahmad@gmail.com',
            'password' => Hash::make('11111111'),
            'role' => 'user',
            'status' => 'free',
        ]);

        User::create([
            'name' => 'Developer',
            'email' => 'developer@gmail.com',
            'password' => Hash::make('11111111'),
            'role' => 'user',
            'status' => 'free',
        ]);

        User::create([
            'name' => 'Designer',
            'email' => 'designer@gmail.com',
            'password' => Hash::make('11111111'),
            'role' => 'user',
            'status' => 'free',
        ]);

        User::create([
            'name' => 'Maya Sari',
            'email' => 'maya@gmail.com',
            'password' => Hash::make('11111111'),
            'role' => 'user',
            'status' => 'free',
        ]);

        User::create([
            'name' => 'Hendra Gunawan',
            'email' => 'hendra@gmail.com',
            'password' => Hash::make('11111111'),
            'role' => 'user',
            'status' => 'free',
        ]);

        User::create([
            'name' => 'Fitri Handayani',
            'email' => 'fitri@gmail.com',
            'password' => Hash::make('11111111'),
            'role' => 'user',
            'status' => 'free',
        ]);

        User::create([
            'name' => 'Agus Setiawan',
            'email' => 'agus@gmail.com',
            'password' => Hash::make('11111111'),
            'role' => 'user',
            'status' => 'free',
        ]);

        User::create([
            'name' => 'Linda Wijayanti',
            'email' => 'linda@gmail.com',
            'password' => Hash::make('11111111'),
            'role' => 'user',
            'status' => 'free',
        ]);

        User::create([
            'name' => 'Eko Prasetyo',
            'email' => 'eko@gmail.com',
            'password' => Hash::make('11111111'),
            'role' => 'user',
            'status' => 'free',
        ]);

        User::create([
            'name' => 'Novi Rahmawati',
            'email' => 'novi@gmail.com',
            'password' => Hash::make('11111111'),
            'role' => 'user',
            'status' => 'free',
        ]);

        $this->command->info('User seeder completed! Created 15 users with email matching their names.');
        $this->command->info('- 2 Admins');
        $this->command->info('- 3 Leaders');
        $this->command->info('- 10 Users (Developers, Designers, QA Testers)');
        $this->command->info('Default password for all users: 11111111');
    }
}
