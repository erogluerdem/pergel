<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Role;

class DemoUserSeeder extends Seeder
{
    public function run(): void
    {
        // Standart Demo Yönetici (Tüm Projeyi Görebilir)
        $demoUser = User::updateOrCreate(
            ['email' => 'demo@pergel.com'],
            [
                'name' => 'Demo Yönetici',
                'password' => Hash::make('Pergel2026!'),
                'is_admin' => true,
                'is_active' => true,
                'title' => 'Proje & Şantiye Direktörü',
                'department' => 'Yönetim',
                'email_verified_at' => now(),
            ]
        );

        // İkincil alternatif demo kullanıcısı
        $testDemo = User::updateOrCreate(
            ['email' => 'demo@test.com'],
            [
                'name' => 'Demo Kullanıcı',
                'password' => Hash::make('demo1234'),
                'is_admin' => true,
                'is_active' => true,
                'title' => 'Proje Yöneticisi',
                'department' => 'Proje Yönetimi',
                'email_verified_at' => now(),
            ]
        );

        // Admin rolü varsa ekle
        if (class_exists(Role::class)) {
            $adminRole = Role::where('name', 'admin')->first();
            if ($adminRole) {
                $demoUser->roles()->syncWithoutDetaching([$adminRole->id]);
                $testDemo->roles()->syncWithoutDetaching([$adminRole->id]);
            }
        }
    }
}
