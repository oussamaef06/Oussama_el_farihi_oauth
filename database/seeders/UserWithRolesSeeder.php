<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Database\Seeders\AdminUserSeeder;
use Database\Seeders\RolesTableSeeder;

class UserWithRolesSeeder extends Seeder
{
    public function run()
    {
        $this->call([
            RolesTableSeeder::class,
            AdminUserSeeder::class,
        ]);

        $roles = Role::all();

        for ($i = 0; $i < 10; $i++) {
            $user = User::create([
                'name' => "User {$i}",
                'email' => "user{$i}@example.com",
                'password' => Hash::make('password'),
            ]);

            $role = $roles->random();
            $user->roles()->attach($role);
        }
    }
}
