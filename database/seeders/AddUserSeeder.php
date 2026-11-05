<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AddUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Creates or updates the user with the provided email
        User::updateOrCreate(
            ['email' => 'erdemeroglu@hotmail.com'],
            [
                'name' => 'Erdem Eroglu',
                'email' => 'erdemeroglu@hotmail.com',
                'password' => Hash::make('ErdEm@1987'),
                'email_verified_at' => now(),
            ]
        );
    }
}
