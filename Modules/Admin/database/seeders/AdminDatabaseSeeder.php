<?php

namespace Modules\Admin\Database\Seeders;

use App\Enums\UserStatus;
use Illuminate\Database\Seeder;

class AdminDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $adminRole = \App\Models\Role::where('name', 'admin')->first();
        $hostRole = \App\Models\Role::where('name', 'host')->first();
        $userRole = \App\Models\Role::where('name', 'user')->first();

        if (!$adminRole || !$hostRole || !$userRole) {
            $this->command->error('Roles not found. Please run RoleSeeder first.');
            return;
        }

        // Admin User
        \App\Models\User::firstOrCreate(
            ['email' => 'admin@admin.com'],
            [
                'name' => 'System Admin',
                'password' => \Illuminate\Support\Facades\Hash::make('password'),
                'role_id' => $adminRole->id,
                'status' => UserStatus::Active->value,
            ]
        );

        // Host User
        \App\Models\User::firstOrCreate(
            ['email' => 'host@example.com'],
            [
                'name' => 'Sample Host',
                'password' => \Illuminate\Support\Facades\Hash::make('password'),
                'role_id' => $hostRole->id,
                'status' => UserStatus::Active->value,
            ]
        );

        // Regular User
        \App\Models\User::firstOrCreate(
            ['email' => 'user@example.com'],
            [
                'name' => 'Regular User',
                'password' => \Illuminate\Support\Facades\Hash::make('password'),
                'role_id' => $userRole->id,
                'status' => UserStatus::Active->value,
            ]
        );

        // Seed some more for pagination and status testing
        for ($i = 1; $i <= 10; $i++) {
            $status = UserStatus::Active;
            if ($i == 1) $status = UserStatus::Pending;
            if ($i == 2) $status = UserStatus::Blocked;
            if ($i % 4 == 0) $status = UserStatus::Inactive;

            \App\Models\User::create([
                'name' => "User $i",
                'email' => "user$i@example.com",
                'password' => \Illuminate\Support\Facades\Hash::make('password'),
                'role_id' => $i % 3 == 0 ? $hostRole->id : $userRole->id,
                'status' => $status->value,
            ]);
        }
    }
}
