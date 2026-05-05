<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Event;
use App\Models\EventType;
use Livewire\Attributes\Layout;
use Illuminate\Support\Str;

#[Layout('components.layouts.admin')]
class EventEditor extends Component
{
    use WithFileUploads;

    public $eventId = null;
    
    // Form Fields
    public $title = '';
    public $description = '';
    public $featured_image;
    public $existing_featured_image;
    public $date = '';
    public $location_address = '';
    public $event_type_id = '';
    public $google_maps_url = '';
    public $registration_type = 'local';
    public $external_registration_url = '';
    public $registration_deadline = '';
    public $payment_instructions = '';
    public $start_time = '';
    public $organizer = '';
    public $results_url = '';
    public $is_active = true;

    // Registration Settings
    public $has_tshirts = false;
    public $tshirt_sizes = [];
    public $payment_methods = [];
    
    // Dynamic lists
    public $distances = []; // ['id' => null, 'name' => '']
    public $categories = []; // ['id' => null, 'name' => '', 'price' => 0, 'min_age' => null, 'max_age' => null, 'gender_restriction' => null, 'event_distance_id' => null]

    public function mount($event = null)
    {
        if ($event) {
            $e = Event::with(['distances', 'categories'])->findOrFail($event);
            $this->eventId = $e->id;
            $this->title = $e->title;
            $this->description = $e->description;
            $this->existing_featured_image = $e->featured_image;
            $this->date = $e->date ? $e->date->format('Y-m-d') : '';
            $this->location_address = $e->location_address;
            $this->event_type_id = $e->event_type_id;
            $this->google_maps_url = $e->google_maps_url;
            $this->registration_type = $e->registration_type ?? 'local';
            $this->external_registration_url = $e->external_registration_url;
            $this->registration_deadline = $e->registration_deadline ? $e->registration_deadline->format('Y-m-d\TH:i') : '';
            $this->payment_instructions = $e->payment_instructions;
            $this->start_time = $e->start_time;
            $this->organizer = $e->organizer;
            $this->results_url = $e->results_url;
            $this->is_active = $e->is_active;

            // Settings
            $settings = $e->registration_settings ?? [];
            $this->has_tshirts = $settings['has_tshirts'] ?? false;
            $this->tshirt_sizes = $settings['tshirt_sizes'] ?? [];
            $this->payment_methods = $settings['payment_methods'] ?? [];

            // Distances
            $this->distances = $e->distances->map(fn($d) => [
                'id' => $d->id,
                'name' => $d->name
            ])->toArray();

            // Categories
            $this->categories = $e->categories->map(fn($c) => [
                'id' => $c->id,
                'name' => $c->name,
                'price' => $c->price,
                'min_age' => $c->min_age,
                'max_age' => $c->max_age,
                'gender_restriction' => $c->gender_restriction,
                'event_distance_id' => $c->event_distance_id
            ])->toArray();
        }

        if (empty($this->tshirt_sizes)) {
            $this->tshirt_sizes = ['S', 'M', 'L', 'XL'];
        }
    }

    public function addDistance()
    {
        $this->distances[] = ['id' => null, 'name' => ''];
    }

    public function removeDistance($index)
    {
        unset($this->distances[$index]);
        $this->distances = array_values($this->distances);
    }

    public function addCategory()
    {
        $this->categories[] = [
            'id' => null,
            'name' => '',
            'price' => 0,
            'min_age' => null,
            'max_age' => null,
            'gender_restriction' => null,
            'event_distance_id' => null
        ];
    }

    public function removeCategory($index)
    {
        unset($this->categories[$index]);
        $this->categories = array_values($this->categories);
    }

    public function save()
    {
        $rules = [
            'title' => 'required|string|max:255',
            'date' => 'required|date',
            'event_type_id' => 'nullable|exists:event_types,id',
            'featured_image' => 'nullable|image|max:2048',
            'google_maps_url' => 'nullable|url',
            'registration_type' => 'required|in:local,external',
            'external_registration_url' => 'required_if:registration_type,external|nullable|url',
            'registration_deadline' => 'nullable|date',
            'distances.*.name' => 'required_if:registration_type,local|string|max:255',
            'categories.*.name' => 'required_if:registration_type,local|string|max:255',
            'categories.*.price' => 'required_if:registration_type,local|numeric|min:0',
        ];

        $this->validate($rules);

        $imagePath = $this->existing_featured_image;
        if ($this->featured_image) {
            $imagePath = $this->featured_image->store('events', 'public');
        }

        $data = [
            'title' => $this->title,
            'description' => $this->description,
            'featured_image' => $imagePath,
            'date' => $this->date,
            'location_address' => $this->location_address,
            'latitude' => -27.3305, 
            'longitude' => -55.8667, 
            'is_active' => $this->is_active,
            'event_type_id' => $this->event_type_id ?: null,
            'google_maps_url' => $this->google_maps_url,
            'registration_type' => $this->registration_type,
            'external_registration_url' => $this->external_registration_url,
            'registration_deadline' => $this->registration_deadline ?: null,
            'payment_instructions' => $this->payment_instructions,
            'start_time' => $this->start_time,
            'organizer' => $this->organizer,
            'results_url' => $this->results_url,
            'registration_settings' => [
                'has_tshirts' => $this->has_tshirts,
                'tshirt_sizes' => $this->tshirt_sizes,
                'payment_methods' => $this->payment_methods,
            ]
        ];

        if ($this->eventId) {
            $event = Event::findOrFail($this->eventId);
            $event->update($data);
        } else {
            $event = Event::create($data);
        }

        if ($this->registration_type === 'local') {
            // Save Distances
            $keepDistances = [];
            foreach ($this->distances as $dist) {
                $d = $event->distances()->updateOrCreate(
                    ['id' => $dist['id']],
                    ['name' => $dist['name']]
                );
                $keepDistances[] = $d->id;
            }
            $event->distances()->whereNotIn('id', $keepDistances)->delete();

            // Map old distance IDs to new ones if necessary (if we had categories linked to them)
            // But since we use real IDs in the array, it should be fine.

            // Save Categories
            $keepCategories = [];
            foreach ($this->categories as $cat) {
                $c = $event->categories()->updateOrCreate(
                    ['id' => $cat['id']],
                    [
                        'name' => $cat['name'],
                        'price' => $cat['price'],
                        'min_age' => $cat['min_age'],
                        'max_age' => $cat['max_age'],
                        'gender_restriction' => $cat['gender_restriction'] ?: null,
                        'event_distance_id' => $cat['event_distance_id'] ?: null,
                    ]
                );
                $keepCategories[] = $c->id;
            }
            $event->categories()->whereNotIn('id', $keepCategories)->delete();
        }

        session()->flash('message', $this->eventId ? 'Evento actualizado exitosamente.' : 'Evento creado exitosamente.');
        return redirect()->route('admin.events');
    }

    public function render()
    {
        return view('livewire.admin.event-editor', [
            'eventTypes' => EventType::all(),
            'eventDistances' => $this->eventId ? \App\Models\EventDistance::where('event_id', $this->eventId)->get() : collect()
        ]);
    }
}
