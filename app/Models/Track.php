<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Laravel\Scout\Searchable;

class Track extends Model
{
    use Searchable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'title',
        'uuid',
        'uri',
        'description',
        'bpm',
        'key',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = ['url'];

    public static $musicalKeys = [
        'C',
        'Db',
        'D',
        'Eb',
        'E',
        'F',
        'Gb',
        'G',
        'Ab',
        'A',
        'Bb',
        'B',
    ];

    public static $musicalScales = [
        'major',
        'minor',
    ];

    public function genres()
    {
        return $this->belongsToMany('App\Models\Genre', 'track_genre');
    }

    public function characters()
    {
        return $this->belongsToMany('App\Models\Character', 'track_character');
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    public function getUrlAttribute()
    {
        return Storage::cloud()->url('track/'.$this->uuid);
    }
}
