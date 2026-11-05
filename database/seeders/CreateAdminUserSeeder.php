<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class CreateAdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create or update admin user
        User::updateOrCreate(
            ['email' => 'erdemeroglu@hotmail.com'],
            [
                'name' => 'Erdem Eroğlu',
                'password' => Hash::make('ErdEm@1987'),
                'email_verified_at' => now(),
            ]
        );
    }
}
