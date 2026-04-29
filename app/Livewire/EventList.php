<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Event;

class EventList extends Component
{
    public $showAll = false;

    public function toggleShowAll()
    {
        $this->showAll = true;
    }

    public function render()
    {
        // Upcoming Events
        $upcomingQuery = Event::with(['categories', 'eventType'])
            ->where('is_active', true)
            ->where('date', '>=', now()->startOfDay())
            ->orderBy('date', 'asc');

        $totalUpcoming = (clone $upcomingQuery)->count();

        if (!$this->showAll) {
            $upcomingQuery->limit(6);
        }

        $upcomingEvents = $upcomingQuery->get();

        // Past Events (Results)
        $pastEvents = Event::with(['categories', 'eventType'])
            ->where('is_active', true)
            ->where('date', '<', now()->startOfDay())
            ->orderBy('date', 'desc')
            ->limit(6)
            ->get();

        return view('livewire.event-list', [
            'events' => $upcomingEvents,
            'pastEvents' => $pastEvents,
            'totalEvents' => $totalUpcoming
        ])->layout('components.layouts.app', ['title' => 'Itapúa Cross - Eventos Deportivos']);
    }
}
