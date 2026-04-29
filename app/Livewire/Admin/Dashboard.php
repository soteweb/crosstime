<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Event;
use App\Models\Registration;
use App\Models\Athlete;

class Dashboard extends Component
{
    public function render()
    {
        $metrics = [
            'events_total' => Event::count(),
            'events_active' => Event::where('is_active', true)->count(),
            'registrations' => Registration::count(),
            'athletes' => Athlete::count(),
            'revenue_pending' => Registration::where('payment_status', 'pending')
                    ->join('event_categories', 'registrations.event_category_id', '=', 'event_categories.id')
                    ->sum('event_categories.price'),
            'kits_total' => Registration::whereIn('status', ['inscribed', 'participant'])->count(),
            'kits_delivered' => Registration::where('status', 'participant')->count(),
        ];

        return view('livewire.admin.dashboard', compact('metrics'))
            ->layout('components.layouts.admin', ['title' => 'Dashboard Central']);
    }
}
