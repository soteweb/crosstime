<?php

namespace App\Livewire\Admin;

use App\Models\Athlete;
use App\Models\Event;
use App\Models\Payment;
use App\Models\Registration;
use App\Models\User;
use App\Services\DatabaseBackupService;
use Livewire\Component;

class BackupAdmin extends Component
{
    public function render(DatabaseBackupService $backupService)
    {
        if (auth()->user()->role !== User::ROLE_SUPER_ADMIN) {
            abort(403, 'Acceso restringido para Administradores Principales.');
        }

        $stats = $backupService->getDatabaseStats();

        $metrics = [
            'athletes' => Athlete::count(),
            'events' => Event::count(),
            'registrations' => Registration::count(),
            'payments' => Payment::count(),
            'users' => User::count(),
        ];

        return view('livewire.admin.backup-admin', compact('stats', 'metrics'))
            ->layout('components.layouts.admin', ['title' => 'Copias de Seguridad (Base de Datos)']);
    }
}
