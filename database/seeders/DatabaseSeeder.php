<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $this->call([
            RolePermissionSeeder::class,
        ]);

        // Change this email/password, then log in and change it again from
        // the UI. This replaces Laravel's default factory-created test user,
        // which had no role and wasn't useful here.
        $owner = User::firstOrCreate(
            ['email' => 'echocrew@owner.com'],
            ['name' => 'Owner', 'password' => bcrypt('password'), 'is_active' => true]
        );
        $owner->assignRole('owner');
    }
}
