<div>
    <div class="space-y-6">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h2 class="text-xl font-bold text-gray-900 border-l-4 border-red-600 pl-4 uppercase tracking-tighter">Atletas e Inscripciones</h2>
                <p class="text-[10px] text-gray-400 ml-4 font-bold uppercase tracking-widest mt-0.5">Gestión de participantes</p>
            </div>
            <div class="flex flex-wrap md:flex-nowrap gap-3">
                @if($view === 'list')
                    <button wire:click="openImport" class="px-4 py-2 bg-blue-50 text-blue-700 border border-blue-200 text-xs font-bold rounded-xl hover:bg-blue-100 transition-all shadow-sm flex items-center justify-center">
                        <svg class="w-4 h-4 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                        Importar
                    </button>
                    <button wire:click="exportToCSV" class="px-4 py-2 bg-white border border-gray-200 text-gray-700 text-xs font-bold rounded-xl hover:bg-gray-50 transition-all shadow-sm flex items-center justify-center">
                        <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                        CSV
                    </button>
                    <button wire:click="openRegistration" class="px-4 py-2 bg-gray-900 text-white text-xs font-bold rounded-xl hover:bg-red-600 transition-all shadow-lg flex items-center justify-center">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                        Nueva Inscripción
                    </button>
                @else
                    <button wire:click="cancel" class="px-4 py-2 bg-white border border-gray-200 text-gray-700 text-xs font-bold rounded-xl hover:bg-gray-50 transition-all shadow-sm flex items-center justify-center">
                        <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                        Volver
                    </button>
                @endif
            </div>
        </div>

        @if(session()->has('message'))
            <div class="bg-green-50 border-l-4 border-green-500 p-4 rounded-r-xl shadow-sm animate-fadeIn">
                <p class="text-sm text-green-700 font-bold">{{ session('message') }}</p>
            </div>
        @endif

        @if($view === 'list')
            <!-- Tabs -->
            <div class="flex border-b border-gray-200">
                <button wire:click="$set('tab', 'registrations')" class="px-6 py-3 text-sm font-bold uppercase tracking-widest transition-all {{ $tab === 'registrations' ? 'border-b-2 border-red-600 text-red-600' : 'text-gray-400 hover:text-gray-600' }}">
                    Inscripciones
                </button>
                <button wire:click="$set('tab', 'athletes')" class="px-6 py-3 text-sm font-bold uppercase tracking-widest transition-all {{ $tab === 'athletes' ? 'border-b-2 border-red-600 text-red-600' : 'text-gray-400 hover:text-gray-600' }}">
                    Base de Datos de Atletas
                </button>
            </div>

            <!-- Filtros -->
            <div class="bg-white p-4 rounded-2xl shadow-sm border border-gray-200 flex flex-col md:flex-row gap-4 items-center">
                <div class="flex-1 w-full">
                    <input type="text" wire:model.live="search" placeholder="Buscar por nombre, apellido o CI..." class="w-full rounded-xl border-gray-200 bg-gray-50 focus:border-red-600 focus:ring-red-600 font-medium p-3">
                </div>
                @if($tab === 'registrations')
                <div class="w-full md:w-80">
                    <div x-data="{ open: false }" class="relative w-full">
                        <div class="relative" @click.away="open = false">
                            <div @click="open = true; $nextTick(() => $refs.search.focus())" 
                                 class="w-full rounded-xl border border-gray-200 bg-gray-50 p-3 shadow-sm cursor-pointer flex justify-between items-center text-gray-700 min-h-[48px] transition-all overflow-hidden" 
                                 :class="open ? 'ring-2 ring-red-600 border-red-600' : ''">
                                
                                @php
                                    $selectedEvent = $filter_event ? collect($events)->firstWhere('id', $filter_event) : null;
                                    if (!$selectedEvent && $filter_event) {
                                        $selectedEvent = \App\Models\Event::find($filter_event);
                                    }
                                @endphp
                                
                                <div x-show="!open" class="truncate font-bold w-full text-sm">
                                    {{ $selectedEvent ? $selectedEvent->title : 'Todos los Eventos' }}
                                </div>
                                
                                <input 
                                    x-show="open" 
                                    x-ref="search"
                                    type="text" 
                                    wire:model.live.debounce.300ms="eventSearchTerm" 
                                    class="bg-transparent border-none p-0 focus:ring-0 w-full font-bold text-sm" 
                                    placeholder="Buscar evento..."
                                >
                                <svg class="w-5 h-5 text-gray-400 shrink-0 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                            </div>
                            
                            <div x-cloak x-show="open" 
                                 x-transition
                                 class="absolute z-50 w-full mt-2 bg-white border border-gray-100 rounded-xl shadow-2xl max-h-72 overflow-y-auto">
                                
                                <button type="button" 
                                        wire:click="$set('filter_event', ''); $set('eventSearchTerm', '')" 
                                        @click="open = false" 
                                        class="w-full text-left px-4 py-3 text-xs font-black uppercase tracking-widest text-gray-400 hover:text-gray-900 hover:bg-gray-50 border-b border-gray-100 transition-colors">
                                    Todos los Eventos
                                </button>
                                
                                @foreach($events as $e)
                                    <button type="button"
                                            wire:click="$set('filter_event', '{{ $e->id }}'); $set('eventSearchTerm', '')" 
                                            @click="open = false" 
                                            class="w-full text-left px-4 py-2 text-sm font-bold text-gray-900 hover:bg-red-50 hover:text-red-600 border-b border-gray-50 transition-colors">
                                        <span class="line-clamp-1">{{ $e->title }}</span>
                                        <span class="block text-[10px] text-gray-400 font-medium mt-0.5 uppercase tracking-widest">{{ \Carbon\Carbon::parse($e->date)->translatedFormat('d M, Y') }}</span>
                                    </button>
                                @endforeach
                                
                                @if(count($events) === 0)
                                    <div class="px-4 py-3 text-xs font-bold text-gray-400 text-center uppercase tracking-widest">No hay coincidencias</div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                @endif
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                @if($tab === 'registrations')
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="bg-gray-50 border-b border-gray-200">
                                <th class="p-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Atleta / Contacto</th>
                                <th class="p-4 text-xs font-bold text-gray-500 uppercase tracking-wider text-center">Estado</th>
                                <th class="p-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Evento / Categoría</th>
                                <th class="p-4 text-xs font-bold text-gray-500 uppercase tracking-wider text-right">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 bg-white">
                            @forelse($registrations as $reg)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="p-4">
                                        <div class="flex items-center gap-2">
                                            <p class="text-sm font-bold text-gray-900">{{ $reg->athlete->name }} {{ $reg->athlete->last_name }}</p>
                                            @if($reg->bib_number)
                                                <span class="bg-gray-900 text-white text-[10px] font-black px-2 py-0.5 rounded uppercase tracking-widest shadow-sm">#{{ $reg->bib_number }}</span>
                                            @endif
                                        </div>
                                        <p class="text-[10px] text-gray-500 font-medium uppercase tracking-tighter mt-1">{{ $reg->athlete->identity_document }} • {{ $reg->athlete->phone }}</p>
                                    </td>
                                    <td class="p-4">
                                        <div class="space-y-1.5 w-full">
                                            <!-- Phase Indicator -->
                                            @if($reg->status === 'pre_inscribed')
                                                <span class="inline-flex w-full items-center justify-center bg-amber-50 text-amber-700 text-[9px] font-black px-2 py-1 rounded-lg border border-amber-200 uppercase tracking-widest">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-amber-400 mr-2"></span> PRE-INSCRIPTO
                                                </span>
                                            @elseif($reg->status === 'inscribed')
                                                <span class="inline-flex w-full items-center justify-center bg-blue-50 text-blue-700 text-[9px] font-black px-2 py-1 rounded-lg border border-blue-200 uppercase tracking-widest">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-blue-400 mr-2"></span> INSCRIPTO
                                                </span>
                                            @else
                                                <span class="inline-flex w-full items-center justify-center bg-emerald-50 text-emerald-700 text-[9px] font-black px-2 py-1 rounded-lg border border-emerald-200 uppercase tracking-widest">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 mr-2"></span> ACREDITADO
                                                </span>
                                            @endif

                                            <!-- Payment Status -->
                                            @if($reg->payment_status === 'paid')
                                                <span class="inline-flex w-full items-center justify-center bg-green-50 text-green-700 text-[8px] font-bold px-2 py-1 rounded-lg border border-green-100 uppercase tracking-widest opacity-80">
                                                    PAGO OK
                                                </span>
                                            @else
                                                <span class="inline-flex w-full items-center justify-center bg-red-50 text-red-700 text-[8px] font-bold px-2 py-1 rounded-lg border border-red-100 uppercase tracking-widest opacity-80">
                                                    DEBE PAGAR
                                                </span>
                                            @endif
                                            
                                            <!-- Kit Status -->
                                            @if($reg->kit_delivered_at)
                                                <span class="inline-flex w-full items-center justify-center bg-purple-50 text-purple-700 text-[8px] font-bold px-2 py-1 rounded-lg border border-purple-100 uppercase tracking-widest opacity-80">
                                                    KIT ENTREGADO
                                                </span>
                                            @else
                                                <span class="inline-flex w-full items-center justify-center bg-gray-50 text-gray-400 text-[8px] font-bold px-2 py-1 rounded-lg border border-gray-100 uppercase tracking-widest opacity-80">
                                                    SIN KIT
                                                </span>
                                            @endif
                                        </div>

                                        <div x-data="{ open: false, btnRect: {} }" class="mt-2">
                                            <button @click="btnRect = $el.getBoundingClientRect(); open = !open" class="w-full bg-white border border-gray-200 hover:border-red-300 hover:text-red-600 text-gray-600 text-[9px] font-bold py-1.5 rounded-lg uppercase flex items-center justify-center transition-all group shadow-sm">
                                                Administrar
                                                <svg class="w-3 h-3 ml-1 text-gray-400 group-hover:text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                            </button>

                                            {{-- Fixed-position dropdown via teleport to avoid overflow clipping --}}
                                            <template x-teleport="body">
                                                <div
                                                    x-show="open"
                                                    x-cloak
                                                    @click.away="open = false"
                                                    :style="`position:fixed; top:${btnRect.bottom + 6}px; left:${btnRect.left}px; width:${Math.max(btnRect.width, 160)}px; z-index:9999;`"
                                                    x-transition:enter="transition ease-out duration-150"
                                                    x-transition:enter-start="opacity-0 scale-95"
                                                    x-transition:enter-end="opacity-100 scale-100"
                                                    x-transition:leave="transition ease-in duration-100"
                                                    x-transition:leave-start="opacity-100 scale-100"
                                                    x-transition:leave-end="opacity-0 scale-95"
                                                    class="bg-white rounded-xl shadow-2xl border border-gray-100 py-1 overflow-hidden"
                                                >
                                                    <p class="px-3 pt-2 pb-1 text-[9px] font-black text-gray-300 uppercase tracking-widest">Cambiar estado</p>
                                                    <button wire:click="changeStatus({{ $reg->id }}, 'pre_inscribed')" @click="open = false" class="w-full text-left px-3 py-2 text-[10px] font-bold text-yellow-600 hover:bg-yellow-50 uppercase flex items-center gap-2">
                                                        <span class="w-1.5 h-1.5 rounded-full bg-yellow-400 inline-block"></span> Pre Inscripto
                                                    </button>
                                                    <button wire:click="openPayment({{ $reg->id }})" @click="open = false" class="w-full text-left px-3 py-2 text-[10px] font-bold text-blue-600 hover:bg-blue-50 uppercase flex items-center gap-2">
                                                        <span class="w-1.5 h-1.5 rounded-full bg-blue-400 inline-block"></span> Validar Pago
                                                    </button>
                                                    <button wire:click="changeStatus({{ $reg->id }}, 'participant')" @click="open = false" class="w-full text-left px-3 py-2 text-[10px] font-bold text-green-600 hover:bg-green-50 uppercase flex items-center gap-2">
                                                        <span class="w-1.5 h-1.5 rounded-full bg-green-400 inline-block"></span> Acreditado
                                                    </button>
                                                    @if($reg->status === 'participant')
                                                        <hr class="my-1 border-gray-100">
                                                        <button wire:click="changeBib({{ $reg->id }})" @click="open = false" class="w-full text-left px-3 py-2 text-[10px] font-bold text-purple-600 hover:bg-purple-50 uppercase flex items-center gap-2">
                                                            <span class="w-1.5 h-1.5 rounded-full bg-purple-400 inline-block"></span> Cambiar Dorsal
                                                        </button>
                                                    @endif
                                                </div>
                                            </template>
                                        </div>
                                    </td>
                                    <td class="p-4">
                                        <p class="text-sm font-bold text-gray-900">{{ $reg->event->title ?? 'N/A' }}</p>
                                        <p class="text-xs text-red-600 font-bold uppercase tracking-wide">{{ $reg->category->name ?? 'N/A' }} • {{ $reg->distance->name ?? 'N/A' }}</p>
                                    </td>
                                    <td class="p-4 text-right flex justify-end items-center gap-1">
                                        @if($reg->status === 'participant')
                                            @php
                                                $phone = preg_replace('/[^0-9]/', '', $reg->athlete->phone);
                                                if (strlen($phone) === 9 && $phone[0] === '0') { $phone = '595' . substr($phone, 1); }
                                                elseif (strlen($phone) === 9) { $phone = '595' . $phone; }
                                                $msg = "¡Hola *{$reg->athlete->name}*! 👋 Ya retiraste tu kit para el evento *{$reg->event->title}*. Tu número de dorsal asignado es el *#{$reg->bib_number}*. ¡Muchos éxitos! 🏃‍♂️🏁";
                                                $waUrl = "https://wa.me/{$phone}?text=" . urlencode($msg);
                                            @endphp
                                            <a href="{{ $waUrl }}" target="_blank" class="p-2 text-green-500 hover:text-green-600 transition-colors" title="Enviar WhatsApp">
                                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                                            </a>
                                        @endif
                                        <button onclick="confirm('¿Estás seguro?') || event.stopImmediatePropagation()" wire:click="deleteRegistration({{ $reg->id }})" class="p-2 text-gray-400 hover:text-red-600 transition-colors">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="4" class="p-20 text-center font-bold text-gray-400">No hay registros.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @else
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="bg-gray-50 border-b border-gray-200">
                                <th class="p-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Atleta</th>
                                <th class="p-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Documento</th>
                                <th class="p-4 text-xs font-bold text-gray-500 uppercase tracking-wider text-right">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 bg-white">
                            @forelse($athletesMaster as $ath)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="p-4">
                                        <p class="text-sm font-bold text-gray-900">{{ $ath->name }} {{ $ath->last_name }}</p>
                                        <p class="text-[10px] text-gray-400 uppercase">{{ $ath->city }} • {{ $ath->phone }}</p>
                                    </td>
                                    <td class="p-4">
                                        <span class="bg-gray-100 text-gray-700 px-2 py-1 rounded text-xs font-bold font-mono">{{ $ath->identity_document }}</span>
                                    </td>
                                    <td class="p-4 text-right">
                                        <button wire:click="editAthlete({{ $ath->id }})" class="p-2 text-gray-400 hover:text-red-600 transition-colors">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="3" class="p-20 text-center font-bold text-gray-400">No hay atletas registrados.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                    <div class="p-4 border-t border-gray-100">{{ $athletesMaster->links() }}</div>
                </div>
                @endif
            </div>
        @endif

        @if($view === 'registration')
            <div class="bg-white rounded-3xl shadow-xl border border-gray-200 overflow-hidden animate-fadeIn">
                <div class="bg-gray-900 p-8 flex justify-between items-center text-white">
                    <h3 class="text-2xl font-black uppercase tracking-tighter">Inscripción Manual</h3>
                    <button wire:click="cancel" class="text-gray-400 hover:text-white transition-colors">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>
                <div class="p-8 space-y-8">
                    <div class="space-y-4">
                        <h4 class="text-[10px] font-bold text-red-600 uppercase tracking-widest border-b border-red-100 pb-2">1. Atleta</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div class="md:col-span-2">
                                <label class="block text-xs font-black text-gray-400 uppercase mb-2">Cédula</label>
                                <input type="text" wire:model.live.debounce.500ms="identity_document" class="w-full rounded-xl border-gray-200 font-bold bg-gray-50 focus:border-red-600 focus:ring-red-600 p-3 shadow-inner">
                            </div>
                            <input type="text" wire:model="name" placeholder="Nombres" class="w-full rounded-xl border-gray-200 font-bold p-3">
                            <input type="text" wire:model="last_name" placeholder="Apellidos" class="w-full rounded-xl border-gray-200 font-bold p-3">
                            <select wire:model="gender" class="w-full rounded-xl border-gray-200 font-bold p-3">
                                <option value="">Género</option>
                                <option value="M">Masc</option>
                                <option value="F">Fem</option>
                            </select>
                            <input type="date" wire:model="date_of_birth" class="w-full rounded-xl border-gray-200 font-bold p-3">
                        </div>
                    </div>
                    <div class="space-y-4">
                        <h4 class="text-[10px] font-bold text-red-600 uppercase tracking-widest border-b border-red-100 pb-2">2. Evento</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div class="w-full md:col-span-2">
                                <div x-data="{ open: false }" class="relative w-full">
                                    <div class="relative" @click.away="open = false">
                                        <div @click="open = true; $nextTick(() => $refs.searchReg.focus())" 
                                             class="w-full rounded-xl border border-gray-200 bg-white p-3 shadow-inner cursor-pointer flex justify-between items-center text-gray-700 min-h-[48px] transition-all overflow-hidden" 
                                             :class="open ? 'ring-2 ring-red-600 border-red-600' : ''">
                                            
                                            @php
                                                $selectedRegEvent = $selected_event_id ? collect($registrationEvents)->firstWhere('id', $selected_event_id) : null;
                                                if (!$selectedRegEvent && $selected_event_id) {
                                                    $selectedRegEvent = \App\Models\Event::find($selected_event_id);
                                                }
                                            @endphp
                                            
                                            <div x-show="!open" class="truncate font-bold w-full text-sm">
                                                {{ $selectedRegEvent ? $selectedRegEvent->title : 'Seleccionar Evento...' }}
                                            </div>
                                            
                                            <input 
                                                x-show="open" 
                                                x-ref="searchReg"
                                                type="text" 
                                                wire:model.live.debounce.300ms="registrationEventSearchTerm" 
                                                class="bg-transparent border-none p-0 focus:ring-0 w-full font-bold text-sm" 
                                                placeholder="Buscar evento..."
                                            >
                                            <svg class="w-5 h-5 text-gray-400 shrink-0 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                        </div>
                                        
                                        <div x-cloak x-show="open" 
                                             x-transition
                                             class="absolute z-50 w-full mt-2 bg-white border border-gray-100 rounded-xl shadow-2xl max-h-72 overflow-y-auto">
                                            
                                            <button type="button" 
                                                    wire:click="$set('selected_event_id', ''); $set('registrationEventSearchTerm', '')" 
                                                    @click="open = false" 
                                                    class="w-full text-left px-4 py-3 text-xs font-black uppercase tracking-widest text-gray-400 hover:text-gray-900 hover:bg-gray-50 border-b border-gray-100 transition-colors">
                                                × Limpiar Selección
                                            </button>
                                            
                                            @foreach($registrationEvents as $e)
                                                <button type="button"
                                                        wire:click="$set('selected_event_id', '{{ $e->id }}'); $set('registrationEventSearchTerm', '')" 
                                                        @click="open = false" 
                                                        class="w-full text-left px-4 py-2 text-sm font-bold text-gray-900 hover:bg-red-50 hover:text-red-600 border-b border-gray-50 transition-colors">
                                                    <span class="line-clamp-1">{{ $e->title }}</span>
                                                    <span class="block text-[10px] text-gray-400 font-medium mt-0.5 uppercase tracking-widest">{{ \Carbon\Carbon::parse($e->date)->translatedFormat('d M, Y') }}</span>
                                                </button>
                                            @endforeach
                                            
                                            @if(count($registrationEvents) === 0)
                                                <div class="px-4 py-3 text-xs font-bold text-gray-400 text-center uppercase tracking-widest">No hay coincidencias</div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <select wire:model.live="selected_distance_id" class="w-full rounded-xl border-gray-200 font-bold p-3">
                                <option value="">Distancia</option>
                                @foreach($eventDistances as $d) <option value="{{ $d->id }}">{{ $d->name }}</option> @endforeach
                            </select>
                            <select wire:model="selected_category_id" class="w-full rounded-xl border-gray-200 font-bold p-3">
                                <option value="">Categoría</option>
                                @foreach($eventCategories as $c) <option value="{{ $c->id }}">{{ $c->name }}</option> @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="p-6 bg-gray-50 border-t border-gray-100 flex gap-4">
                    <button wire:click="cancel" class="flex-1 py-3 text-gray-500 font-bold uppercase tracking-widest text-[10px]">Cancelar</button>
                    <button wire:click="saveRegistration" class="flex-[2] py-3 bg-gradient-to-b from-red-500 to-red-700 hover:from-red-600 hover:to-red-800 text-white font-black rounded-xl transition-all shadow-lg shadow-red-500/30 uppercase tracking-widest text-xs">Guardar Inscripción</button>
                </div>
            </div>
        @endif

        @if($view === 'edit_athlete')
            <div class="bg-white rounded-3xl shadow-xl border border-gray-200 overflow-hidden animate-fadeIn">
                <div class="bg-gray-900 p-8 flex justify-between items-center text-white">
                    <h3 class="text-2xl font-black uppercase tracking-tighter">Editar Atleta</h3>
                    <button wire:click="cancel" class="text-gray-400 hover:text-white transition-colors"><svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg></button>
                </div>
                <div class="p-8 grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="md:col-span-2"><input type="text" wire:model="identity_document" class="w-full rounded-xl border-gray-200 font-bold bg-gray-50 p-3 shadow-inner"></div>
                    <input type="text" wire:model="name" placeholder="Nombres" class="w-full rounded-xl border-gray-200 font-bold p-3">
                    <input type="text" wire:model="last_name" placeholder="Apellidos" class="w-full rounded-xl border-gray-200 font-bold p-3">
                    <input type="text" wire:model="phone" placeholder="Teléfono" class="w-full rounded-xl border-gray-200 font-bold p-3">
                    <input type="email" wire:model="email" placeholder="Email" class="w-full rounded-xl border-gray-200 font-bold p-3">
                    <input type="text" wire:model="city" placeholder="Ciudad" class="w-full rounded-xl border-gray-200 font-bold p-3">
                    <select wire:model="blood_type" class="w-full rounded-xl border-gray-200 font-bold p-3">
                        <option value="">Tipo Sangre</option>
                        <option value="A+">A+</option><option value="A-">A-</option><option value="B+">B+</option><option value="B-">B-</option>
                        <option value="AB+">AB+</option><option value="AB-">AB-</option><option value="O+">O+</option><option value="O-">O-</option>
                    </select>
                </div>
                <div class="p-6 bg-gray-50 border-t border-gray-100 flex gap-4">
                    <button wire:click="cancel" class="flex-1 py-3 text-gray-500 font-bold uppercase tracking-widest text-[10px]">Cancelar</button>
                    <button wire:click="updateAthlete" class="flex-[2] py-3 bg-gradient-to-b from-red-500 to-red-700 hover:from-red-600 hover:to-red-800 text-white font-black rounded-xl transition-all shadow-lg shadow-red-500/30 uppercase tracking-widest text-xs">Actualizar</button>
                </div>
            </div>
        @endif

        @if($view === 'import')
            <div class="bg-white rounded-3xl shadow-xl border border-gray-200 overflow-hidden animate-fadeIn">
                <div class="bg-gray-900 p-8 flex justify-between items-center text-white">
                    <h3 class="text-2xl font-black uppercase tracking-tighter">Importar Atletas</h3>
                    <button wire:click="cancel" class="text-gray-400 hover:text-white transition-colors"><svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg></button>
                </div>
                <div class="p-12 text-center space-y-6">
                    <div class="border-4 border-dashed border-gray-100 rounded-3xl p-10 flex flex-col items-center">
                        <input type="file" wire:model="importFile" class="mb-4">
                        @error('importFile') <span class="text-red-600 font-bold text-xs">{{ $message }}</span> @enderror
                        <button wire:click="importAthletes" class="px-6 py-3 bg-gray-900 text-white font-black rounded-xl text-xs uppercase tracking-widest">Procesar CSV</button>
                    </div>
                </div>
                <div class="p-6 bg-gray-50 border-t border-gray-100 text-center">
                    <button wire:click="downloadTemplate" class="text-blue-600 font-bold uppercase tracking-widest text-[10px]">Descargar Formato CSV</button>
                </div>
            </div>
        @endif

        @if($view === 'bib_assignment')
            <div class="bg-white rounded-3xl shadow-2xl border border-gray-100 overflow-hidden animate-fadeIn max-w-2xl mx-auto">
                <div class="bg-gray-900 p-8 text-white flex justify-between items-center">
                    <div>
                        <h3 class="text-2xl font-black uppercase">{{ $bibIsEdit ? 'Cambiar Dorsal' : 'Asignar Dorsal' }}</h3>
                        <p class="text-xs text-gray-400 mt-1">{{ $bibIsEdit ? 'Corrige el número de dorsal ya asignado' : 'Confirma para registrar entrega de kit' }}</p>
                    </div>
                    <button wire:click="cancel" class="text-gray-400 hover:text-white transition-colors"><svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg></button>
                </div>
                <div class="p-12 text-center space-y-6">
                    <input type="text" wire:model="bib_number" autofocus placeholder="DORSAL" class="w-full text-center text-7xl font-black rounded-3xl border-gray-200 bg-gray-50 p-6 text-gray-900 shadow-inner">
                    @if($bibIsEdit)
                        <p class="text-sm font-bold text-orange-500 uppercase tracking-widest">⚠️ El atleta ya fue acreditado — se actualizará el número</p>
                    @else
                        <p class="text-sm font-bold text-gray-400 uppercase tracking-widest">Confirma para registrar entrega de kit</p>
                    @endif
                </div>
                <div class="p-6 bg-gray-50 border-t border-gray-100 flex gap-4">
                    <button wire:click="cancel" class="flex-1 py-3 text-gray-500 font-bold uppercase tracking-widest text-[10px]">Cancelar</button>
                    <button wire:click="saveBibNumber" class="flex-[2] py-3 bg-gradient-to-b from-red-500 to-red-700 hover:from-red-600 hover:to-red-800 text-white font-black rounded-xl transition-all shadow-lg shadow-red-500/30 uppercase tracking-widest text-xs">{{ $bibIsEdit ? 'Actualizar Dorsal' : 'Confirmar Entrega' }}</button>
                </div>
            </div>
        @endif

        @if($view === 'payment_validation')
            <div class="bg-white rounded-3xl shadow-2xl border border-gray-100 overflow-hidden animate-fadeIn max-w-2xl mx-auto">
                <div class="bg-gray-900 p-8 text-white flex justify-between items-center">
                    <h3 class="text-2xl font-black uppercase tracking-tighter">Validar Pago</h3>
                    <button wire:click="cancel" class="text-gray-400 hover:text-white transition-colors"><svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg></button>
                </div>
                <div class="p-8 space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Monto Recibido</label>
                            <input type="number" wire:model="payment_amount" class="w-full text-2xl font-black rounded-xl border-gray-200 bg-gray-50 p-3 shadow-inner">
                        </div>
                        <div>
                            <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Medio de Pago</label>
                            <select wire:model="payment_method" class="w-full font-bold rounded-xl border-gray-200 bg-gray-50 p-3">
                                <option value="Efectivo">Efectivo</option>
                                <option value="Transferencia">Transferencia</option>
                                <option value="Billetera Tigo">Billetera Tigo</option>
                                <option value="Billetera Personal">Billetera Personal</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Referencia / Comprobante</label>
                            <input type="text" wire:model="payment_reference" placeholder="Ej: #99944" class="w-full font-bold rounded-xl border-gray-200 bg-gray-50 p-3 shadow-inner">
                        </div>
                        <div>
                            <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Fecha del Pago</label>
                            <input type="date" wire:model="payment_date" class="w-full font-bold rounded-xl border-gray-200 bg-gray-50 p-3 shadow-inner">
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Notas Adicionales</label>
                            <textarea wire:model="payment_notes" class="w-full font-medium rounded-xl border-gray-200 bg-gray-50 p-3 shadow-inner" rows="2"></textarea>
                        </div>
                    </div>
                </div>
                <div class="p-6 bg-gray-50 border-t border-gray-100 flex gap-4">
                    <button wire:click="cancel" class="flex-1 py-3 text-gray-500 font-bold uppercase tracking-widest text-[10px]">Cancelar</button>
                    <button wire:click="savePayment" class="flex-[2] py-3 bg-gradient-to-b from-blue-500 to-blue-700 hover:from-blue-600 hover:to-blue-800 text-white font-black rounded-xl transition-all shadow-lg shadow-blue-500/30 uppercase tracking-widest text-xs">Confirmar Pago</button>
                </div>
            </div>
        @endif
    </div>
</div>

<style>
    .animate-fadeIn { animation: fadeIn 0.3s ease-out; }
    @keyframes fadeIn { from { opacity: 0; transform: scale(0.98); } to { opacity: 1; transform: scale(1); } }
</style>
@push('scripts')
<script>
    document.addEventListener('livewire:init', () => {
        Livewire.on('open-whatsapp', (event) => {
            window.open(event.url, '_blank');
        });
    });
</script>
@endpush
