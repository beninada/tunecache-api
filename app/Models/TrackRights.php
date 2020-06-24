<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TrackRights extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'track_id',
        'right_id'
    ];

    public static $createRules = [
        'user_id' => ['required', 'exists:users,id'],
        'track_id' => ['required', 'exists:tracks,id'],
        'right_id' => ['required', 'exists:rights,id'],
    ];

    public function users()
    {
        return $this->belongsToMany('App\Models\User', 'user_id');
    }

    public function tracks()
    {
        return $this->belongsToMany('App\Models\Track', 'track_id');
    }

    public function rights()
    {
        return $this->belongsToMany('App\Models\Rights', 'id');
    }
}
