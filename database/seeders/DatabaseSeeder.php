<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();

        $roles = ['Admin', 'Teacher', 'Student', 'Support', 'Secretary'];

        foreach ($roles as $role) {

            Role::create([
                'name' => $role
            ]);
        }

        User::create([
            'name' => 'Admin',
            'email' => 'admin@gmail.com',
            'phone' => '010000010',
            'password' => Hash::make('12345678'),
            'role_id' => 1,
        ]);
    }
}
