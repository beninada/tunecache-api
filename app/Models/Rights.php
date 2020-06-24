<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rights extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title',
    ];

    public function track_rights()
    {
        return $this->belongsToMany('App\Models\TrackRights', 'track_id');
    }
}
