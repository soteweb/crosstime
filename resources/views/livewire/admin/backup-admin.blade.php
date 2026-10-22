<div class="space-y-8" x-data="{ downloading: false }">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h2 class="text-xl font-bold text-gray-900 border-l-4 border-red-600 pl-4 uppercase tracking-tighter">Copias de Seguridad</h2>
            <p class="text-[10px] text-gray-400 ml-4 font-bold uppercase tracking-widest mt-0.5">Respaldo y descarga local de la base de datos</p>
        </div>
        <div class="flex items-center gap-2">
            <span class="inline-flex items-center px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-wider bg-green-50 text-green-700 border border-green-200">
                <span class="w-2 h-2 rounded-full bg-green-500 mr-2 animate-pulse"></span>
                Base de Datos Activa
            </span>
        </div>
    </div>

    <!-- Mensajes de Notificación -->
    @if(session()->has('message'))
        <div class="bg-green-50 border-l-4 border-green-500 p-4 rounded-r-2xl shadow-sm">
            <p class="text-sm text-green-800 font-bold">{{ session('message') }}</p>
        </div>
    @endif
    @if(session()->has('error'))
        <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded-r-2xl shadow-sm">
            <p class="text-sm text-red-800 font-bold">{{ session('error') }}</p>
        </div>
    @endif

    <!-- Métricas del Sistema -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm">
            <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest block">Motor / BD</span>
            <div class="flex items-baseline gap-2 mt-1">
                <span class="text-xl font-black text-gray-900">{{ $stats['driver'] }}</span>
                <span class="text-xs font-bold text-gray-500 truncate">({{ $stats['database'] }})</span>
            </div>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm">
            <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest block">Tablas Totales</span>
            <span class="text-2xl font-black text-red-600 mt-1 block">{{ $stats['tables_count'] }}</span>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm">
            <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest block">Tamaño Estimado</span>
            <span class="text-2xl font-black text-gray-900 mt-1 block">{{ $stats['size_formatted'] }}</span>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm">
            <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest block">Atletas Registrados</span>
            <span class="text-2xl font-black text-blue-600 mt-1 block">{{ number_format($metrics['athletes']) }}</span>
        </div>
    </div>

    <!-- Tarjeta Principal de Descarga -->
    <div class="bg-gradient-to-br from-gray-900 via-gray-900 to-gray-800 text-white rounded-3xl p-6 md:p-10 shadow-xl border border-gray-800 relative overflow-hidden">
        <!-- Decoración de fondo -->
        <div class="absolute -right-10 -bottom-10 w-64 h-64 bg-red-600/10 rounded-full blur-3xl pointer-events-none"></div>

        <div class="relative z-10 max-w-3xl">
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-lg bg-red-600/20 text-red-400 border border-red-500/30 text-[10px] font-black uppercase tracking-widest mb-4">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                Zona de Seguridad / Super Admin
            </div>

            <h3 class="text-2xl md:text-3xl font-black uppercase tracking-tight mb-3">
                Descarga de Copia de Seguridad Local
            </h3>
            <p class="text-gray-300 text-sm leading-relaxed mb-8">
                Genera un volcado completo con todas las tablas del sistema: eventos, atletas históricos, categorías, inscripciones, pagos, clubes y páginas CMS. Al hacer clic, el archivo se transmitirá directamente a la carpeta de descargas de tu computadora.
            </p>

            <!-- Botones de Acción -->
            <div class="flex flex-col sm:flex-row gap-4">
                <!-- Botón SQL Plano -->
                <a href="{{ route('admin.backup.download', ['format' => 'sql']) }}" 
                   @click="downloading = true; setTimeout(() => downloading = false, 5000)"
                   class="flex-1 bg-red-600 hover:bg-red-700 text-white font-black py-4 px-6 rounded-2xl transition-all shadow-lg shadow-red-600/30 flex items-center justify-center gap-3 text-xs uppercase tracking-widest hover:-translate-y-0.5 active:translate-y-0">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                    <span>Descargar SQL (.sql)</span>
                </a>

                <!-- Botón SQL GZ Comprimido -->
                <a href="{{ route('admin.backup.download', ['format' => 'gzip']) }}" 
                   @click="downloading = true; setTimeout(() => downloading = false, 5000)"
                   class="flex-1 bg-gray-800 hover:bg-gray-700 border border-gray-700 text-white font-black py-4 px-6 rounded-2xl transition-all flex items-center justify-center gap-3 text-xs uppercase tracking-widest hover:-translate-y-0.5 active:translate-y-0">
                    <svg class="w-5 h-5 flex-shrink-0 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"></path></svg>
                    <span>Descargar Comprimido (.sql.gz)</span>
                </a>
            </div>

            <!-- Indicador en vivo -->
            <div x-show="downloading" x-cloak class="mt-4 flex items-center gap-2 text-xs text-yellow-300 font-bold animate-pulse">
                <svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path></svg>
                Generando respaldo y transfiriendo al navegador...
            </div>
        </div>
    </div>

    <!-- Guía y Buenas Prácticas -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Tarjeta de Buenas Prácticas -->
        <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm space-y-4">
            <div class="flex items-center gap-3 text-gray-900 font-bold uppercase text-xs tracking-wider">
                <span class="w-8 h-8 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </span>
                Recomendaciones de Seguridad
            </div>
            <ul class="space-y-3 text-xs text-gray-600 leading-relaxed">
                <li class="flex items-start gap-2">
                    <span class="text-red-500 font-bold">•</span>
                    <span><strong>Frecuencia:</strong> Se recomienda descargar una copia antes de abrir inscripciones masivas y otra al culminar cada evento deportivo.</span>
                </li>
                <li class="flex items-start gap-2">
                    <span class="text-red-500 font-bold">•</span>
                    <span><strong>Almacenamiento seguro:</strong> Guarda tus archivos descargados en tu disco local y en un servicio en la nube (como Google Drive u OneDrive).</span>
                </li>
                <li class="flex items-start gap-2">
                    <span class="text-red-500 font-bold">•</span>
                    <span><strong>Nombres automáticos:</strong> Los archivos se descargan con fecha y hora exacta (ej. <code>backup_itapuacross_2026-10-22_18-05.sql</code>) para que puedas identificar cada versión.</span>
                </li>
            </ul>
        </div>

        <!-- Tarjeta de Restauración -->
        <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm space-y-4">
            <div class="flex items-center gap-3 text-gray-900 font-bold uppercase text-xs tracking-wider">
                <span class="w-8 h-8 rounded-xl bg-green-50 text-green-600 flex items-center justify-center">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                </span>
                ¿Cómo Restaurar esta Copia?
            </div>
            <p class="text-xs text-gray-600 leading-relaxed">
                Si alguna vez necesitas restaurar el sistema en tu VPS anterior, en un servidor nuevo o en tu máquina local:
            </p>
            <div class="bg-gray-50 p-3 rounded-xl border border-gray-100 text-[11px] font-mono text-gray-700 space-y-1">
                <p class="text-gray-400 font-sans font-bold text-[10px] uppercase">Desde la terminal del VPS:</p>
                <p>mysql -u root -p itapuacross &lt; backup_itapuacross.sql</p>
                <p class="text-gray-400 font-sans font-bold text-[10px] uppercase pt-2">O desde phpMyAdmin / DBeaver:</p>
                <p>Pestaña <strong>Importar</strong> &rarr; Seleccionar archivo <code>.sql</code> &rarr; <strong>Continuar</strong>.</p>
            </div>
        </div>
    </div>
</div>
