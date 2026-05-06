<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Event;
use App\Models\Payment;
use App\Models\Registration;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class FinanceReport extends Component
{
    public $selected_event_id = '';
    public $eventSearchTerm = '';
    
    public function mount()
    {
        if (!in_array(auth()->user()->role, [User::ROLE_SUPER_ADMIN, User::ROLE_ORGANIZER])) {
            abort(403, 'No tienes permiso para ver reportes financieros.');
        }
    }

    public function render()
    {
        $eventsQuery = Event::orderBy('date', 'desc');
        if ($this->eventSearchTerm) {
            $eventsQuery->where('title', 'ilike', '%' . $this->eventSearchTerm . '%');
        }
        $events = $eventsQuery->take(15)->get();
        
        $stats = [
            'total_collected' => 0,
            'pending_amount' => 0,
            'by_method' => [],
            'by_category' => [],
            'registrations_count' => 0,
            'paid_count' => 0,
        ];

        if ($this->selected_event_id) {
            $event = Event::with(['categories', 'distances'])->find($this->selected_event_id);
            
            if ($event) {
                // Total Collected
                $stats['total_collected'] = Payment::whereHas('registration', function($q) {
                    $q->where('event_id', $this->selected_event_id);
                })->sum('amount');

                // Breakdown by method
                $stats['by_method'] = Payment::whereHas('registration', function($q) {
                    $q->where('event_id', $this->selected_event_id);
                })
                ->select('payment_method', DB::raw('SUM(amount) as total'))
                ->groupBy('payment_method')
                ->get();

                // Breakdown by category
                $stats['by_category'] = Registration::where('registrations.event_id', $this->selected_event_id)
                    ->join('event_categories', 'registrations.event_category_id', '=', 'event_categories.id')
                    ->select('event_categories.name', DB::raw('COUNT(*) as count'), DB::raw("SUM(CASE WHEN registrations.payment_status = 'paid' THEN 1 ELSE 0 END) as paid_count"))
                    ->groupBy('event_categories.name')
                    ->get();

                // Counts
                $stats['registrations_count'] = Registration::where('event_id', $this->selected_event_id)->count();
                $stats['paid_count'] = Registration::where('event_id', $this->selected_event_id)->where('payment_status', 'paid')->count();
                
                // Estimation of pending
                $stats['pending_amount'] = Registration::where('registrations.event_id', $this->selected_event_id)
                    ->where('registrations.payment_status', '!=', 'paid')
                    ->join('event_categories', 'registrations.event_category_id', '=', 'event_categories.id')
                    ->sum('event_categories.price');
            }
        }

        return view('livewire.admin.finance-report', [
            'events' => $events,
            'stats' => $stats
        ])->layout('components.layouts.admin', ['title' => 'Reporte Financiero']);
    }
}
