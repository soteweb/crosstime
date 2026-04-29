<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EventDistance extends Model
{
    protected $fillable = [
        'event_id',
        'name',
    ];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function categories()
    {
        return $this->hasMany(EventCategory::class);
    }
}
