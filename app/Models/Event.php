<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $fillable = [
        'title',
        'description',
        'featured_image',
        'date',
        'location_address',
        'latitude',
        'longitude',
        'is_active',
        'event_type_id',
        'google_maps_url',
        'registration_type',
        'external_registration_url',
        'external_inscribed_url',
        'registration_settings',
        'registration_deadline',
        'payment_instructions',
        'start_time',
        'slug',
        'organizer',
        'results_url',
        'route_map_file',
        'instagram_urls'
    ];

    protected $casts = [
        'date' => 'date',
        'registration_settings' => 'array',
        'registration_deadline' => 'datetime',
        'instagram_urls' => 'array',
    ];

    public function categories()
    {
        return $this->hasMany(EventCategory::class);
    }

    public function distances()
    {
        return $this->hasMany(EventDistance::class);
    }

    public function eventType()
    {
        return $this->belongsTo(EventType::class);
    }

    public function clubs()
    {
        return $this->belongsToMany(Club::class);
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function resolveRouteBinding($value, $field = null)
    {
        return $this->where('slug', $value)
            ->orWhere('id', is_numeric($value) ? $value : 0)
            ->firstOrFail();
    }

    protected static function boot()
    {
        parent::boot();
        static::saving(function ($event) {
            if (empty($event->slug)) {
                $event->slug = \Illuminate\Support\Str::slug($event->title);
                
                // Ensure uniqueness
                $originalSlug = $event->slug;
                $count = 1;
                while (static::where('slug', $event->slug)->where('id', '!=', $event->id)->exists()) {
                    $event->slug = "{$originalSlug}-{$count}";
                    $count++;
                }
            }
        });
    }
}
