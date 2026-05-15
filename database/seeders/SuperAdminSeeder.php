<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class SuperAdminSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'superadmin@example.com'],
            [
                'name'     => 'Super Admin',
                'email'    => 'superadmin@example.com',
                'password' => Hash::make('Admin@12345'),
                'role'     => 'super_admin',
                'password_changed' => true, // Super admin has a proper password
                'default_subscription_fee' => 300.00,
                'bg_owner_pin' => '954895', // 6-digit PIN for BG Owner access
            ]
        );

        $this->command->info('
        
        Super Admin created: superadmin@example.com / Admin@12345');
    }
}