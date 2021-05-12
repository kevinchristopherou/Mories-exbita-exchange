<?php

use Caffeinated\Shinobi\Models\Role;
use Illuminate\Database\Seeder;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \Illuminate\Support\Facades\DB::statement('SET FOREIGN_KEY_CHECKS = 0');
        \Illuminate\Support\Facades\DB::statement('TRUNCATE roles');
        \Illuminate\Support\Facades\DB::statement('SET FOREIGN_KEY_CHECKS = 1');

        Role::create([
            'id' => 1,
            'name' => 'Admin',
            'slug' => 'admin',
        ]);

        Role::create([
            'id' => 2,
            'name' => 'User Manager',
            'slug' => 'user_manager',
        ]);

        Role::create([
            'id' => 3,
            'name' => 'Finance Manager',
            'slug' => 'finance_manager',
        ]);
    }
}
