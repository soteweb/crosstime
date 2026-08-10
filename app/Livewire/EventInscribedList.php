<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Event;
use App\Models\Registration;

class EventInscribedList extends Component
{
    public Event $event;
    public $categoryId = '';

    public function mount(Event $event)
    {
        // Security check: only visible until the day after the event
        if (now()->startOfDay()->gt($event->date->addDay())) {
            abort(404);
        }

        $this->event = $event;
    }

    public function render()
    {
        $query = Registration::with(['athlete', 'category'])
            ->where('event_id', $this->event->id);

        if ($this->categoryId) {
            $query->where('event_category_id', $this->categoryId);
        }

        $registrations = $query->orderBy('created_at', 'asc')->get();

        return view('livewire.event-inscribed-list', [
            'registrations' => $registrations,
            'categories' => $this->event->categories
        ])->layout('components.layouts.app', ['title' => 'Listado de Inscriptos - ' . $this->event->title]);
    }
}
