<?php

namespace Database\Seeds;

use Illuminate\Database\Seeder;
use App\Models\Genre;

class GenreSeeder extends Seeder
{
    public function run()
    {
        foreach (Genre::$names as $name) {
            Genre::firstOrCreate(['name' => $name]);
        }
    }
}
