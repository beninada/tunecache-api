<?php

namespace Database\Seeds;

use Illuminate\Database\Seeder;
use App\Models\Rights;

class RightsSeeder extends Seeder
{
    public function run()
    {
        // https://www.soundreef.com/en/blog/music-licenses/

        Rights::firstOrCreate(['title' => 'Master Recording License']);
        Rights::firstOrCreate(['title' => 'Performance License']);
        Rights::firstOrCreate(['title' => 'Synchronization License']);
        Rights::firstOrCreate(['title' => 'Mechanical License']);
        Rights::firstOrCreate(['title' => 'Print License']);
    }
}
