<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Event;

class EventsPast extends Component
{
    use WithPagination;

    public function render()
    {
        $events = Event::with(['categories', 'eventType'])
            ->where('is_active', true)
            ->where('date', '<', now()->startOfDay())
            ->orderBy('date', 'desc')
            ->paginate(12);

        return view('livewire.events-past', [
            'events' => $events,
        ])->layout('components.layouts.app', ['title' => 'Resultados y Eventos Pasados - Itapúa Cross']);
    }
}
