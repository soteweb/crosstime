<div wire:key="athletes-component-root">
    <div class="space-y-6">
        <!-- HEADER DINÁMICO -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4" wire:key="header-controls">
            <div wire:click="$set('showFullList', false)" class="cursor-pointer group">
                <h2 class="text-2xl font-black text-gray-900 border-l-4 border-red-600 pl-4 uppercase tracking-tighter group-hover:text-red-600 transition-colors">Centro de Control</h2>
                <p class="text-[10px] text-gray-400 ml-4 font-bold uppercase tracking-widest mt-0.5">Selecciona un evento para Administrarlo</p>
            </div>
            <div class="flex flex-wrap md:flex-nowrap gap-3">
                @if($view === 'list')
                    @if($tab === 'registrations' && !$isHub)
                        <button wire:click="exportToCSV" class="px-4 py-2 bg-green-600 text-white text-xs font-bold rounded-xl hover:bg-green-700 transition-all shadow-lg flex items-center justify-center">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                            Exportar Excel
                        </button>
                    @endif
                    <button wire:click="openRegistration" class="px-4 py-2 bg-gray-900 text-white text-xs font-bold rounded-xl hover:bg-red-600 transition-all shadow-lg flex items-center justify-center">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                        Nueva Inscripción
                    </button>
                @else
                    <button wire:click="setTab('{{ $tab }}')" class="px-4 py-2 bg-white border border-gray-200 text-gray-700 text-xs font-bold rounded-xl hover:bg-gray-50 transition-all shadow-sm flex items-center justify-center">
                        <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                        Volver al Listado
                    </button>
                @endif
            </div>
        </div>

        @if(session()->has('message'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-xl animate-fadeIn" wire:key="session-message">
                <p class="text-sm font-bold">{{ session('message') }}</p>
            </div>
        @endif

        @if(session()->has('error'))
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded-xl animate-fadeIn" wire:key="session-error">
                <p class="text-sm font-bold">{{ session('error') }}</p>
            </div>
        @endif

        @if($view === 'list')
            <div wire:key="view-list-container">
                @if(!$isHub)
                    <!-- Tabs -->
                    <div class="flex border-b border-gray-200 mb-6" wire:key="tab-navigation">
                        <button wire:click="setTab('registrations')" class="px-6 py-3 text-sm font-bold uppercase tracking-widest transition-all {{ $tab === 'registrations' ? 'border-b-2 border-red-600 text-red-600' : 'text-gray-400 hover:text-gray-600' }}">
                            Inscripciones
                        </button>
                        <button wire:click="setTab('athletes')" class="px-6 py-3 text-sm font-bold uppercase tracking-widest transition-all {{ $tab === 'athletes' ? 'border-b-2 border-red-600 text-red-600' : 'text-gray-400 hover:text-gray-600' }}">
                            Base de Datos
                        </button>
                        <button wire:click="setTab('checkin')" class="px-6 py-3 text-sm font-bold uppercase tracking-widest transition-all {{ $tab === 'checkin' ? 'border-b-2 border-green-600 text-green-600' : 'text-gray-400 hover:text-green-600' }}">
                            Acreditación
                        </button>
                    </div>

                    <!-- Filtros -->
                    <div class="bg-white p-4 rounded-2xl shadow-sm border border-gray-200 flex flex-col md:flex-row gap-4 items-center mb-6" wire:key="filters-area">
                        <div class="flex-1 w-full">
                            <input type="text" wire:model.live.debounce.300ms="search" placeholder="Buscar por nombre, CI o dorsal..." class="w-full rounded-xl border-gray-200 bg-gray-50 focus:border-red-600 focus:ring-red-600 font-medium p-3">
                        </div>
                        <div class="w-full md:w-64">
                            <select wire:model.live="filter_event" class="w-full rounded-xl border-gray-200 bg-gray-50 font-bold p-3 text-sm transition-all">
                                <option value="">Todos los Eventos</option>
                                @foreach($allEvents as $e) <option value="{{ $e->id }}">{{ $e->title }}</option> @endforeach
                            </select>
                        </div>
                    </div>
                @endif

                <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden min-h-[500px]" wire:key="main-content-card">
                    @if($tab === 'registrations')
                        @if($isHub)
                            <!-- HUB VIEW -->
                            <div class="p-8" wire:key="hub-view">
                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                                    <div class="lg:col-span-2 space-y-6">
                                        <div class="flex items-center justify-between">
                                            <h4 class="text-[10px] font-black text-red-600 uppercase tracking-widest flex items-center">
                                                <span class="w-2 h-2 bg-red-600 rounded-full mr-2"></span> 
                                                {{ $showFullList ? 'Historial Completo de Eventos' : 'Eventos Próximos' }}
                                            </h4>
                                            @if(!$showFullList)
                                                <button wire:click="$set('showFullList', true)" class="text-[10px] font-black text-gray-400 uppercase tracking-widest hover:text-red-600 transition-colors">Ver todos los Eventos &rarr;</button>
                                            @else
                                                <button wire:click="$set('showFullList', false)" class="text-[10px] font-black text-gray-400 uppercase tracking-widest hover:text-red-600 transition-colors">&larr; Ver solo Próximos</button>
                                            @endif
                                        </div>
                                        <div class="grid grid-cols-1 gap-4">
                                            @foreach($showFullList ? $allEvents : $events as $e)
                                            <div wire:click="setFilterEvent('{{ $e->id }}')" class="bg-gray-50 p-6 rounded-3xl border border-transparent hover:border-red-500 cursor-pointer flex items-center justify-between group transition-all shadow-sm hover:shadow-xl hover:shadow-red-500/5" wire:key="hub-event-{{ $e->id }}">
                                                <div>
                                                    <h5 class="text-lg font-black text-gray-900 uppercase group-hover:text-red-600 transition-colors">{{ $e->title }}</h5>
                                                    <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest">{{ \Carbon\Carbon::parse($e->date)->translatedFormat('d M, Y') }}</p>
                                                </div>
                                                <div class="bg-white p-3 rounded-2xl group-hover:bg-red-600 group-hover:text-white transition-all shadow-inner">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M9 5l7 7-7 7"></path></svg>
                                                </div>
                                            </div>
                                            @endforeach
                                        </div>
                                    </div>
                                    <div class="space-y-6">
                                        <h4 class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Acciones Rápidas</h4>
                                        <button wire:click="setTab('checkin')" class="w-full bg-green-600 text-white p-8 rounded-[40px] text-left shadow-lg hover:bg-green-700 transition-all group overflow-hidden relative">
                                            <span class="block text-xl font-black uppercase relative z-10">Acreditación</span>
                                            <span class="text-[10px] font-bold opacity-70 uppercase tracking-widest relative z-10">Entrega de kits</span>
                                        </button>
                                        <button wire:click="setTab('athletes')" class="w-full bg-white border border-gray-200 p-8 rounded-[40px] text-left hover:border-blue-500 transition-all group relative overflow-hidden">
                                            <span class="block text-xl font-black text-gray-900 uppercase relative z-10">Base de Datos</span>
                                            <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest relative z-10">Gestión de Atletas</span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @else
                            <!-- REGISTRATIONS TABLE (OPTIMIZADA) -->
                            <div class="overflow-x-auto" wire:key="registrations-table">
                                <table class="w-full text-left">
                                    <thead class="bg-gray-50 border-b border-gray-200">
                                        <tr>
                                            <th class="p-4 text-xs font-bold text-gray-500 uppercase">Atleta / Dorsal</th>
                                            <th class="p-4 text-xs font-bold text-gray-500 uppercase text-center">Misión de Inscripción</th>
                                            <th class="p-4 text-xs font-bold text-gray-500 uppercase text-right">Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-100 bg-white">
                                        @forelse($registrations as $reg)
                                            <tr class="hover:bg-red-50/30 transition-colors group" wire:key="reg-row-{{ $reg->id }}">
                                                <td class="p-4">
                                                    <div class="flex items-center">
                                                        <div class="w-10 h-10 rounded-full bg-gray-100 flex items-center justify-center text-gray-400 font-black mr-3 uppercase text-xs">
                                                            {{ substr($reg->athlete?->name, 0, 1) }}{{ substr($reg->athlete?->last_name, 0, 1) }}
                                                        </div>
                                                        <div>
                                                            <p class="text-sm font-black text-gray-900 uppercase leading-none mb-1">{{ $reg->athlete?->name }} {{ $reg->athlete?->last_name }}</p>
                                                            <div class="flex items-center gap-2">
                                                                <span class="text-[10px] text-gray-400 font-bold uppercase tracking-widest">CI: {{ $reg->athlete?->identity_document }}</span>
                                                                @if($reg->bib_number)
                                                                    <span class="w-1 h-1 bg-gray-300 rounded-full"></span>
                                                                    <span class="text-[10px] text-red-600 font-black uppercase tracking-widest">Dorsal: #{{ $reg->bib_number }}</span>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="p-4">
                                                    <div class="flex items-center justify-center gap-2">
                                                        <!-- Paso 1: Pre-Inscripto -->
                                                        <div class="flex flex-col items-center">
                                                            <div class="w-6 h-6 rounded-full bg-green-500 text-white flex items-center justify-center text-[10px] shadow-sm">
                                                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg>
                                                            </div>
                                                            <span class="text-[8px] font-black uppercase text-green-600 mt-1">Pre-Inscripto</span>
                                                        </div>
                                                        <div class="w-4 h-[1px] bg-gray-200 -mt-3"></div>
                                                        <!-- Paso 2: Inscripto -->
                                                        @php $isPaid = $reg->status === 'paid' || $reg->status === 'inscribed' || $reg->status === 'participant'; @endphp
                                                        <div class="flex flex-col items-center">
                                                            <div class="w-6 h-6 rounded-full flex items-center justify-center text-[10px] transition-all {{ $isPaid ? 'bg-green-500 text-white shadow-sm' : 'bg-gray-100 text-gray-300' }}">
                                                                @if($isPaid) <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg> @else 2 @endif
                                                            </div>
                                                            <span class="text-[8px] font-black uppercase mt-1 {{ $isPaid ? 'text-green-600' : 'text-gray-300' }}">Inscripto</span>
                                                        </div>
                                                        <div class="w-4 h-[1px] bg-gray-200 -mt-3"></div>
                                                        <!-- Paso 3: Acreditado -->
                                                        @php $isAccredited = $reg->status === 'participant'; @endphp
                                                        <div class="flex flex-col items-center">
                                                            <div class="w-6 h-6 rounded-full flex items-center justify-center text-[10px] transition-all {{ $isAccredited ? 'bg-blue-500 text-white shadow-sm' : 'bg-gray-100 text-gray-300' }}">
                                                                @if($isAccredited) <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg> @else 3 @endif
                                                            </div>
                                                            <span class="text-[8px] font-black uppercase mt-1 {{ $isAccredited ? 'text-blue-600' : 'text-gray-300' }}">Acreditado</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="p-4 text-right">
                                                    <div class="flex justify-end gap-1">
                                                        @if($reg->status === 'pre_inscribed')
                                                            <button onclick="confirm('¿Estás seguro de eliminar esta inscripción por completo?') || event.stopImmediatePropagation()" wire:click="deleteRegistration({{ $reg->id }})" class="p-2 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-all" title="Eliminar Inscripción">
                                                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                            </button>
                                                        @endif
                                                        <button wire:click="selectRegistration({{ $reg->id }})" class="p-2 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-all" title="Gestionar Inscripción">
                                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                                                        </button>
                                                        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $reg->athlete?->phone) }}" target="_blank" class="p-2 text-gray-400 hover:text-green-600 hover:bg-green-50 rounded-lg transition-all"><svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/></svg></a>
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr><td colspan="3" class="p-20 text-center font-bold text-gray-400 uppercase tracking-widest">Sin resultados</td></tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    @endif

                    <!-- Pestaña Atletas Maestro -->
                    @if($tab === 'athletes')
                        <div class="overflow-x-auto" wire:key="athletes-master-table">
                            <table class="w-full text-left">
                                <thead class="bg-gray-50 border-b border-gray-200">
                                    <tr>
                                        <th class="p-4 text-xs font-bold text-gray-500 uppercase">Atleta</th>
                                        <th class="p-4 text-xs font-bold text-gray-500 uppercase text-right">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100 bg-white">
                                    @forelse($athletesMaster as $ath)
                                        <tr class="hover:bg-gray-50 transition-colors" wire:key="ath-row-{{ $ath->id }}">
                                            <td class="p-4">
                                                <p class="text-sm font-black text-gray-900 uppercase">{{ $ath->name }} {{ $ath->last_name }}</p>
                                                <p class="text-[10px] text-gray-400 font-mono tracking-tighter">CI: {{ $ath->identity_document }}</p>
                                            </td>
                                            <td class="p-4 text-right">
                                                <div class="flex justify-end gap-1">
                                                    <button wire:click="viewAthlete({{ $ath->id }})" class="p-2 text-gray-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-all" title="Ver Perfil"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg></button>
                                                    <button wire:click="editAthlete({{ $ath->id }})" class="p-2 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-all" title="Editar"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg></button>
                                                    <button onclick="confirm('¿Eliminar atleta?') || event.stopImmediatePropagation()" wire:click="deleteAthlete({{ $ath->id }})" class="p-2 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-all" title="Eliminar">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="2" class="p-20 text-center text-gray-400 font-bold uppercase tracking-widest">Sin resultados</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    @endif

                    @if($tab === 'checkin')
                        <!-- Vista de Acreditación (Checkin) -->
                        <div class="overflow-x-auto" wire:key="checkin-list">
                            <table class="w-full text-left">
                                <thead class="bg-green-50 border-b border-green-100">
                                    <tr>
                                        <th class="p-6 text-[10px] font-black text-green-800 uppercase tracking-widest">Dorsal / Atleta</th>
                                        <th class="p-6 text-[10px] font-black text-green-800 uppercase tracking-widest text-center">Estado de Pago</th>
                                        <th class="p-6 text-[10px] font-black text-green-800 uppercase tracking-widest text-right">Acreditación</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100 bg-white">
                                    @forelse($checkinList as $item)
                                        <tr class="hover:bg-green-50/30 transition-all" wire:key="checkin-row-{{ $item->id }}">
                                            <td class="p-6">
                                                <div class="flex items-center gap-4">
                                                    <div class="w-16 h-16 bg-gray-900 text-white rounded-2xl flex items-center justify-center text-2xl font-black shadow-inner">
                                                        {{ $item->bib_number ?: '??' }}
                                                    </div>
                                                    <div>
                                                        <p class="text-lg font-black text-gray-900 uppercase leading-none mb-1">{{ $item->athlete?->name }} {{ $item->athlete?->last_name }}</p>
                                                        <div class="flex items-center gap-2">
                                                            <span class="text-[10px] font-bold text-red-600 uppercase tracking-widest">{{ $item->category?->name }}</span>
                                                            <span class="w-1 h-1 bg-gray-300 rounded-full"></span>
                                                            <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">CI: {{ $item->athlete?->identity_document }}</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="p-6 text-center">
                                                @if($item->payment_status === 'paid' || $item->status === 'inscribed' || $item->status === 'participant')
                                                    <span class="px-3 py-1 bg-green-100 text-green-700 text-[9px] font-black uppercase tracking-widest rounded-full">Pago Confirmado</span>
                                                @else
                                                    <button wire:click="selectRegistration({{ $item->id }})" class="px-3 py-1 bg-red-100 text-red-700 text-[9px] font-black uppercase tracking-widest rounded-full animate-pulse border border-red-200 hover:bg-red-200 transition-all">Pendiente de Pago</button>
                                                @endif
                                            </td>
                                            <td class="p-6 text-right">
                                                @if($item->kit_delivered_at)
                                                    <div class="flex flex-col items-end">
                                                        <span class="px-4 py-2 bg-gray-100 text-gray-400 rounded-xl text-[9px] font-black uppercase tracking-widest flex items-center">
                                                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg>
                                                            Entregado
                                                        </span>
                                                        <span class="text-[8px] text-gray-300 font-bold mt-1">{{ \Carbon\Carbon::parse($item->kit_delivered_at)->format('H:i') }}hs</span>
                                                    </div>
                                                @else
                                                    @if($item->payment_status === 'paid' || $item->status === 'inscribed')
                                                        <button wire:click="selectRegistration({{ $item->id }})" class="px-8 py-4 bg-green-600 text-white text-[11px] font-black rounded-2xl hover:bg-green-700 transition-all uppercase tracking-widest shadow-xl shadow-green-100 flex items-center gap-2">
                                                            Confirmar Entrega
                                                        </button>
                                                    @else
                                                        <button wire:click="selectRegistration({{ $item->id }})" class="px-8 py-4 bg-gray-100 text-gray-400 text-[11px] font-black rounded-2xl cursor-not-allowed uppercase tracking-widest">Saldar Cuenta</button>
                                                    @endif
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3" class="p-24 text-center">
                                                <div class="max-w-xs mx-auto space-y-4">
                                                    @if($search)
                                                        <div class="p-6 bg-red-50 rounded-3xl border border-red-100">
                                                            <p class="text-sm font-black text-red-600 uppercase tracking-tight mb-2">Atleta No Encontrado</p>
                                                            <p class="text-[10px] text-red-400 font-bold uppercase leading-relaxed">No hay ninguna inscripción para "{{ $search }}" en este evento.</p>
                                                        </div>
                                                        <button wire:click="openRegistration" class="w-full py-4 bg-gray-900 text-white font-black rounded-2xl uppercase tracking-widest text-xs shadow-xl hover:bg-red-600 transition-all">Registrar Atleta Ahora</button>
                                                    @else
                                                        <p class="text-gray-300 font-black uppercase tracking-widest text-sm">Selecciona un evento para acreditar</p>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        @endif

        <!-- VISTA: NUEVA INSCRIPCIÓN MANUAL -->
        @if($view === 'registration')
            <div class="bg-white rounded-[40px] shadow-2xl border border-gray-100 overflow-hidden animate-fadeIn" wire:key="registration-view">
                <div class="bg-gray-900 p-8 flex justify-between items-center text-white">
                    <div>
                        <h3 class="text-2xl font-black uppercase tracking-tighter">Nueva Inscripción Manual</h3>
                        <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest mt-1">Registrar atleta en un evento</p>
                    </div>
                    <button wire:click="setTab('registrations')" class="text-gray-400 hover:text-white transition-colors"><svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg></button>
                </div>

                <div class="p-10 space-y-10 bg-white">
                    <!-- Paso 1: Atleta -->
                    <div class="space-y-6">
                        <h4 class="text-[10px] font-black uppercase tracking-[0.2em] text-red-600 flex items-center">
                            <span class="w-2 h-2 bg-red-600 rounded-full mr-3"></span> Datos del Atleta
                        </h4>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div class="space-y-1">
                                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest">CI / Documento</label>
                                <input type="text" wire:model.blur="identity_document" class="w-full rounded-2xl border-gray-100 bg-gray-50 font-bold p-4 focus:ring-red-600 focus:border-red-600" placeholder="Ej: 1234567">
                            </div>
                            <div class="space-y-1">
                                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Nombres</label>
                                <input type="text" wire:model="name" class="w-full rounded-2xl border-gray-100 bg-gray-50 font-bold p-4">
                            </div>
                            <div class="space-y-1">
                                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Apellidos</label>
                                <input type="text" wire:model="last_name" class="w-full rounded-2xl border-gray-100 bg-gray-50 font-bold p-4">
                            </div>
                            <div class="space-y-1">
                                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Género</label>
                                <select wire:model="gender" class="w-full rounded-2xl border-gray-100 bg-gray-50 font-bold p-4">
                                    <option value="">Seleccionar...</option>
                                    <option value="M">Masculino</option>
                                    <option value="F">Femenino</option>
                                </select>
                            </div>
                            <div class="space-y-1">
                                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Fecha Nacimiento</label>
                                <input type="date" wire:model="date_of_birth" class="w-full rounded-2xl border-gray-100 bg-gray-50 font-bold p-4">
                            </div>
                            <div class="space-y-1">
                                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Teléfono</label>
                                <input type="text" wire:model="phone" class="w-full rounded-2xl border-gray-100 bg-gray-50 font-bold p-4">
                            </div>
                            <div class="space-y-1">
                                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Email</label>
                                <input type="email" wire:model="email" class="w-full rounded-2xl border-gray-100 bg-gray-50 font-bold p-4">
                            </div>
                            <div class="space-y-1">
                                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Ciudad</label>
                                <input type="text" wire:model="city" class="w-full rounded-2xl border-gray-100 bg-gray-50 font-bold p-4">
                            </div>
                            <div class="space-y-1">
                                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Club</label>
                                <select wire:model="club_id" class="w-full rounded-2xl border-gray-100 bg-gray-50 font-bold p-4">
                                    <option value="">Ninguno / Particular</option>
                                    @foreach($clubsList as $club)
                                        <option value="{{ $club->id }}">{{ $club->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Paso 2: Evento -->
                    <div class="space-y-6 pt-10 border-t border-gray-50">
                        <h4 class="text-[10px] font-black uppercase tracking-[0.2em] text-red-600 flex items-center">
                            <span class="w-2 h-2 bg-red-600 rounded-full mr-3"></span> Detalles de la Carrera
                        </h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                            <div class="space-y-1">
                                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Evento</label>
                                <select wire:model.live="selected_event_id" class="w-full rounded-2xl border-gray-100 bg-gray-50 font-bold p-4">
                                    <option value="">Seleccionar Evento...</option>
                                    @foreach($registrationEvents as $event)
                                        <option value="{{ $event->id }}">{{ $event->title }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="space-y-1">
                                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Distancia</label>
                                <select wire:model.live="selected_distance_id" class="w-full rounded-2xl border-gray-100 bg-gray-50 font-bold p-4" {{ !$selected_event_id ? 'disabled' : '' }}>
                                    <option value="">Seleccionar Distancia...</option>
                                    @foreach($eventDistances as $dist)
                                        <option value="{{ $dist->id }}">{{ $dist->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="space-y-1">
                                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Categoría</label>
                                <select wire:model="selected_category_id" class="w-full rounded-2xl border-gray-100 bg-gray-50 font-bold p-4" {{ !$selected_distance_id ? 'disabled' : '' }}>
                                    <option value="">Seleccionar Categoría...</option>
                                    @foreach($eventCategories as $cat)
                                        <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="space-y-1">
                                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Talle de Remera</label>
                                <select wire:model="tshirt_size" class="w-full rounded-2xl border-gray-100 bg-gray-50 font-bold p-4">
                                    <option value="">Seleccionar Talle...</option>
                                    <option value="XS">XS</option><option value="S">S</option>
                                    <option value="M">M</option><option value="L">L</option>
                                    <option value="XL">XL</option><option value="XXL">XXL</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="pt-10 flex gap-4">
                        <button wire:click="setTab('registrations')" class="flex-1 py-5 text-gray-500 font-bold uppercase text-xs tracking-widest">Cancelar</button>
                        <button wire:click="saveRegistration" class="flex-[3] py-5 bg-red-600 text-white font-black rounded-[24px] uppercase tracking-widest text-sm shadow-2xl hover:bg-red-700 transition-all transform hover:-translate-y-1 active:translate-y-0">
                            Confirmar Inscripción
                        </button>
                    </div>
                </div>
            </div>
        @endif

        <!-- VISTA DETALLE: VER INSCRIPCIÓN -->
        @if($view === 'detail')
            @php $reg = \App\Models\Registration::find($this->selectedRegistrationId); @endphp
            <div class="bg-white rounded-[40px] shadow-2xl border border-gray-100 overflow-hidden animate-fadeIn" wire:key="mission-control-view">
                <div class="bg-gray-900 p-8 flex flex-col md:flex-row justify-between items-start md:items-center text-white relative">
                    <div class="relative z-10 space-y-1">
                        <div class="flex items-center gap-3">
                            <span class="px-3 py-0.5 bg-red-600 text-[8px] font-black uppercase tracking-widest rounded-full">Ficha de Inscripción</span>
                            <span class="text-gray-500 font-black text-[10px] uppercase tracking-widest">ID #{{ $reg?->id }}</span>
                        </div>
                        <h3 class="text-3xl font-black uppercase tracking-tighter">{{ $reg?->athlete?->name }} {{ $reg?->athlete?->last_name }}</h3>
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-widest flex items-center">
                            {{ $reg?->event?->title }} • {{ $reg?->category?->name }}
                        </p>
                    </div>
                    <div class="relative z-10 flex flex-col items-end gap-2 mt-6 md:mt-0">
                        <p class="text-[9px] font-black text-gray-500 uppercase tracking-widest mb-1">Dorsal</p>
                        <p class="text-5xl font-black text-white tracking-tighter leading-none">#{{ $reg?->bib_number ?: '---' }}</p>
                    </div>
                </div>

                <div class="p-10 grid grid-cols-1 lg:grid-cols-3 gap-8 bg-white">
                    <div class="lg:col-span-2 space-y-6">
                        <div class="bg-gray-50 rounded-[32px] p-8 border border-gray-100">
                            <h4 class="text-[10px] font-black uppercase tracking-[0.2em] text-gray-900 flex items-center mb-6">
                                <span class="w-2 h-2 bg-red-600 rounded-full mr-3"></span> Validación de Pago y Dorsal
                            </h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="space-y-4">
                                    <div class="space-y-1">
                                        <label class="text-[9px] font-black text-gray-400 uppercase tracking-widest ml-1">Monto</label>
                                        <input type="number" wire:model="payment_amount" class="w-full bg-white rounded-2xl border-gray-100 font-black p-3 text-xl shadow-inner">
                                    </div>
                                    <div class="space-y-1">
                                        <label class="text-[9px] font-black text-gray-400 uppercase tracking-widest ml-1">Dorsal Oficial</label>
                                        <input type="text" wire:model="bib_number" class="w-full bg-white rounded-2xl border-gray-100 font-black p-3 text-xl shadow-inner text-red-600">
                                    </div>
                                </div>
                                <div class="space-y-4">
                                    <div class="space-y-1">
                                        <label class="text-[9px] font-black text-gray-400 uppercase tracking-widest ml-1">Método</label>
                                        <select wire:model="payment_method" class="w-full bg-white rounded-2xl border-gray-100 font-bold p-3">
                                            <option value="Efectivo">Efectivo</option>
                                            <option value="Transferencia">Transferencia</option>
                                            <option value="Tarjeta">Tarjeta</option>
                                        </select>
                                    </div>
                                    <div class="space-y-1">
                                        <label class="text-[9px] font-black text-gray-400 uppercase tracking-widest ml-1">Referencia</label>
                                        <input type="text" wire:model="payment_reference" class="w-full bg-white rounded-2xl border-gray-100 font-bold p-3">
                                    </div>
                                </div>
                            </div>
                            <button wire:click="savePayment" class="w-full mt-6 py-4 bg-gray-900 text-white font-black rounded-2xl uppercase tracking-widest text-xs shadow-xl hover:bg-red-600 transition-all flex items-center justify-center">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                                Guardar y Validar Pago
                            </button>
                        </div>

                        <!-- EDICIÓN DE DETALLES DE INSCRIPCIÓN -->
                        <div class="bg-gray-50 rounded-[32px] p-8 border border-gray-100 mt-6">
                            <h4 class="text-[10px] font-black uppercase tracking-[0.2em] text-gray-900 flex items-center mb-6">
                                <span class="w-2 h-2 bg-blue-600 rounded-full mr-3"></span> Detalles de Competencia
                            </h4>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div class="space-y-1">
                                    <label class="text-[9px] font-black text-gray-400 uppercase tracking-widest">Distancia</label>
                                    <select wire:model.live="selected_distance_id" class="w-full bg-white rounded-xl border-gray-100 font-bold p-2 text-xs">
                                        @foreach($eventDistances as $dist)
                                            <option value="{{ $dist->id }}">{{ $dist->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="space-y-1">
                                    <label class="text-[9px] font-black text-gray-400 uppercase tracking-widest">Categoría</label>
                                    <select wire:model="selected_category_id" class="w-full bg-white rounded-xl border-gray-100 font-bold p-2 text-xs">
                                        @foreach($eventCategories as $cat)
                                            <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="space-y-1">
                                    <label class="text-[9px] font-black text-gray-400 uppercase tracking-widest">Talle Remera</label>
                                    <select wire:model="tshirt_size" class="w-full bg-white rounded-xl border-gray-100 font-bold p-2 text-xs">
                                        <option value="">Seleccionar...</option>
                                        <option value="XS">XS</option><option value="S">S</option>
                                        <option value="M">M</option><option value="L">L</option>
                                        <option value="XL">XL</option><option value="XXL">XXL</option>
                                    </select>
                                </div>
                            </div>
                            <button wire:click="updateRegistrationDetails" class="w-full mt-6 py-3 bg-white border border-gray-200 text-gray-900 font-black rounded-xl uppercase tracking-widest text-[9px] shadow-sm hover:bg-blue-50 hover:border-blue-200 transition-all">
                                Actualizar Datos de Carrera
                            </button>
                        </div>
                    </div>

                    <div class="space-y-6">
                        <div class="bg-gray-900 rounded-[32px] p-8 text-white h-full flex flex-col justify-center text-center">
                            <h4 class="text-[10px] font-black uppercase tracking-[0.2em] mb-4 text-green-500">Logística</h4>
                            @if($reg?->kit_delivered_at)
                                <div class="p-4 bg-white/5 rounded-2xl border border-white/10 mb-4">
                                    <p class="text-[9px] font-black text-green-400 uppercase mb-1">Acreditado el</p>
                                    <p class="text-sm font-black">{{ \Carbon\Carbon::parse($reg->kit_delivered_at)->format('d/m, H:i') }}hs</p>
                                </div>
                                <button onclick="confirm('¿Revertir acreditación?') || event.stopImmediatePropagation()" wire:click="revertToPreInscribed({{ $reg->id }})" class="text-[9px] font-black text-gray-500 uppercase hover:text-red-500 transition-colors">Volver a Pre-Inscripto</button>
                            @else
                                <p class="text-xs text-gray-400 mb-6 font-medium">Confirma la entrega del kit físico al atleta.</p>
                                <button wire:click="confirmKitDelivery({{ $reg?->id }})" class="w-full py-6 bg-green-600 text-white font-black rounded-3xl text-lg uppercase tracking-tighter shadow-2xl hover:bg-green-500 transition-all">ACREDITAR</button>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="p-6 bg-gray-50 border-t border-gray-100 flex justify-between items-center">
                    <div class="flex gap-2">
                        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $reg?->athlete?->phone) }}" target="_blank" class="px-5 py-2.5 bg-green-50 text-green-700 text-[10px] font-black uppercase rounded-xl border border-green-200">WhatsApp</a>
                        <button wire:click="editAthlete({{ $reg?->athlete_id }})" class="px-5 py-2.5 bg-white text-gray-700 text-[10px] font-black uppercase rounded-xl border border-gray-200">Editar Atleta</button>
                    </div>
                    <div class="flex gap-4 items-center">
                        <button onclick="confirm('¿Anular inscripción?') || event.stopImmediatePropagation()" wire:click="revertToPreInscribed({{ $reg?->id }})" class="text-[10px] font-black text-red-600 uppercase hover:text-red-800 transition-colors">Anular y Revertir</button>
                        <button wire:click="setTab('registrations')" class="px-8 py-3 bg-gray-900 text-white text-[10px] font-black uppercase tracking-widest rounded-xl shadow-lg hover:bg-red-600 transition-all">Cerrar y Volver</button>
                    </div>
                </div>
            </div>
        @endif

        <!-- VISTA EDICIÓN DE ATLETA -->
        @if($view === 'edit_athlete')
            <div class="bg-white rounded-[40px] shadow-2xl border border-gray-100 overflow-hidden animate-fadeIn" wire:key="edit-athlete-view">
                <div class="bg-gray-900 p-8 flex justify-between items-center text-white">
                    <h3 class="text-2xl font-black uppercase tracking-tighter">Editar Perfil del Atleta</h3>
                    <button wire:click="setTab('{{ $tab }}')" class="text-gray-400 hover:text-white transition-colors"><svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg></button>
                </div>
                <div class="p-10 space-y-8 bg-white">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="space-y-1">
                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Documento (CI)</label>
                            <input type="text" wire:model="identity_document" class="w-full rounded-2xl border-gray-100 bg-gray-50 font-bold p-4">
                        </div>
                        <div class="space-y-1">
                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Nombres</label>
                            <input type="text" wire:model="name" class="w-full rounded-2xl border-gray-100 bg-gray-50 font-bold p-4">
                        </div>
                        <div class="space-y-1">
                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Apellidos</label>
                            <input type="text" wire:model="last_name" class="w-full rounded-2xl border-gray-100 bg-gray-50 font-bold p-4">
                        </div>
                        <div class="space-y-1">
                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Género</label>
                            <select wire:model="gender" class="w-full rounded-2xl border-gray-100 bg-gray-50 font-bold p-4">
                                <option value="">Seleccionar...</option>
                                <option value="M">Masculino</option>
                                <option value="F">Femenino</option>
                            </select>
                        </div>
                        <div class="space-y-1">
                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Fecha de Nacimiento</label>
                            <input type="date" wire:model="date_of_birth" class="w-full rounded-2xl border-gray-100 bg-gray-50 font-bold p-4">
                        </div>
                        <div class="space-y-1">
                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Teléfono</label>
                            <input type="text" wire:model="phone" class="w-full rounded-2xl border-gray-100 bg-gray-50 font-bold p-4">
                        </div>
                        <div class="space-y-1">
                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Email</label>
                            <input type="email" wire:model="email" class="w-full rounded-2xl border-gray-100 bg-gray-50 font-bold p-4">
                        </div>
                        <div class="space-y-1">
                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Tipo de Sangre</label>
                            <select wire:model="blood_type" class="w-full rounded-2xl border-gray-100 bg-gray-50 font-bold p-4">
                                <option value="">Seleccionar...</option>
                                <option value="A+">A+</option><option value="A-">A-</option>
                                <option value="B+">B+</option><option value="B-">B-</option>
                                <option value="O+">O+</option><option value="O-">O-</option>
                                <option value="AB+">AB+</option><option value="AB-">AB-</option>
                            </select>
                        </div>
                        <div class="space-y-1">
                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Club</label>
                            <select wire:model="club_id" class="w-full rounded-2xl border-gray-100 bg-gray-50 font-bold p-4">
                                <option value="">Ninguno / Particular</option>
                                @foreach($clubsList as $club)
                                    <option value="{{ $club->id }}">{{ $club->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="space-y-1">
                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Número de Carnet</label>
                            <input type="text" wire:model="carnet_number" class="w-full rounded-2xl border-gray-100 bg-gray-50 font-bold p-4">
                        </div>
                        <div class="space-y-1">
                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Ciudad</label>
                            <input type="text" wire:model="city" class="w-full rounded-2xl border-gray-100 bg-gray-50 font-bold p-4">
                        </div>
                        <div class="space-y-1">
                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest">País</label>
                            <input type="text" wire:model="country" class="w-full rounded-2xl border-gray-100 bg-gray-50 font-bold p-4">
                        </div>
                    </div>
                    <div class="pt-6 border-t border-gray-50 flex gap-4">
                        <button wire:click="setTab('{{ $tab }}')" class="flex-1 py-4 text-gray-500 font-bold uppercase text-xs">Cancelar</button>
                        <button wire:click="updateAthlete" class="flex-[3] py-4 bg-red-600 text-white font-black rounded-2xl uppercase tracking-widest text-sm shadow-xl hover:bg-red-700 transition-all">Actualizar Perfil Atleta</button>
                    </div>
                </div>
            </div>
        @endif

        <!-- VISTA: VER PERFIL DE ATLETA (LECTURA) -->
        @if($view === 'view_athlete')
            <div class="bg-white rounded-[40px] shadow-2xl border border-gray-100 overflow-hidden animate-fadeIn" wire:key="view-athlete-view">
                <div class="bg-gray-900 p-10 text-white relative">
                    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6 relative z-10">
                        <div class="flex items-center gap-6">
                            <div class="w-24 h-24 rounded-full bg-red-600 flex items-center justify-center text-4xl font-black shadow-2xl border-4 border-white/10">
                                {{ substr($name, 0, 1) }}{{ substr($last_name, 0, 1) }}
                            </div>
                            <div>
                                <h3 class="text-4xl font-black uppercase tracking-tighter">{{ $name }} {{ $last_name }}</h3>
                                <p class="text-sm font-bold text-gray-400 uppercase tracking-widest mt-1">CI: {{ $identity_document }} • {{ $gender === 'M' ? 'Masculino' : 'Femenino' }}</p>
                            </div>
                        </div>
                        <div class="flex gap-3">
                            <button wire:click="editAthlete({{ $editingAthleteId }})" class="px-6 py-3 bg-white text-gray-900 text-xs font-black rounded-2xl uppercase tracking-widest shadow-lg hover:bg-gray-100 transition-all">Editar Perfil</button>
                            <button wire:click="setTab('{{ $tab }}')" class="p-3 bg-white/10 text-white rounded-2xl hover:bg-white/20 transition-all"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg></button>
                        </div>
                    </div>
                </div>
                <div class="p-10 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 bg-white">
                    <div class="space-y-1">
                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">Contacto</p>
                        <p class="text-lg font-bold text-gray-900">{{ $phone ?: 'No registrado' }}</p>
                        <p class="text-sm text-gray-500">{{ $email ?: 'Sin email' }}</p>
                    </div>
                    <div class="space-y-1">
                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">Logística</p>
                        <p class="text-lg font-bold text-gray-900">{{ $city ?: 'N/A' }}, {{ $country }}</p>
                        <p class="text-sm text-gray-500">Club: {{ \App\Models\Club::find($club_id)?->name ?? 'Ninguno' }}</p>
                    </div>
                    <div class="space-y-1">
                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">Médico / Otros</p>
                        <p class="text-lg font-bold text-red-600">Sangre: {{ $blood_type ?: 'N/A' }}</p>
                        <p class="text-sm text-gray-500">Carnet: {{ $carnet_number ?: '-' }}</p>
                    </div>
                </div>
                <div class="p-6 bg-gray-50 border-t border-gray-100 flex justify-end">
                    <button wire:click="setTab('{{ $tab }}')" class="px-10 py-4 bg-gray-900 text-white text-[10px] font-black uppercase tracking-widest rounded-2xl">Cerrar</button>
                </div>
            </div>
        @endif

        <!-- VISTA: IMPORTAR ATLETAS -->
        @if($view === 'import')
            <div class="bg-white rounded-[40px] shadow-2xl border border-gray-100 overflow-hidden animate-fadeIn" wire:key="import-view">
                <div class="bg-gray-900 p-8 flex justify-between items-center text-white">
                    <div>
                        <h3 class="text-2xl font-black uppercase tracking-tighter">Importar Base de Datos</h3>
                        <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest mt-1">Carga masiva desde CSV</p>
                    </div>
                    <button wire:click="setTab('athletes')" class="text-gray-400 hover:text-white transition-colors"><svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg></button>
                </div>
                <div class="p-12 space-y-8 text-center">
                    <div class="max-w-md mx-auto space-y-6">
                        <div class="p-12 border-4 border-dashed border-gray-100 rounded-[40px] bg-gray-50 flex flex-col items-center justify-center group hover:border-red-500 transition-all">
                            <svg class="w-16 h-16 text-gray-200 group-hover:text-red-500 transition-all mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path></svg>
                            <input type="file" wire:model="importFile" class="absolute opacity-0 w-full h-full cursor-pointer">
                            <p class="text-sm font-bold text-gray-400 uppercase tracking-widest">Suelta el archivo CSV aquí</p>
                        </div>
                        <div class="flex flex-col gap-3">
                            <button wire:click="importAthletes" class="w-full py-5 bg-red-600 text-white font-black rounded-3xl uppercase tracking-widest text-sm shadow-xl hover:bg-red-700 transition-all">Procesar Importación</button>
                            <button wire:click="downloadTemplate" class="text-[10px] font-black text-gray-400 uppercase hover:text-red-600 transition-colors">Descargar Plantilla CSV &rarr;</button>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <!-- VISTA: ASIGNACIÓN DE DORSAL (BIB) -->
        @if($view === 'bib_assignment')
            <div class="bg-white rounded-[40px] shadow-2xl border border-gray-100 overflow-hidden animate-fadeIn max-w-2xl mx-auto" wire:key="bib-view">
                <div class="bg-gray-900 p-8 text-center text-white">
                    <h3 class="text-2xl font-black uppercase tracking-tighter">Asignar Dorsal Oficial</h3>
                    <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest mt-1">Entrega de kit y acreditación</p>
                </div>
                <div class="p-12 space-y-8 text-center">
                    <div class="space-y-4">
                        <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Número de Dorsal</label>
                        <input type="text" wire:model="bib_number" class="w-full text-7xl font-black text-center text-red-600 border-none bg-gray-50 rounded-3xl p-8 focus:ring-0" placeholder="000" autofocus>
                    </div>
                    <div class="flex gap-4">
                        <button wire:click="setTab('checkin')" class="flex-1 py-5 text-gray-400 font-bold uppercase text-xs">Cancelar</button>
                        <button wire:click="saveBibNumber" class="flex-[3] py-5 bg-green-600 text-white font-black rounded-3xl uppercase tracking-widest text-sm shadow-xl hover:bg-green-700 transition-all">Confirmar y Acreditar</button>
                    </div>
                </div>
            </div>
        @endif

        <!-- VISTA: PAGO STANDALONE -->
        @if($view === 'payment_validation')
            <div class="bg-white rounded-[40px] shadow-2xl border border-gray-100 overflow-hidden animate-fadeIn max-w-2xl mx-auto" wire:key="payment-view">
                <div class="bg-gray-900 p-8 text-white">
                    <h3 class="text-2xl font-black uppercase tracking-tighter text-center">Validación de Pago</h3>
                </div>
                <div class="p-10 space-y-6">
                    <div class="grid grid-cols-2 gap-4">
                        <div class="space-y-1">
                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Monto</label>
                            <input type="number" wire:model="payment_amount" class="w-full bg-gray-50 rounded-2xl border-transparent font-black p-4 text-2xl">
                        </div>
                        <div class="space-y-1">
                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Dorsal</label>
                            <input type="text" wire:model="bib_number" class="w-full bg-gray-50 rounded-2xl border-transparent font-black p-4 text-2xl text-red-600">
                        </div>
                    </div>
                    <div class="space-y-1">
                        <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Método</label>
                        <select wire:model="payment_method" class="w-full bg-gray-50 rounded-2xl border-transparent font-bold p-4">
                            <option value="Efectivo">Efectivo</option>
                            <option value="Transferencia">Transferencia</option>
                            <option value="Tarjeta">Tarjeta</option>
                        </select>
                    </div>
                    <button wire:click="savePayment" class="w-full py-6 bg-red-600 text-white font-black rounded-3xl uppercase tracking-widest text-sm shadow-xl hover:bg-red-700 transition-all">Registrar Pago e Inscribir</button>
                    <button wire:click="setTab('registrations')" class="w-full py-4 text-gray-400 font-bold uppercase text-[10px]">Cancelar</button>
                </div>
            </div>
        @endif
    </div>
</div>

<style>
    .animate-fadeIn { animation: fadeIn 0.3s ease-out; }
    @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
</style>
