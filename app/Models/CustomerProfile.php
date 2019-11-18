<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerProfile extends Model
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
    ];

    public function user()
    {
        return $this->morphOne('App\Models\User', 'profile');
    }
}
