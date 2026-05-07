<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Registration extends Model
{
    protected $fillable = [
        'event_id',
        'event_category_id',
        'athlete_id',
        'event_distance_id',
        'payment_status',
        'waiver_accepted_at',
        'tshirt_size',
        'payment_method',
        'observation',
        'status',
        'kit_delivered_by',
    ];

    public function deliveredBy()
    {
        return $this->belongsTo(User::class, 'kit_delivered_by');
    }

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function category()
    {
        return $this->belongsTo(EventCategory::class, 'event_category_id');
    }

    public function athlete()
    {
        return $this->belongsTo(Athlete::class);
    }

    public function distance()
    {
        return $this->belongsTo(EventDistance::class, 'event_distance_id');
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }
}
