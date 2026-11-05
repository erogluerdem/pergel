<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;

class CreateDemoUser extends Command
{
    protected $signature = 'user:create-demo';
    protected $description = 'Tum projeyi ve modulleri gorebilen tam yetkili Demo Yonetici kullanicisini olusturur';

    public function handle()
    {
        $this->info('Demo kullanici olusturuluyor...');

        $attributes = [
            'name' => 'Demo Yönetici',
            'password' => Hash::make('Pergel2026!'),
            'email_verified_at' => now(),
        ];

        // Sadece varsa is_admin ve is_active ekle
        if (\Illuminate\Support\Facades\Schema::hasColumn('users', 'is_admin')) {
            $attributes['is_admin'] = 1;
        }
        if (\Illuminate\Support\Facades\Schema::hasColumn('users', 'is_active')) {
            $attributes['is_active'] = 1;
        }

        $user = User::updateOrCreate(
            ['email' => 'demo@pergel.com'],
            $attributes
        );

        if (class_exists(Role::class)) {
            $adminRole = Role::where('name', 'admin')->first();
            if ($adminRole) {
                $user->roles()->syncWithoutDetaching([$adminRole->id]);
            }
        }

        $this->info('----------------------------------------------------');
        $this->info('Demo Yonetici Kullanicisi Basariyla Olusturuldu!');
        $this->info('----------------------------------------------------');
        $this->info('E-posta : demo@pergel.com');
        $this->info('Sifre   : Pergel2026!');
        $this->info('Yetki   : Tam Yetkili Yonetici (Tum moduller & projeler acik)');
        $this->info('----------------------------------------------------');

        return Command::SUCCESS;
    }
}
