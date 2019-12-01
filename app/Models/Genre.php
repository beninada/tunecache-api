<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Genre extends Model
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
        'Acoustic',
        'African',
        'Alternative Rock',
        'Ambient',
        'Asian Pop',
        'Bass',
        'Bass House',
        'Big Room',
        'Bollywood',
        'Breakbeat',
        'Chill Out',
        'Classical',
        'Complextro',
        'Country',
        'Dance',
        'Deep House',
        'Disco',
        'Downtempo',
        'Drum & Bass',
        'Dub',
        'Dub Techno',
        'Dubstep',
        'EDM',
        'Electro',
        'Electronic',
        'Experimental',
        'Folk',
        'Footwork',
        'Funk',
        'Future Bass',
        'Future House',
        'G House',
        'Glitch',
        'Glitch Hop',
        'Grime',
        'Hardcore',
        'Hardstyle',
        'Hip Hop',
        'House',
        'IDM',
        'Indie',
        'Jazz',
        'Latin',
        'Lofi Hip Hop',
        'Lofi House',
        'Melbourne',
        'Metal',
        'Minimal',
        'Moombahton',
        'Neo Soul',
        'Neuro',
        'Nu Disco',
        'Piano',
        'Pop',
        'Progressive House',
        'Psytrance',
        'R&B',
        'Rap',
        'Reggae',
        'Reggaeton',
        'Riddim',
        'Rock',
        'Soul',
        'Sound FX',
        'Soundtrack',
        'Synthpop',
        'Tech House',
        'Techno',
        'Trance',
        'Trap',
        'Tribal House',
        'Trip Hop',
        'Tropical House',
        'UK Garage',
        'World',
    ];

    public function tracks()
    {
        return $this->belongsToMany('App\Models\Track', 'track_genre');
    }
}
