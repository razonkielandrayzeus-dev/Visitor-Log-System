<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Create Admin
        User::create([
            'name' => 'System Admin',
            'email' => 'admin@vlms.test',
            'password' => Hash::make('password123'),
            'role' => 'admin',
        ]);

        // Create Guard
        User::create([
            'name' => 'Security Guard',
            'email' => 'guard@vlms.test',
            'password' => Hash::make('password123'),
            'role' => 'guard',
        ]);

        $this->command->info('Users created successfully!');
        $this->command->info('Admin: admin@vlms.test / password123');
        $this->command->info('Guard: guard@vlms.test / password123');
    }
}
