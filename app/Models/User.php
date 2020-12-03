<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
//        'email',
        'password',
        'username',
        'address',
        'location',
        'phone',
        'udid'
    ];

    public function getAddressAttribute()
    {
        return isset($this->attributes['address']) != null ? $this->attributes['address'] : '';
    }

    public function getCreatedAtAttribute()
    {
        return date('Y-m-d h:i:s A', strtotime($this->attributes['created_at']));
    }

    public function getUpdatedAtAttribute()
    {
        return date('Y-m-d h:i:s A', strtotime($this->attributes['updated_at']));
    }


    /** Items */
    public function items()
    {
        return $this->hasMany('App\Models\Item');
    }


    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
}
