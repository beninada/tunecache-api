<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Playlist extends Model
{
        /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title', 'description', 'user_id'
    ];

    /**
     * The relationships that should always be loaded.
     *
     * @var array
     */

    // protected $with = ['users'];

    public static $createRules = [
        'title' => ['required', 'string', 'max:100'],
        'description' => ['required', 'string', 'max:5000'],
        'user_id' => ['required', 'exists:users,id'],
    ];


    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    public function tracks()
    {
        return $this->belongsToMany('App\Models\Track', 'playlist_tracks');
    }

}
