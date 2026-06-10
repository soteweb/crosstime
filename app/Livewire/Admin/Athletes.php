<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use App\Models\Registration;
use App\Models\Athlete;
use App\Traits\HasWhatsApp;

class Athletes extends Component
{
    use WithFileUploads, WithPagination, HasWhatsApp;

    public $search = '';
    public $filter_event = '';
    public $tab = 'registrations'; // 'registrations', 'athletes', 'checkin'
    public $showFullList = false;
    
    public $view = 'list'; // 'list', 'registration', 'edit_athlete', 'import', 'bib_assignment', 'detail'
    public $selectedRegistrationId;
    public $importFile;
    public $editingAthleteId;
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
    public $club_id = null;
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

    public function togglePaymentStatus($id)
    {
        $reg = Registration::find($id);
        if ($reg) {
            if ($reg->status === 'paid' || $reg->status === 'inscribed') {
                $reg->status = 'pre_inscribed';
                $reg->payment_status = 'pending';
            } else {
                $reg->status = 'paid';
                $reg->payment_status = 'paid';
            }
            $reg->save();
            session()->flash('message', 'Estado de inscripción actualizado.');
        }
    }

    public function changeStatus($registrationId, $newStatus)
    {
        $reg = Registration::find($registrationId);
        if ($reg) {
            if ($newStatus === 'participant') {
                $this->selectedRegistrationId = $registrationId;
                $this->bib_number = $reg->bib_number ?? '';
                $this->bibIsEdit = $reg->status === 'participant';
                $this->view = 'bib_assignment';
                return;
            }

            if ($newStatus === 'inscribed' && $reg->payment_status !== 'paid') {
                $this->openPayment($registrationId);
                return;
            }

            if ($newStatus === 'pre_inscribed') {
                $this->revertToPreInscribed($registrationId);
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
        $this->bib_number = $reg->bib_number ?? '';
        $this->view = 'payment_validation';
    }

    public function savePayment()
    {
        $this->validate([
            'payment_method' => 'required',
            'payment_date' => 'required|date',
            'bib_number' => 'required|string',
        ]);

        $reg = Registration::findOrFail($this->selectedRegistrationId);
        
        // Validate Bib Uniqueness in this event
        $exists = Registration::where('event_id', $reg->event_id)
            ->where('bib_number', $this->bib_number)
            ->where('id', '!=', $reg->id)
            ->exists();

        if ($exists) {
            $this->addError('bib_number', 'Este número de dorsal ya está asignado en este evento.');
            return;
        }
        
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
            'status' => 'inscribed',
            'bib_number' => $this->bib_number
        ]);

        $this->whatsappUrl = $this->generateWhatsAppUrl($reg);

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
            if (empty($this->bib_number)) {
                session()->flash('error', 'El número de dorsal es obligatorio.');
                return;
            }

            // Validar unicidad del dorsal en este evento
            $exists = Registration::where('event_id', $reg->event_id)
                ->where('bib_number', $this->bib_number)
                ->where('id', '!=', $reg->id)
                ->exists();

            if ($exists) {
                session()->flash('error', "El dorsal #{$this->bib_number} ya está asignado a otro atleta en este evento.");
                return;
            }

            $reg->status = 'participant';
            $reg->bib_number = $this->bib_number;
            $reg->payment_status = 'paid';
            if (!$reg->kit_delivered_at) {
                $reg->kit_delivered_at = now();
                $reg->kit_delivered_by = auth()->id();
            }
            $reg->save();
        }
        $this->view = 'list';

        $wasEdit = $this->bibIsEdit;
        
        $this->reset(['selectedRegistrationId', 'bib_number', 'bibIsEdit']);
        session()->flash('message', $wasEdit
            ? 'Dorsal actualizado correctamente.'
            : 'Kit entregado y atleta acreditado.'
        );
    }

    public function revertToPreInscribed($registrationId)
    {
        $reg = Registration::find($registrationId);
        if ($reg) {
            $reg->update([
                'status' => 'pre_inscribed',
                'payment_status' => 'pending',
                'bib_number' => null,
                'kit_delivered_at' => null,
                'kit_delivered_by' => null
            ]);
            
            $reg->payments()->delete();
            
            $this->view = 'list';
            session()->flash('message', 'Inscripción revertida a Pre-Inscripto correctamente.');
        }
    }

    public function confirmKitDelivery($registrationId)
    {
        $reg = Registration::find($registrationId);
        if ($reg) {
            // 1. Validar que tenga un dorsal (en DB o en el input de pantalla)
            $bibToAssign = $reg->bib_number ?: $this->bib_number;

            if (empty($bibToAssign)) {
                session()->flash('error', 'Debes asignar un dorsal antes de acreditar.');
                return;
            }

            // 2. Validar unicidad del dorsal
            $exists = Registration::where('event_id', $reg->event_id)
                ->where('bib_number', $bibToAssign)
                ->where('id', '!=', $reg->id)
                ->exists();

            if ($exists) {
                session()->flash('error', "El dorsal #{$bibToAssign} ya está asignado a otro participante en este evento.");
                return;
            }

            // 3. Validar estado de pago
            if ($reg->payment_status !== 'paid' && $this->payment_amount <= 0) {
                session()->flash('error', 'No se puede acreditar un atleta con pago pendiente.');
                return;
            }

            $reg->update([
                'status' => 'participant',
                'bib_number' => $bibToAssign,
                'kit_delivered_at' => now(),
                'kit_delivered_by' => auth()->id(),
                'payment_status' => 'paid'
            ]);

            session()->flash('message', "Kit entregado a {$reg->athlete->name} (Dorsal #{$bibToAssign})");
            
            $this->whatsappUrl = $this->generateWhatsAppUrl($reg);
        }
    }

    public function selectRegistration($id)
    {
        $reg = Registration::with(['payments', 'category', 'distance'])->findOrFail($id);
        $this->selectedRegistrationId = $id;
        $this->bib_number = $reg->bib_number ?? '';
        
        $this->selected_event_id = $reg->event_id;
        $this->selected_distance_id = $reg->event_distance_id;
        $this->selected_category_id = $reg->event_category_id;
        $this->tshirt_size = $reg->tshirt_size;

        $lastPayment = $reg->payments()->latest()->first();
        $this->payment_amount = $lastPayment ? $lastPayment->amount : ($reg->category?->price ?? 0);
        $this->payment_method = $lastPayment ? $lastPayment->payment_method : ($reg->payment_method ?: 'Efectivo');
        $this->payment_reference = $lastPayment ? $lastPayment->reference : '';
        $this->payment_date = $lastPayment ? $lastPayment->payment_date : now()->format('Y-m-d');
        
        $this->view = 'detail';
    }

    public function updateRegistrationDetails()
    {
        $reg = Registration::findOrFail($this->selectedRegistrationId);
        $reg->update([
            'event_distance_id' => $this->selected_distance_id,
            'event_category_id' => $this->selected_category_id,
            'tshirt_size' => $this->tshirt_size,
        ]);

        session()->flash('message', 'Detalles de inscripción actualizados.');
    }

    public function openRegistration()
    {
        $this->reset(['identity_document', 'name', 'last_name', 'gender', 'date_of_birth', 'phone', 'email', 'selected_event_id', 'selected_distance_id', 'selected_category_id', 'tshirt_size']);
        $this->view = 'registration';
    }

    public function setTab($tab)
    {
        $this->tab = $tab;
        $this->view = 'list';
        // Quitamos el reseteo de filter_event para que persista la navegación
        $this->search = '';
    }

    public function setFilterEvent($eventId)
    {
        $this->filter_event = $eventId;
        $this->tab = 'registrations';
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
            $this->blood_type = $athlete->blood_type;
            $this->club = $athlete->club;
            $this->club_id = $athlete->club_id;
            $this->carnet_number = $athlete->carnet_number;
            $this->city = $athlete->city;
            $this->country = $athlete->country ?? 'Paraguay';
        }
    }

    public function saveRegistration()
    {
        $this->validate([
            'identity_document' => 'required',
            'name' => 'required',
            'last_name' => 'required',
            'gender' => 'required',
            'date_of_birth' => 'required|date',
            'phone' => 'required',
            'email' => 'required|email',
            'selected_event_id' => 'required',
            'selected_distance_id' => 'required',
            'selected_category_id' => 'required',
        ]);

        // Evitar duplicados: Un atleta no puede estar 2 veces en el mismo evento
        $athlete = \App\Models\Athlete::where('identity_document', $this->identity_document)->first();
        if ($athlete) {
            $exists = Registration::where('athlete_id', $athlete->id)
                ->where('event_id', $this->selected_event_id)
                ->exists();
            
            if ($exists) {
                session()->flash('error', 'Este atleta ya se encuentra inscripto en este evento.');
                return;
            }
        }

        $athlete = \App\Models\Athlete::updateOrCreate(
            ['identity_document' => $this->identity_document],
            [
                'name' => $this->name,
                'last_name' => $this->last_name,
                'gender' => $this->gender,
                'date_of_birth' => $this->date_of_birth,
                'phone' => $this->phone,
                'email' => $this->email,
                'blood_type' => $this->blood_type,
                'club' => $this->club_id ? \App\Models\Club::find($this->club_id)?->name : null,
                'club_id' => $this->club_id ?: null,
                'carnet_number' => $this->carnet_number,
                'city' => $this->city,
                'country' => $this->country,
            ]
        );

        $registration = \App\Models\Registration::create([
            'athlete_id' => $athlete->id,
            'event_id' => $this->selected_event_id,
            'event_distance_id' => $this->selected_distance_id,
            'event_category_id' => $this->selected_category_id,
            'tshirt_size' => $this->tshirt_size,
            'status' => 'inscribed',
            'payment_status' => 'pending'
        ]);

        session()->flash('message', 'Inscripción manual exitosa para ' . $athlete->name);
        $this->setTab('registrations');
    }

    public function viewAthlete($id)
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
        $this->club_id = $athlete->club_id;
        $this->carnet_number = $athlete->carnet_number;
        $this->city = $athlete->city;
        $this->country = $athlete->country ?? 'Paraguay';
        
        $this->view = 'view_athlete';
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
        $this->club_id = $athlete->club_id;
        $this->carnet_number = $athlete->carnet_number;
        $this->city = $athlete->city;
        $this->country = $athlete->country ?? 'Paraguay';
        
        $this->view = 'edit_athlete';
    }

    public function deleteAthlete($id)
    {
        $athlete = Athlete::findOrFail($id);
        
        // Check if has registrations
        if ($athlete->registrations()->exists()) {
            session()->flash('error', "No se puede eliminar al atleta {$athlete->name} porque tiene inscripciones activas.");
            return;
        }

        $athlete->delete();
        session()->flash('message', "Atleta eliminado correctamente.");
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
            'club' => $this->club_id ? \App\Models\Club::find($this->club_id)?->name : null,
            'club_id' => $this->club_id ?: null,
            'carnet_number' => $this->carnet_number,
            'city' => $this->city,
            'country' => $this->country,
        ]);

        $this->view = 'list';
        session()->flash('message', 'Datos del atleta actualizados con éxito.');
    }

    public function deleteRegistration($id)
    {
        $reg = Registration::find($id);
        if ($reg) {
            if ($reg->status === 'pre_inscribed') {
                $reg->payments()->delete();
                $reg->delete();
                $this->view = 'list';
                session()->flash('message', 'Inscripción eliminada correctamente del listado.');
            } else {
                session()->flash('error', 'Solo se pueden eliminar de forma definitiva las inscripciones en estado Pre-Inscripto. Si desea borrar este atleta, primero reviértalo a Pre-Inscripto.');
            }
        }
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
        $query = $this->getRegistrationsQuery()->orderBy('created_at', 'desc');

        $filename = "inscripciones_" . now()->format('Y-m-d_H-i') . ".csv";
        $headers = [
            "Content-type" => "text/csv; charset=UTF-8",
            "Content-Disposition" => "attachment; filename=$filename",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        ];

        $callback = function() use ($query) {
            $file = fopen('php://output', 'w');
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF)); // BOM para Excel
            
            fputcsv($file, [
                'ID', 'Evento', 'Distancia', 'Categoria', 'Dorsal', 
                'Documento CI', 'Nombres', 'Apellidos', 'Genero', 'Fecha Nacimiento', 
                'Edad (Evento)', 'Telefono', 'Email', 'Ciudad', 'Pais', 
                'Grupo Sanguineo', 'Club', 'Numero Carnet', 'Talle Remera', 
                'Estado Pago', 'Metodo Pago', 'Estado', 'Fecha Inscripcion', 'Observaciones'
            ]);

            $query->chunk(200, function($registrations) use ($file) {
                foreach ($registrations as $reg) {
                    $age = '-';
                    if ($reg->athlete->date_of_birth) {
                        $birthYear = (int) \Carbon\Carbon::parse($reg->athlete->date_of_birth)->format('Y');
                        $eventYear = $reg->event && $reg->event->date ? (int) \Carbon\Carbon::parse($reg->event->date)->format('Y') : (int) date('Y');
                        $age = $eventYear - $birthYear;
                    }

                    fputcsv($file, [
                        $reg->id,
                        $reg->event->title ?? '-',
                        $reg->distance->name ?? '-',
                        $reg->category->name ?? '-',
                        $reg->bib_number ?? '-',
                        $reg->athlete->identity_document ?? '-',
                        $reg->athlete->name ?? '-',
                        $reg->athlete->last_name ?? '-',
                        $reg->athlete->gender ?? '-',
                        $reg->athlete->date_of_birth ? \Carbon\Carbon::parse($reg->athlete->date_of_birth)->format('d/m/Y') : '-',
                        $age,
                        $reg->athlete->phone ?? '-',
                        $reg->athlete->email ?? '-',
                        $reg->athlete->city ?? '-',
                        $reg->athlete->country ?? '-',
                        $reg->athlete->blood_type ?? '-',
                        $reg->athlete->club ?? '-',
                        $reg->athlete->carnet_number ?? '-',
                        $reg->tshirt_size ?? '-',
                        $reg->payment_status ?? '-',
                        $reg->payment_method ?? '-',
                        $reg->status ?? '-',
                        $reg->created_at->format('d/m/Y H:i'),
                        $reg->observation ?? '-',
                    ]);
                }
            });
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    private function getRegistrationsQuery()
    {
        $query = Registration::with(['athlete', 'event', 'category', 'distance']);

        if ($this->search) {
            $query->where(function($q) {
                $q->whereHas('athlete', function($inner) {
                    $inner->where('name', 'ilike', '%'.$this->search.'%')
                          ->orWhere('last_name', 'ilike', '%'.$this->search.'%')
                          ->orWhere('identity_document', 'ilike', '%'.$this->search.'%');
                })->orWhere('bib_number', 'ilike', '%'.$this->search.'%');
            });
        }

        if ($this->filter_event) {
            $query->where('event_id', $this->filter_event);
        }

        return $query;
    }

    public function render()
    {
        $allEvents = \App\Models\Event::orderBy('date', 'desc')->get();
        $upcomingEvents = \App\Models\Event::where('date', '>=', now()->startOfDay())
            ->orderBy('date', 'asc')
            ->take(3)
            ->get();

        $registrationEvents = \App\Models\Event::where('is_active', true)
            ->when($this->registrationEventSearchTerm, function($q) {
                $q->where('title', 'ilike', '%'.$this->registrationEventSearchTerm.'%');
            })->orderBy('date', 'desc')->get();

        $selectedEvent = $this->selected_event_id ? \App\Models\Event::find($this->selected_event_id) : null;

        $registrations = collect();
        $isHub = false;

        $this->tab = $this->tab ?: 'registrations';
        $this->view = $this->view ?: 'list';

        if ($this->tab === 'registrations' && $this->view === 'list') {
            if (empty($this->filter_event) && empty($this->search)) {
                $isHub = true;
            } else {
                $isHub = false;
                $registrations = $this->getRegistrationsQuery()
                    ->orderBy('created_at', 'desc')
                    ->paginate(20);
            }
        }
        
        $athletesMaster = collect();
        if ($this->tab === 'athletes') {
            $athletesQuery = \App\Models\Athlete::query();
            if ($this->search) {
                $athletesQuery->where(function($q) {
                    $q->where('name', 'ilike', '%'.$this->search.'%')
                      ->orWhere('last_name', 'ilike', '%'.$this->search.'%')
                      ->orWhere('identity_document', 'ilike', '%'.$this->search.'%');
                });
            }
            $athletesMaster = $athletesQuery->orderBy('last_name')->paginate(20);
        }

        $checkinList = collect();
        if ($this->tab === 'checkin') {
            $checkinQuery = \App\Models\Registration::query();
            
            if ($this->filter_event) {
                $checkinQuery->where('event_id', $this->filter_event);
            }

            if ($this->search) {
                $checkinQuery->where(function($q) {
                    $q->whereHas('athlete', function($inner) {
                        $inner->where('name', 'ilike', '%'.$this->search.'%')
                              ->orWhere('last_name', 'ilike', '%'.$this->search.'%')
                              ->orWhere('identity_document', 'ilike', '%'.$this->search.'%');
                    })->orWhere('bib_number', 'ilike', '%'.$this->search.'%');
                });
            }

            if ($this->filter_event) {
                $checkinList = $checkinQuery->orderBy('bib_number', 'asc')->paginate(30);
            }
        }

        return view('livewire.admin.athletes', [
            'registrations' => $registrations,
            'athletesMaster' => $athletesMaster,
            'checkinList' => $checkinList,
            'isHub' => $isHub,
            'events' => $upcomingEvents,
            'allEvents' => $allEvents,
            'registrationEvents' => $registrationEvents,
            'eventDistances' => $selectedEvent?->distances ?? collect(),
            'eventCategories' => $selectedEvent?->categories->when($this->selected_distance_id, function($q) {
                return $q->where('event_distance_id', $this->selected_distance_id);
            }) ?? collect(),
            'clubsList' => \App\Models\Club::where('is_active', true)->orderBy('name')->get(),
        ])->layout('components.layouts.admin', ['title' => 'Atletas / Inscripciones']);
    }
}
