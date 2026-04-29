<div class="space-y-8">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h2 class="text-3xl font-black text-gray-900 border-l-4 border-red-600 pl-4 uppercase tracking-tighter">Reporte Financiero</h2>
            <p class="text-[10px] text-gray-400 ml-4 font-bold uppercase tracking-widest mt-1">Control de ingresos y proyecciones</p>
        </div>
        
        <div class="w-full md:w-80">
            <select wire:model.live="selected_event_id" class="w-full rounded-2xl border-gray-200 bg-white font-bold p-4 shadow-sm focus:border-red-600 focus:ring-red-600 transition-all">
                <option value="">Seleccionar Evento...</option>
                @foreach($events as $e)
                    <option value="{{ $e->id }}">{{ $e->title }} ({{ $e->date->format('d/m/Y') }})</option>
                @endforeach
            </select>
        </div>
    </div>

    @if(!$selected_event_id)
        <div class="bg-white rounded-[2rem] p-20 text-center border-2 border-dashed border-gray-100">
            <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-6">
                <svg class="w-10 h-10 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
            </div>
            <h3 class="text-xl font-bold text-gray-900 uppercase tracking-tighter">Selecciona un evento para ver los números</h3>
            <p class="text-sm text-gray-400 font-medium mt-2">Los datos se actualizarán automáticamente al elegir uno.</p>
        </div>
    @else
        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100">
                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Total Recaudado</p>
                <h4 class="text-3xl font-black text-gray-900">{{ number_format($stats['total_collected'], 0, ',', '.') }} <span class="text-xs text-gray-400">Gs.</span></h4>
                <div class="mt-4 flex items-center text-[10px] font-bold text-green-600 uppercase">
                    <span class="flex h-2 w-2 rounded-full bg-green-500 mr-2"></span>
                    Dinero en Caja
                </div>
            </div>

            <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100">
                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Proyección Pendiente</p>
                <h4 class="text-3xl font-black text-red-600">{{ number_format($stats['pending_amount'], 0, ',', '.') }} <span class="text-xs text-gray-400">Gs.</span></h4>
                <div class="mt-4 flex items-center text-[10px] font-bold text-gray-400 uppercase">
                    <span class="flex h-2 w-2 rounded-full bg-red-400 mr-2"></span>
                    Por Cobrar
                </div>
            </div>

            <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100">
                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Inscriptos (Pagados)</p>
                <h4 class="text-3xl font-black text-gray-900">{{ $stats['paid_count'] }} <span class="text-sm text-gray-400">/ {{ $stats['registrations_count'] }}</span></h4>
                <div class="mt-4 w-full bg-gray-100 rounded-full h-1.5 overflow-hidden">
                    @php $percent = $stats['registrations_count'] > 0 ? ($stats['paid_count'] / $stats['registrations_count']) * 100 : 0; @endphp
                    <div class="bg-red-600 h-full rounded-full" style="width: {{ $percent }}%"></div>
                </div>
            </div>

            <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100">
                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Ticket Promedio</p>
                @php $avg = $stats['paid_count'] > 0 ? $stats['total_collected'] / $stats['paid_count'] : 0; @endphp
                <h4 class="text-3xl font-black text-gray-900">{{ number_format($avg, 0, ',', '.') }} <span class="text-xs text-gray-400">Gs.</span></h4>
                <div class="mt-4 flex items-center text-[10px] font-bold text-blue-600 uppercase">
                    Por Atleta
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Por Medio de Pago -->
            <div class="bg-white rounded-[2rem] shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-6 border-b border-gray-50 flex items-center justify-between">
                    <h5 class="text-sm font-black uppercase tracking-widest text-gray-900">Ingresos por Medio de Pago</h5>
                    <svg class="w-5 h-5 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        @forelse($stats['by_method'] as $method)
                            <div>
                                <div class="flex justify-between items-end mb-1">
                                    <span class="text-xs font-bold text-gray-600 uppercase tracking-tight">{{ $method->payment_method }}</span>
                                    <span class="text-sm font-black text-gray-900">{{ number_format($method->total, 0, ',', '.') }} Gs.</span>
                                </div>
                                <div class="w-full bg-gray-50 rounded-full h-2 overflow-hidden">
                                    @php $methodPercent = $stats['total_collected'] > 0 ? ($method->total / $stats['total_collected']) * 100 : 0; @endphp
                                    <div class="bg-red-600 h-full rounded-full" style="width: {{ $methodPercent }}%"></div>
                                </div>
                            </div>
                        @empty
                            <p class="text-center py-10 text-gray-400 font-bold text-xs uppercase">Sin datos de pagos confirmados</p>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Por Categoría -->
            <div class="bg-white rounded-[2rem] shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-6 border-b border-gray-50 flex items-center justify-between">
                    <h5 class="text-sm font-black uppercase tracking-widest text-gray-900">Inscripciones por Categoría</h5>
                    <svg class="w-5 h-5 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 005.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                </div>
                <div class="p-6 overflow-x-auto">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="text-[10px] font-black text-gray-400 uppercase tracking-widest border-b border-gray-100">
                                <th class="pb-4">Categoría</th>
                                <th class="pb-4 text-center">Inscriptos</th>
                                <th class="pb-4 text-center">Pagados</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @foreach($stats['by_category'] as $cat)
                                <tr>
                                    <td class="py-4 text-xs font-bold text-gray-700 uppercase">{{ $cat->name }}</td>
                                    <td class="py-4 text-center font-black text-gray-900 text-sm">{{ $cat->count }}</td>
                                    <td class="py-4 text-center">
                                        <span class="inline-block px-2 py-1 bg-green-50 text-green-600 text-[10px] font-black rounded-lg">
                                            {{ $cat->paid_count }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endif
</div>
