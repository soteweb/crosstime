<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Event;

class EventShow extends Component
{
    public $event;

    public function mount(Event $event)
    {
        $this->event = $event->load(['categories', 'eventType']);
    }

    public function render()
    {
        return view('livewire.event-show')
            ->layout('components.layouts.app', ['title' => $this->event->title . ' - Itapúa Cross']);
    }
}
