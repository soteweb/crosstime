<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Event;
use App\Models\Athlete;
use App\Models\Registration;

class EventRegistration extends Component
{
    public Event $event;
    
    public $step = 1; // 1: ID, 2: Personal Data, 3: Event Details, 4: Payment/Finish
    
    // Athlete Data
    public $name = '';
    public $last_name = '';
    public $identity_document = '';
    public $gender = '';
    public $blood_type = '';
    public $club = '';
    public $carnet_number = '';
    public $date_of_birth = '';
    public $city = '';
    public $country = 'Paraguay';
    public $email = '';
    public $phone = '';

    // Registration Data
    public $selected_distance_id = null;
    public $selected_category_id = null;
    public $tshirt_size = null;
    public $payment_method = '';
    public $observation = '';
    public $accepts_waiver = false;

    public $success_message = false;

    public function mount(Event $event)
    {
        $this->event = $event->load(['categories', 'distances']);
    }

    public function goToStep2()
    {
        $this->validate(['identity_document' => 'required|string']);
        
        $athlete = Athlete::where('identity_document', $this->identity_document)->first();
        if ($athlete) {
            $existing = Registration::where('event_id', $this->event->id)
                ->where('athlete_id', $athlete->id)
                ->first();
            
            if ($existing) {
                $statusLabels = [
                    'pre_inscribed' => 'Pre Inscripto',
                    'inscribed' => 'Inscripto',
                    'participant' => 'Participante'
                ];
                $label = $statusLabels[$existing->status] ?? 'Inscripto';
                $this->addError('identity_document', "Este atleta ya se encuentra {$label}.");
                return;
            }

            $this->name = $athlete->name;
            $this->last_name = $athlete->last_name;
            $this->gender = $athlete->gender;
            $this->blood_type = $athlete->blood_type;
            $this->club = $athlete->club;
            $this->carnet_number = $athlete->carnet_number;
            $this->date_of_birth = $athlete->date_of_birth ? $athlete->date_of_birth->format('Y-m-d') : '';
            $this->city = $athlete->city;
            $this->country = $athlete->country;
            $this->email = $athlete->email;
            $this->phone = $athlete->phone;
        }
        
        $this->step = 2;
    }

    public function goToStep3()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'gender' => 'required|in:M,F',
            'date_of_birth' => 'required|date|before:today',
            'email' => 'required|email',
            'phone' => 'required|string',
            'city' => 'required|string',
            'country' => 'required|string',
        ]);

        // If event has no distances, go straight to categories
        if ($this->event->distances->isEmpty()) {
            $this->selected_distance_id = null;
        }

        $this->step = 3;
    }

    public function getAvailableCategoriesProperty()
    {
        if (!$this->date_of_birth || !$this->gender) return collect();

        $birthDate = new \DateTime($this->date_of_birth);
        $today = new \DateTime();
        $age = $today->diff($birthDate)->y;

        return $this->event->categories
            ->filter(function ($cat) use ($age) {
                // Filter by distance if selected
                if ($this->selected_distance_id && $cat->event_distance_id && $cat->event_distance_id != $this->selected_distance_id) {
                    return false;
                }
                
                // Filter by distance if category REQUIRES a distance but none is selected
                if (!$this->selected_distance_id && $cat->event_distance_id) {
                    return false;
                }

                // Filter by Gender
                if ($cat->gender_restriction && $cat->gender_restriction !== $this->gender) {
                    return false;
                }

                // Filter by Age
                if ($cat->min_age && $age < $cat->min_age) return false;
                if ($cat->max_age && $age > $cat->max_age) return false;

                return true;
            });
    }

    public function goToStep4()
    {
        $rules = [
            'selected_category_id' => 'required|exists:event_categories,id',
            'accepts_waiver' => 'accepted'
        ];

        if ($this->event->distances->isNotEmpty()) {
            $rules['selected_distance_id'] = 'required|exists:event_distances,id';
        }

        if ($this->event->registration_settings['has_tshirts'] ?? false) {
            $rules['tshirt_size'] = 'required';
        }

        $this->validate($rules);
        
        $this->step = 4;
    }

    public function register()
    {
        $this->validate([
            'payment_method' => 'required'
        ]);

        // Find or create athlete
        $athlete = Athlete::updateOrCreate(
            ['identity_document' => $this->identity_document],
            [
                'name' => $this->name,
                'last_name' => $this->last_name,
                'gender' => $this->gender,
                'blood_type' => $this->blood_type,
                'club' => $this->club,
                'carnet_number' => $this->carnet_number,
                'date_of_birth' => $this->date_of_birth,
                'city' => $this->city,
                'country' => $this->country,
                'email' => $this->email,
                'phone' => $this->phone,
            ]
        );

        // Check unique registration
        $existing = Registration::where('event_id', $this->event->id)
            ->where('athlete_id', $athlete->id)
            ->first();

        if ($existing) {
            $this->addError('identity_document', 'Este atleta ya se encuentra inscripto en este evento.');
            $this->step = 1;
            return;
        }

        // Create Registration
        Registration::create([
            'event_id' => $this->event->id,
            'event_category_id' => $this->selected_category_id,
            'athlete_id' => $athlete->id,
            'event_distance_id' => $this->selected_distance_id,
            'payment_status' => 'pending',
            'status' => 'pre_inscribed',
            'payment_method' => $this->payment_method,
            'tshirt_size' => $this->tshirt_size,
            'observation' => $this->observation,
            'waiver_accepted_at' => now(),
        ]);

        $this->success_message = true;
        $this->step = 5; // Finish
    }

    public function render()
    {
        return view('livewire.event-registration')->layout('components.layouts.app', ['title' => 'Inscripción - ' . $this->event->title]);
    }
}
