<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Event;

class EventsUpcoming extends Component
{
    use WithPagination;

    public function render()
    {
        $events = Event::with(['categories', 'eventType'])
            ->where('is_active', true)
            ->where('date', '>=', now()->startOfDay())
            ->orderBy('date', 'asc')
            ->paginate(12);

        return view('livewire.events-upcoming', [
            'events' => $events,
        ])->layout('components.layouts.app', ['title' => 'Próximos Eventos - Itapúa Cross']);
    }
}
