<?php

namespace Database\Seeds;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder {
    public function run()
    {
        Role::create(['name' => 'admin']);
        Role::create(['name' => 'artist']);
        Role::create(['name' => 'customer']);
    }
}
