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
                'default_subscription_fee' => 300.00,
            ]
        );

        $this->command->info('
        
        Super Admin created: superadmin@example.com / Admin@12345');
    }
}