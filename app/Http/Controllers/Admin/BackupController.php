<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\DatabaseBackupService;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class BackupController extends Controller
{
    public function download(Request $request, DatabaseBackupService $backupService): BinaryFileResponse
    {
        // Seguridad: Solo Super Admin
        if (!auth()->check() || auth()->user()->role !== User::ROLE_SUPER_ADMIN) {
            abort(403, 'Solo el Administrador Principal (Super Admin) tiene autorización para descargar copias de seguridad.');
        }

        $format = $request->query('format', 'sql');
        $compressGzip = $format === 'gzip' || $format === 'gz';

        $filePath = $backupService->createBackupFile($compressGzip);
        $fileName = basename($filePath);

        $contentType = $compressGzip ? 'application/gzip' : 'application/sql';

        return response()->download($filePath, $fileName, [
            'Content-Type' => $contentType,
            'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
        ])->deleteFileAfterSend(true);
    }
}
