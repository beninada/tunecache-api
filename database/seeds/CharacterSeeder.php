<?php

namespace Database\Seeds;

use Illuminate\Database\Seeder;
use App\Models\Character;

class CharacterSeeder extends Seeder
{
    public function run()
    {
        foreach (Character::$names as $name) {
            Character::firstOrCreate(['name' => $name]);
        }
    }
}
