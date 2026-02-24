<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::factory()->create([
            'name' => 'Fasterino Rafael',
            'role' => 'admin',
            'stand_id' => 'STD-20260224-0001',
            'password' => bcrypt('12345678')
        ]);
    }
}
