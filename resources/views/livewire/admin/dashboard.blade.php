<div>
    <div class="mb-6">
        <h3 class="text-xl font-bold text-gray-900 border-l-4 border-red-600 pl-4 uppercase tracking-tighter">Resumen del Sistema</h3>
        <p class="text-[10px] text-gray-400 ml-4 font-bold uppercase tracking-widest mt-0.5">Estadísticas en tiempo real</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Eventos Activos -->
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-200 flex items-center justify-between">
            <div>
                <p class="text-sm font-bold text-gray-500 uppercase tracking-wide">Eventos Activos</p>
                <h4 class="text-3xl font-black text-gray-900 mt-1">{{ $metrics['events_active'] }} <span class="text-sm font-medium text-gray-400">/ {{ $metrics['events_total'] }}</span></h4>
            </div>
            <div class="bg-red-50 p-3 rounded-xl text-red-600">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
            </div>
        </div>

        <!-- Atletas -->
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-200 flex items-center justify-between">
            <div>
                <p class="text-sm font-bold text-gray-500 uppercase tracking-wide">Atletas</p>
                <h4 class="text-3xl font-black text-gray-900 mt-1">{{ $metrics['athletes'] }}</h4>
            </div>
            <div class="bg-blue-50 p-3 rounded-xl text-blue-600">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
            </div>
        </div>

        <!-- Registros Totales -->
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-200 flex items-center justify-between">
            <div>
                <p class="text-sm font-bold text-gray-500 uppercase tracking-wide">Inscripciones</p>
                <h4 class="text-3xl font-black text-gray-900 mt-1">{{ $metrics['registrations'] }}</h4>
            </div>
            <div class="bg-green-50 p-3 rounded-xl text-green-600">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
            </div>
        </div>

        <!-- Ingresos por Cobrar -->
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-200 flex items-center justify-between">
            <div>
                <p class="text-sm font-bold text-gray-500 uppercase tracking-wide">Por Cobrar (Gs)</p>
                <h4 class="text-2xl font-bold text-red-600 mt-1">{{ number_format($metrics['revenue_pending'], 0, ',', '.') }}</h4>
            </div>
            <div class="bg-yellow-50 p-3 rounded-xl text-yellow-600">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
        </div>
    </div>

    <!-- Entrega de Kits Progress -->
    <div class="bg-white rounded-2xl p-8 shadow-sm border border-gray-200 mb-8 overflow-hidden relative">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-6">
            <div>
                <h4 class="text-xl font-bold text-gray-900">Estado de Entrega de Kits</h4>
                <p class="text-sm text-gray-500 font-medium">Progreso total de inscripciones confirmadas que ya retiraron su dorsal.</p>
            </div>
            <div class="text-right">
                <span class="text-3xl font-black text-red-600">{{ $metrics['kits_delivered'] }}</span>
                <span class="text-lg font-bold text-gray-400">/ {{ $metrics['kits_total'] }}</span>
                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mt-1">Kits Entregados</p>
            </div>
        </div>
        
        <div class="relative pt-1">
            <div class="overflow-hidden h-4 mb-4 text-xs flex rounded-full bg-gray-100 border border-gray-200">
                @php
                    $percentage = $metrics['kits_total'] > 0 ? ($metrics['kits_delivered'] / $metrics['kits_total']) * 100 : 0;
                @endphp
                <div style="width:{{ $percentage }}%" class="shadow-none flex flex-col text-center whitespace-nowrap text-white justify-center bg-red-600 transition-all duration-500"></div>
            </div>
            <div class="flex items-center justify-between text-xs font-bold uppercase tracking-tighter text-gray-500">
                <span>0%</span>
                <span>{{ number_format($percentage, 1) }}% completado</span>
                <span>100%</span>
            </div>
        </div>

        <div class="mt-6 flex flex-wrap gap-4">
            <div class="bg-red-50 px-4 py-2 rounded-xl border border-red-100 flex items-center">
                <div class="w-2 h-2 rounded-full bg-red-600 mr-2"></div>
                <span class="text-xs font-bold text-red-700">{{ $metrics['kits_delivered'] }} Acreditados listos</span>
            </div>
            <div class="bg-gray-50 px-4 py-2 rounded-xl border border-gray-200 flex items-center">
                <div class="w-2 h-2 rounded-full bg-gray-300 mr-2"></div>
                <span class="text-xs font-bold text-gray-600">{{ $metrics['kits_total'] - $metrics['kits_delivered'] }} Pendientes de retiro</span>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-200">
            <h4 class="text-xl font-bold text-gray-900 border-b border-gray-100 pb-3 mb-4">Acciones Rápidas</h4>
            <div class="space-y-3">
                <a href="{{ route('admin.events') }}" class="block p-4 border border-gray-200 rounded-xl hover:border-red-600 hover:bg-red-50 transition-all font-bold text-gray-700 flex justify-between items-center group">
                    Crear un nuevo Evento
                    <svg class="w-5 h-5 text-gray-400 group-hover:text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                </a>
                <a href="{{ route('admin.athletes') }}" class="block p-4 border border-gray-200 rounded-xl hover:border-red-600 hover:bg-red-50 transition-all font-bold text-gray-700 flex justify-between items-center group">
                    Ver base de datos de Atletas
                    <svg class="w-5 h-5 text-gray-400 group-hover:text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                </a>
            </div>
        </div>
        
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-200">
            <h4 class="text-xl font-bold text-gray-900 border-b border-gray-100 pb-3 mb-4">Sistema</h4>
            <p class="text-gray-600 text-sm font-medium">El panel se encuentra sincronizado. Las inscripciones web están ingresando en vivo con estado <b>Pendiente</b> de pago en la base de datos hasta que decida gestionarlos administrativamente.</p>
        </div>
    </div>
</div>
