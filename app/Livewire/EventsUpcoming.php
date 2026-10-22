<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Event;

class EventsUpcoming extends Component
{
    use WithPagination;

    public $search = '';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $query = Event::with(['categories', 'eventType'])
            ->where('is_active', true)
            ->where('date', '>=', now()->startOfDay())
            ->orderBy('date', 'asc');

        if (!empty($this->search)) {
            $query->where(function ($q) {
                $q->where('title', 'like', '%' . $this->search . '%')
                  ->orWhere('location_address', 'like', '%' . $this->search . '%');
            });
        }

        $events = $query->paginate(12);

        return view('livewire.events-upcoming', [
            'events' => $events,
        ])->layout('components.layouts.app', ['title' => 'Próximos Eventos - Itapúa Cross']);
    }
}
