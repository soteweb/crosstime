<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Event;

class EventList extends Component
{
    public function render()
    {
        // Upcoming Events (strict limit 6 for home)
        $upcomingEvents = Event::with(['categories', 'eventType'])
            ->where('is_active', true)
            ->where('date', '>=', now()->startOfDay())
            ->orderBy('date', 'asc')
            ->limit(6)
            ->get();
            
        $totalUpcoming = Event::where('is_active', true)->where('date', '>=', now()->startOfDay())->count();

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
