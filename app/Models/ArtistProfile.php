<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ArtistProfile extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'image',
        'city',
        'country',
        'bio',
        'genre',
        'daw',
        'fb_url',
        'tw_url',
        'ig_url',
        'sc_url',
    ];

    public function user()
    {
        return $this->morphOne('App\Models\User', 'profile');
    }
}
