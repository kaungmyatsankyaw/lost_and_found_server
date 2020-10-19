<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    protected $guarded = [
        'id'
    ];

    /** User */
    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

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

    public function getTimeAttribute()
    {
        return date('Y-m-d h:i:s A', strtotime($this->attributes['time']));
    }

    public function getTypeAttribute()
    {
        $_int_type = $this->attributes['type'];
        switch ($_int_type) {
            case 1:
                return 'Lost';
            case 2:
                return 'Found';
        }
    }
}
