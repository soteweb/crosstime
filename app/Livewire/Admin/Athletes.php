<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use App\Models\Registration;
use App\Models\Athlete;

class Athletes extends Component
{
    use WithFileUploads, WithPagination;

    public $search = '';
    public $filter_event = '';
    public $tab = 'registrations'; // 'registrations' or 'athletes'
    
    public $view = 'list'; // 'list', 'registration', 'edit_athlete', 'import', 'bib_assignment'
    public $importFile;
    public $editingAthleteId;
    public $selectedRegistrationId;
    public $bib_number = '';
    public $bibIsEdit = false;
    public $whatsappUrl = '';
    
    public $identity_document = '';
    public $name = '';
    public $last_name = '';
    public $gender = '';
    public $date_of_birth = '';
    public $phone = '';
    public $email = '';
    public $blood_type = '';
    public $club = '';
    public $carnet_number = '';
    public $city = '';
    public $country = 'Paraguay';
    
    public $selected_event_id = '';
    public $selected_distance_id = '';
    public $selected_category_id = '';
    public $eventSearchTerm = '';
    public $registrationEventSearchTerm = '';
    public $payment_method = 'Efectivo';
    public $payment_status = 'paid';
    public $tshirt_size = '';

    // Payment Form
    public $payment_amount = 0;
    public $payment_reference = '';
    public $payment_notes = '';
    public $payment_date = '';

    public function changeStatus($registrationId, $newStatus)
    {
        $reg = Registration::find($registrationId);
        if ($reg) {
            if ($newStatus === 'participant') {
                $this->selectedRegistrationId = $registrationId;
                $this->bib_number = $reg->bib_number ?? '';
                $this->bibIsEdit = false;
                $this->view = 'bib_assignment';
                return;
            }

            if ($newStatus === 'inscribed' && $reg->payment_status !== 'paid') {
                $this->openPayment($registrationId);
                return;
            }

            $reg->status = $newStatus;
            
            // Sync payment_status for backward compatibility or display
            if ($newStatus === 'pre_inscribed') {
                $reg->payment_status = 'pending';
            } else {
                $reg->payment_status = 'paid';
            }
            
            $reg->save();
        }
    }

    public function openPayment($id)
    {
        $reg = Registration::with('category')->findOrFail($id);
        $this->selectedRegistrationId = $reg->id;
        $this->payment_amount = $reg->category->price;
        $this->payment_method = $reg->payment_method ?: 'Efectivo';
        $this->payment_reference = '';
        $this->payment_notes = '';
        $this->payment_date = now()->format('Y-m-d');
        $this->view = 'payment_validation';
    }

    public function savePayment()
    {
        $this->validate([
            'payment_amount' => 'required|numeric|min:0',
            'payment_method' => 'required',
            'payment_date' => 'required|date',
        ]);

        $reg = Registration::findOrFail($this->selectedRegistrationId);
        
        \App\Models\Payment::create([
            'registration_id' => $reg->id,
            'amount' => $this->payment_amount,
            'payment_method' => $this->payment_method,
            'reference' => $this->payment_reference,
            'notes' => $this->payment_notes,
            'payment_date' => $this->payment_date,
        ]);

        $reg->update([
            'payment_status' => 'paid',
            'status' => 'inscribed'
        ]);

        $this->view = 'list';
        $this->reset(['selectedRegistrationId', 'payment_amount', 'payment_reference', 'payment_notes']);
        session()->flash('message', 'Pago registrado y atleta inscripto correctamente.');
    }

    public function changeBib($registrationId)
    {
        $reg = Registration::find($registrationId);
        if ($reg) {
            $this->selectedRegistrationId = $registrationId;
            $this->bib_number = $reg->bib_number ?? '';
            $this->bibIsEdit = true;
            $this->view = 'bib_assignment';
        }
    }

    public function saveBibNumber()
    {
        $reg = Registration::find($this->selectedRegistrationId);
        if ($reg) {
            $reg->status = 'participant';
            $reg->bib_number = $this->bib_number;
            $reg->payment_status = 'paid';
            if (!$reg->kit_delivered_at) {
                $reg->kit_delivered_at = now();
            }
            $reg->save();
        }
        $this->view = 'list';

        $wasEdit = $this->bibIsEdit;

        if (!$wasEdit) {
            // Generate WhatsApp Link only on first assignment
            $phone = preg_replace('/[^0-9]/', '', $reg->athlete->phone);
            if (strlen($phone) === 9 && $phone[0] === '0') {
                $phone = '595' . substr($phone, 1);
            } elseif (strlen($phone) === 9) {
                $phone = '595' . $phone;
            }
            $message = "¡Hola *{$reg->athlete->name}*! 👋 Ya retiraste tu kit para el evento *{$reg->event->title}*. Tu número de dorsal asignado es el *#{$reg->bib_number}*. ¡Muchos éxitos en la carrera! 🏃‍♂️🏁";
            $this->whatsappUrl = "https://wa.me/{$phone}?text=" . urlencode($message);
            $this->dispatch('open-whatsapp', url: $this->whatsappUrl);
        }

        $this->reset(['selectedRegistrationId', 'bib_number', 'bibIsEdit']);
        session()->flash('message', $wasEdit
            ? 'Dorsal actualizado a #' . $reg->bib_number
            : 'Atleta acreditado con Dorsal #' . $reg->bib_number
        );
    }

    public function openRegistration()
    {
        $this->reset(['identity_document', 'name', 'last_name', 'gender', 'date_of_birth', 'phone', 'email', 'selected_event_id', 'selected_distance_id', 'selected_category_id', 'tshirt_size']);
        $this->view = 'registration';
    }

    public function cancel()
    {
        $this->view = 'list';
    }

    public function updatedIdentityDocument()
    {
        $athlete = \App\Models\Athlete::where('identity_document', $this->identity_document)->first();
        if ($athlete) {
            $this->name = $athlete->name;
            $this->last_name = $athlete->last_name;
            $this->gender = $athlete->gender;
            $this->date_of_birth = $athlete->date_of_birth ? $athlete->date_of_birth->format('Y-m-d') : '';
            $this->phone = $athlete->phone;
            $this->email = $athlete->email;
        }
    }

    public function saveRegistration()
    {
        $this->validate([
            'identity_document' => 'required',
            'name' => 'required',
            'last_name' => 'required',
            'selected_event_id' => 'required',
            'selected_category_id' => 'required',
        ]);

        $athlete = \App\Models\Athlete::updateOrCreate(
            ['identity_document' => $this->identity_document],
            [
                'name' => $this->name,
                'last_name' => $this->last_name,
                'gender' => $this->gender,
                'date_of_birth' => $this->date_of_birth,
                'phone' => $this->phone,
                'email' => $this->email,
            ]
        );

        Registration::create([
            'athlete_id' => $athlete->id,
            'event_id' => $this->selected_event_id,
            'event_distance_id' => $this->selected_distance_id ?: null,
            'event_category_id' => $this->selected_category_id,
            'payment_method' => $this->payment_method,
            'payment_status' => $this->payment_status,
            'status' => $this->payment_status === 'paid' ? 'inscribed' : 'pre_inscribed',
            'tshirt_size' => $this->tshirt_size,
            'waiver_accepted_at' => now(),
        ]);

        $this->view = 'list';
        session()->flash('message', 'Inscripción manual realizada con éxito.');
    }

    public function editAthlete($id)
    {
        $athlete = Athlete::findOrFail($id);
        $this->editingAthleteId = $athlete->id;
        $this->identity_document = $athlete->identity_document;
        $this->name = $athlete->name;
        $this->last_name = $athlete->last_name;
        $this->gender = $athlete->gender;
        $this->date_of_birth = $athlete->date_of_birth ? $athlete->date_of_birth->format('Y-m-d') : '';
        $this->phone = $athlete->phone;
        $this->email = $athlete->email;
        $this->blood_type = $athlete->blood_type;
        $this->club = $athlete->club;
        $this->carnet_number = $athlete->carnet_number;
        $this->city = $athlete->city;
        $this->country = $athlete->country ?? 'Paraguay';
        
        $this->view = 'edit_athlete';
    }

    public function updateAthlete()
    {
        $this->validate([
            'identity_document' => 'required|unique:athletes,identity_document,' . $this->editingAthleteId,
            'name' => 'required',
            'last_name' => 'required',
        ]);

        $athlete = Athlete::findOrFail($this->editingAthleteId);
        $athlete->update([
            'identity_document' => $this->identity_document,
            'name' => $this->name,
            'last_name' => $this->last_name,
            'gender' => $this->gender,
            'date_of_birth' => $this->date_of_birth,
            'phone' => $this->phone,
            'email' => $this->email,
            'blood_type' => $this->blood_type,
            'club' => $this->club,
            'carnet_number' => $this->carnet_number,
            'city' => $this->city,
            'country' => $this->country,
        ]);

        $this->view = 'list';
        session()->flash('message', 'Datos del atleta actualizados con éxito.');
    }

    public function deleteRegistration($id)
    {
        Registration::find($id)?->delete();
    }

    public function openImport()
    {
        $this->reset('importFile');
        $this->view = 'import';
    }

    public function importAthletes()
    {
        $this->validate([
            'importFile' => 'required|file|mimes:csv,txt|max:2048', // Max 2MB
        ]);

        $path = $this->importFile->getRealPath();
        $file = fopen($path, 'r');
        
        $header = fgetcsv($file); // Read the header row
        
        // Define expected columns (flexible matching could be added later, but strict for now)
        // Expected: cedula, nombre, apellido, genero, fecha_nacimiento, club, telefono, email, ciudad
        
        $count = 0;
        while (($row = fgetcsv($file)) !== false) {
            // Basic mapping (assuming strict column order for simplicity in this MVP)
            // 0: cedula, 1: nombre, 2: apellido, 3: genero (M/F), 4: fecha_nacimiento (Y-m-d), 5: telefono, 6: email
            
            if (count($row) >= 3) { // At least cedula, nombre, apellido
                $identity = trim($row[0]);
                if (!empty($identity)) {
                    Athlete::updateOrCreate(
                        ['identity_document' => $identity],
                        [
                            'name' => trim($row[1] ?? ''),
                            'last_name' => trim($row[2] ?? ''),
                            'gender' => isset($row[3]) && in_array(strtoupper(trim($row[3])), ['M', 'F']) ? strtoupper(trim($row[3])) : null,
                            'date_of_birth' => !empty(trim($row[4] ?? '')) ? trim($row[4]) : null,
                            'phone' => trim($row[5] ?? ''),
                            'email' => trim($row[6] ?? ''),
                        ]
                    );
                    $count++;
                }
            }
        }
        
        fclose($file);
        
        $this->view = 'list';
        session()->flash('message', "Importación completada. Se procesaron/actualizaron $count atletas.");
    }

    public function downloadTemplate()
    {
        $filename = "plantilla_atletas.csv";
        $headers = [
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=$filename",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        ];

        $callback = function() {
            $file = fopen('php://output', 'w');
            // Headers
            fputcsv($file, ['Cedula', 'Nombres', 'Apellidos', 'Genero', 'Fecha_Nacimiento', 'Telefono', 'Email']);
            // Example row
            fputcsv($file, ['1234567', 'Juan Carlos', 'Perez', 'M', '1990-05-15', '0981123456', 'juan@ejemplo.com']);
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function exportToCSV()
    {
        $query = Registration::with(['athlete', 'event', 'category', 'distance']);

        if ($this->search) {
            $query->whereHas('athlete', function($q) {
                $q->where('name', 'like', '%'.$this->search.'%')
                  ->orWhere('last_name', 'like', '%'.$this->search.'%')
                  ->orWhere('identity_document', 'like', '%'.$this->search.'%');
            })->orWhere('bib_number', 'like', '%'.$this->search.'%');
        }

        if ($this->filter_event) {
            $query->where('event_id', $this->filter_event);
        }

        $registrations = $query->orderBy('created_at', 'desc')->get();

        $filename = "inscripciones_" . now()->format('Y-m-d_H-i') . ".csv";
        $headers = [
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=$filename",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        ];

        $callback = function() use ($registrations) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['ID', 'CI', 'Nombre', 'Apellido', 'Dorsal', 'Evento', 'Distancia', 'Categoria', 'Pago', 'Talle', 'Fecha']);

            foreach ($registrations as $reg) {
                fputcsv($file, [
                    $reg->id,
                    $reg->athlete->identity_document,
                    $reg->athlete->name,
                    $reg->athlete->last_name,
                    $reg->bib_number ?? '-',
                    $reg->event->title ?? 'N/A',
                    $reg->distance->name ?? 'N/A',
                    $reg->category->name ?? 'N/A',
                    $reg->payment_status,
                    $reg->tshirt_size,
                    $reg->created_at->format('d/m/Y H:i'),
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function render()
    {
        $query = Registration::with(['athlete', 'event', 'category', 'distance']);

        if ($this->search) {
            $query->whereHas('athlete', function($q) {
                $q->where('name', 'like', '%'.$this->search.'%')
                  ->orWhere('last_name', 'like', '%'.$this->search.'%')
                  ->orWhere('identity_document', 'like', '%'.$this->search.'%');
            })->orWhere('bib_number', 'like', '%'.$this->search.'%');
        }

        if ($this->filter_event) {
            $query->where('event_id', $this->filter_event);
        }

        $registrations = $query->orderBy('created_at', 'desc')->get();
        
        // 1. Event list for Filter (Main Tab)
        $eventsQuery = \App\Models\Event::query()->orderBy('date', 'desc');
        if ($this->eventSearchTerm) {
            $eventsQuery->where('title', 'ilike', '%' . $this->eventSearchTerm . '%');
        }
        $events = $eventsQuery->take(15)->get();

        // 2. Event list for New Registration
        $regEventsQuery = \App\Models\Event::query()->orderBy('date', 'desc');
        if ($this->registrationEventSearchTerm) {
            $regEventsQuery->where('title', 'ilike', '%' . $this->registrationEventSearchTerm . '%');
        }
        $registrationEvents = $regEventsQuery->take(15)->get();
        
        $selectedEvent = $this->selected_event_id ? \App\Models\Event::with(['distances', 'categories'])->find($this->selected_event_id) : null;
        
        $athletesMaster = collect();
        if ($this->tab === 'athletes') {
            $athletesQuery = Athlete::query();
            if ($this->search) {
                $athletesQuery->where(function($q) {
                    $q->where('name', 'ilike', '%'.$this->search.'%')
                      ->orWhere('last_name', 'ilike', '%'.$this->search.'%')
                      ->orWhere('identity_document', 'ilike', '%'.$this->search.'%');
                });
            }
            $athletesMaster = $athletesQuery->orderBy('last_name')->paginate(20);
        }

        return view('livewire.admin.athletes', [
            'registrations' => $registrations,
            'athletesMaster' => $athletesMaster,
            'events' => $events,
            'registrationEvents' => $registrationEvents,
            'eventDistances' => $selectedEvent?->distances ?? collect(),
            'eventCategories' => $selectedEvent?->categories->when($this->selected_distance_id, function($q) {
                return $q->where('event_distance_id', $this->selected_distance_id);
            }) ?? collect(),
        ])->layout('components.layouts.admin', ['title' => 'Atletas / Inscripciones']);
    }
}
