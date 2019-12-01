<?php

use Illuminate\Database\Seeder;
use Database\Seeds\RoleSeeder;
use Database\Seeds\GenreSeeder;
use Database\Seeds\CharacterSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(RoleSeeder::class);
        $this->call(GenreSeeder::class);
        $this->call(CharacterSeeder::class);
    }
}
