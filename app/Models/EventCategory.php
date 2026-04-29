<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EventCategory extends Model
{
    protected $fillable = [
        'event_id',
        'name',
        'price',
        'min_age',
        'max_age',
        'gender_restriction',
        'event_distance_id',
    ];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function distance()
    {
        return $this->belongsTo(EventDistance::class, 'event_distance_id');
    }
}
