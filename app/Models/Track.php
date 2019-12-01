<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Track extends Model
{
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
}
