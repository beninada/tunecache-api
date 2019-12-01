<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Character extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
    ];

    public $timestamps = false;

    public static $names = [
        'Angry',
        'Busy & Frantic',
        'Changing Tempo',
        'Chasing',
        'Dark',
        'Dreamy',
        'Eccentric',
        'Elegant',
        'Epic',
        'Euphoric',
        'Fear',
        'Floating',
        'Funny',
        'Glamorous',
        'Happy',
        'Heavy & Ponderous',
        'Hopeful',
        'Laid Back',
        'Marching',
        'Mysterious',
        'Peaceful',
        'Quirky',
        'Relaxing',
        'Restless',
        'Romantic',
        'Running',
        'Sad',
        'Scary',
        'Sentimental',
        'Sexy',
        'Smooth',
        'Sneaking',
        'Suspense',
        'Weird  ',
    ];

    public function tracks()
    {
        return $this->belongsToMany('App\Models\Track', 'track_character');
    }
}
