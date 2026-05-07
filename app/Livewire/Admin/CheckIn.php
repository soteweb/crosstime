<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Event;
use App\Models\Registration;

class CheckIn extends Component
{
    public $eventSearchTerm = '';
    public $selected_event_id = '';
    
    public $athleteSearchTerm = '';
    
    public $selectedRegistrationId = null;
    public $assigned_bib_number = '';

    public function selectRegistration($id)
    {
        $this->selectedRegistrationId = $id;
        $registration = Registration::find($id);
        if ($registration) {
            $this->assigned_bib_number = $registration->bib_number ?? '';
        }
    }

    public function clearRegistration()
    {
        $this->selectedRegistrationId = null;
        $this->assigned_bib_number = '';
    }

    public function markAsPaid()
    {
        if (!$this->selectedRegistrationId) return;
        
        $registration = Registration::find($this->selectedRegistrationId);
        if ($registration) {
            $registration->payment_status = 'paid';
            $registration->save();
            session()->flash('message', 'Pago marcado como confirmado.');
        }
    }

    public function checkIn()
    {
        $this->validate([
            'assigned_bib_number' => 'required|string|max:20',
        ]);

        if (!$this->selectedRegistrationId) return;

        $registration = Registration::with('athlete', 'event')->find($this->selectedRegistrationId);
        
        if ($registration) {
            // Update bib and status
            $registration->bib_number = $this->assigned_bib_number;
            $registration->status = 'participant';
            if (!$registration->kit_delivered_at) {
                $registration->kit_delivered_at = now();
                $registration->kit_delivered_by = auth()->id();
            }
            $registration->save();

            // WhatsApp url format (similar to Athletes)
            $phone = $registration->athlete->phone;
            if ($phone && !str_starts_with($phone, '595')) {
                $phone = '595' . ltrim($phone, '0');
            }
            
            $message = "🏆 ¡Hola *{$registration->athlete->name}*! Ya retiraste tu kit para el evento *{$registration->event->title}*. Tu número de dorsal asignado es el *#{$registration->bib_number}*. ¡Muchos éxitos en la carrera! 🏃‍♂️🏁";
            $whatsappUrl = "https://wa.me/{$phone}?text=" . urlencode($message);

            session()->flash('message', '¡Kit entregado a ' . $registration->athlete->name . '! Dorsal #' . $registration->bib_number);
            
            $this->dispatch('open-whatsapp', url: $whatsappUrl);

            $this->clearRegistration();
            $this->athleteSearchTerm = '';
        }
    }

    public function render()
    {
        // 1. Events List
        $eventsQuery = Event::query()->orderBy('date', 'desc');
        if ($this->eventSearchTerm) {
            $eventsQuery->where('title', 'ilike', '%' . $this->eventSearchTerm . '%');
        }
        $events = $eventsQuery->take(15)->get();

        // 2. Athlete Search Results
        $searchResults = collect();
        if ($this->selected_event_id && strlen($this->athleteSearchTerm) >= 2 && !$this->selectedRegistrationId) {
            $searchResults = Registration::with(['athlete', 'category', 'distance'])
                ->where('event_id', $this->selected_event_id)
                ->whereHas('athlete', function($q) {
                    $q->where('name', 'ilike', '%'.$this->athleteSearchTerm.'%')
                      ->orWhere('last_name', 'ilike', '%'.$this->athleteSearchTerm.'%')
                      ->orWhere('identity_document', 'ilike', '%'.$this->athleteSearchTerm.'%');
                })
                ->take(10)
                ->get();
        }

        // 3. Selected Registration Details
        $selectedRegistration = null;
        if ($this->selectedRegistrationId) {
            $selectedRegistration = Registration::with(['athlete', 'category', 'distance'])->find($this->selectedRegistrationId);
        }

        // 4. Recent Deliveries (Last 5 kits delivered for this event)
        $recentDeliveries = collect();
        if ($this->selected_event_id) {
            $recentDeliveries = Registration::with(['athlete', 'category'])
                ->where('event_id', $this->selected_event_id)
                ->whereNotNull('kit_delivered_at')
                ->where('kit_delivered_by', auth()->id())
                ->orderBy('kit_delivered_at', 'desc')
                ->take(5)
                ->get();
        }

        return view('livewire.admin.check-in', [
            'events' => $events,
            'searchResults' => $searchResults,
            'selectedRegistration' => $selectedRegistration,
            'recentDeliveries' => $recentDeliveries,
        ])->layout('components.layouts.admin', ['title' => 'Control de Kits / Check-In']);
    }
}
