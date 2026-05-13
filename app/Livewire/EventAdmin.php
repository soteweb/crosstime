<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Event;
use Livewire\Attributes\Layout;

#[Layout('components.layouts.admin')]
class EventAdmin extends Component
{
    // Search & UI Helpers
    public $search = '';
    public $isDeleteModalOpen = false;
    
    // Model Data
    public $deletingEventId = null;

    public function confirmDelete($id)
    {
        $this->deletingEventId = $id;
        $this->isDeleteModalOpen = true;
    }

    public function deleteEvent()
    {
        if ($this->deletingEventId) {
            Event::findOrFail($this->deletingEventId)->delete();
            $this->isDeleteModalOpen = false;
            $this->deletingEventId = null;
            session()->flash('message', 'Evento eliminado correctamente.');
        }
    }

    public function render()
    {
        $events = Event::with(['categories', 'eventType'])
            ->where('title', 'ilike', '%' . $this->search . '%')
            ->orderBy('date', 'desc')
            ->get();

        return view('livewire.event-admin', [
            'events' => $events
        ])->layout('components.layouts.admin', ['title' => 'Gestión de Eventos']);
    }
}
