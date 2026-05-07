<div class="space-y-8">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h2 class="text-3xl font-black text-gray-900 border-l-4 border-red-600 pl-4 uppercase tracking-tighter">Check-In / Kits</h2>
            <p class="text-[10px] text-gray-400 ml-4 font-bold uppercase tracking-widest mt-1">Entrega rápida de dorsales y remeras</p>
        </div>
        
        <div class="w-full md:w-96">
            <div x-data="{ open: false }" class="relative w-full">
                <div class="relative" @click.away="open = false">
                    <!-- Fake Select / Input -->
                    <div @click="open = true; $nextTick(() => $refs.search.focus())" 
                         class="w-full rounded-2xl border border-gray-200 bg-white p-4 shadow-sm cursor-pointer flex justify-between items-center text-gray-700 min-h-[56px] transition-all overflow-hidden" 
                         :class="open ? 'ring-2 ring-red-600 border-red-600' : ''">
                        
                        @php
                            $selectedEvent = $selected_event_id ? collect($events)->firstWhere('id', $selected_event_id) : null;
                            if (!$selectedEvent && $selected_event_id) {
                                $selectedEvent = \App\Models\Event::find($selected_event_id);
                            }
                        @endphp
                        
                        <div x-show="!open" class="truncate font-bold w-full text-sm">
                            {{ $selectedEvent ? $selectedEvent->title : 'Seleccionar Evento...' }}
                        </div>
                        
                        <input 
                            x-show="open" 
                            x-ref="search"
                            type="text" 
                            wire:model.live.debounce.300ms="eventSearchTerm" 
                            class="bg-transparent border-none p-0 focus:ring-0 w-full font-bold text-sm" 
                            placeholder="Buscar evento por nombre..."
                        >
                        <svg class="w-5 h-5 text-gray-400 shrink-0 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </div>
                    
                    <!-- Dropdown List -->
                    <div x-cloak x-show="open" 
                         x-transition
                         class="absolute z-50 w-full mt-2 bg-white border border-gray-100 rounded-2xl shadow-2xl max-h-72 overflow-y-auto">
                        
                        <button type="button" 
                                wire:click="$set('selected_event_id', ''); $set('eventSearchTerm', '')" 
                                @click="open = false" 
                                class="w-full text-left px-5 py-4 text-xs font-black uppercase tracking-widest text-gray-400 hover:text-gray-900 hover:bg-gray-50 border-b border-gray-100 transition-colors">
                            × Limpiar Selección
                        </button>
                        
                        @foreach($events as $e)
                            <button type="button"
                                    wire:click="$set('selected_event_id', '{{ $e->id }}'); $set('eventSearchTerm', '')" 
                                    @click="open = false" 
                                    class="w-full text-left px-5 py-3 text-sm font-bold text-gray-900 hover:bg-red-50 hover:text-red-600 border-b border-gray-50 transition-colors">
                                <span class="line-clamp-1">{{ $e->title }}</span>
                                <span class="block text-[10px] text-gray-400 font-medium mt-0.5 uppercase tracking-widest">{{ \Carbon\Carbon::parse($e->date)->translatedFormat('d M, Y') }}</span>
                            </button>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if(!$selected_event_id)
        <div class="bg-white rounded-[2rem] p-20 text-center border-2 border-dashed border-gray-200">
            <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-6">
                <svg class="w-10 h-10 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"></path></svg>
            </div>
            <h3 class="text-xl font-bold text-gray-900 uppercase tracking-tighter">Selecciona el Evento en Curso</h3>
            <p class="text-sm text-gray-400 font-medium mt-2">Activa el modo de entrega de kits eligiendo un evento arriba.</p>
        </div>
    @else
        <!-- Check-in Main Area -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            
            <!-- Left Column: Search & Results -->
            <div class="lg:col-span-7 space-y-6">
                @if(session()->has('message'))
                    <div class="bg-green-50 border-l-4 border-green-500 p-4 rounded-xl shadow-sm flex items-start">
                        <svg class="w-6 h-6 text-green-500 mr-3 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <div>
                            <h4 class="font-bold text-green-800">{{ session('message') }}</h4>
                        </div>
                    </div>
                @endif

                <div class="bg-white p-6 md:p-10 rounded-[2rem] shadow-lg border border-gray-100">
                    <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-3">Buscar Atleta</label>
                    <div class="relative">
                        <input type="text" wire:model.live.debounce.300ms="athleteSearchTerm" 
                               placeholder="Ingresar CI, Nombre o Apellido..." 
                               class="w-full rounded-2xl border-2 border-gray-200 bg-gray-50 focus:border-red-600 focus:ring-red-600 focus:bg-white text-xl font-bold p-5 pl-14 transition-all"
                               {{ $selectedRegistrationId ? 'disabled' : '' }}>
                        <svg class="w-7 h-7 text-gray-400 absolute left-5 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        
                        @if($athleteSearchTerm && !$selectedRegistrationId)
                            <button wire:click="$set('athleteSearchTerm', '')" class="absolute right-5 top-1/2 -translate-y-1/2 text-gray-400 hover:text-red-600 bg-gray-100 rounded-full p-1 transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                            </button>
                        @endif
                    </div>

                    <!-- Search Results -->
                    @if(count($searchResults) > 0 && !$selectedRegistrationId)
                        <div class="mt-4 border border-gray-100 rounded-2xl overflow-hidden divide-y divide-gray-50 bg-white shadow-sm">
                            @foreach($searchResults as $reg)
                                <button wire:click="selectRegistration({{ $reg->id }})" class="w-full text-left p-4 hover:bg-red-50 flex items-center justify-between group transition-colors">
                                    <div>
                                        <h4 class="font-black text-gray-900 group-hover:text-red-600 text-lg uppercase tracking-tight">{{ $reg->athlete->name }} {{ $reg->athlete->last_name }}</h4>
                                        <p class="text-xs font-bold text-gray-500 tracking-widest mt-1">CI: {{ $reg->athlete->identity_document }}</p>
                                    </div>
                                    <div class="text-right">
                                        <span class="block text-xs font-bold text-gray-400 uppercase">{{ $reg->distance->name ?? '' }}</span>
                                        @if($reg->payment_status === 'paid')
                                            <span class="inline-block mt-1 px-2 py-1 bg-green-100 text-green-700 text-[9px] font-black uppercase rounded-lg">PAGADO</span>
                                        @else
                                            <span class="inline-block mt-1 px-2 py-1 bg-red-100 text-red-700 text-[9px] font-black uppercase rounded-lg">DEBE PAGAR</span>
                                        @endif
                                    </div>
                                </button>
                            @endforeach
                        </div>
                    @elseif(strlen($athleteSearchTerm) >= 2 && !$selectedRegistrationId)
                        <div class="mt-6 text-center py-8 bg-gray-50 rounded-2xl border-2 border-dashed border-gray-200">
                            <svg class="mx-auto h-12 w-12 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                            <h3 class="mt-2 text-sm font-bold text-gray-900">Atleta no encontrado</h3>
                            <p class="mt-1 text-xs text-gray-500">Asegúrate de escribir correctamente la cédula o el nombre.</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Right Column: Verification & Action -->
            <div class="lg:col-span-5">
                @if($selectedRegistration)
                    <div class="bg-white rounded-[2rem] shadow-2xl border-4 {{ $selectedRegistration->payment_status === 'paid' ? 'border-green-500' : 'border-red-500' }} overflow-hidden relative">
                        
                        <div class="p-6 md:p-8 space-y-8">
                            <!-- Athlete Info -->
                            <div class="flex justify-between items-start">
                                <div>
                                    <h3 class="text-2xl font-black text-gray-900 uppercase tracking-tighter">{{ $selectedRegistration->athlete->name }}</h3>
                                    <h3 class="text-2xl font-black text-gray-900 uppercase tracking-tighter">{{ $selectedRegistration->athlete->last_name }}</h3>
                                    <p class="text-sm font-bold text-gray-500 tracking-widest mt-2">CI: {{ $selectedRegistration->athlete->identity_document }}</p>
                                </div>
                                <button wire:click="clearRegistration" class="text-gray-400 hover:text-red-600 bg-gray-100 hover:bg-red-50 rounded-full p-2 transition-colors">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                </button>
                            </div>

                            <!-- Payment Traffic Light -->
                            <div class="rounded-2xl p-6 {{ $selectedRegistration->payment_status === 'paid' ? 'bg-green-50' : 'bg-red-50' }}">
                                @if($selectedRegistration->payment_status === 'paid')
                                    <div class="flex items-center text-green-700">
                                        <svg class="w-10 h-10 mr-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        <div>
                                            <p class="text-xl font-black uppercase tracking-tight">Pago Confirmado</p>
                                            <p class="text-xs font-bold opacity-75 uppercase tracking-widest">Listo para entrega</p>
                                        </div>
                                    </div>
                                @else
                                    <div class="flex items-center text-red-700 mb-4">
                                        <svg class="w-10 h-10 mr-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                                        <div>
                                            <p class="text-xl font-black uppercase tracking-tight">Pago Pendiente</p>
                                            <p class="text-sm font-bold opacity-90 mt-1">Debe abonar: {{ number_format($selectedRegistration->category->price ?? 0, 0, ',', '.') }} Gs.</p>
                                        </div>
                                    </div>
                                    <button wire:click="markAsPaid" class="w-full bg-red-600 hover:bg-red-700 text-white font-bold py-3 rounded-xl uppercase tracking-widest text-xs shadow-lg transition-colors">
                                        Registrar Pago Ahora
                                    </button>
                                @endif
                            </div>

                            <!-- Kit Details -->
                            <div class="grid grid-cols-2 gap-4">
                                <div class="bg-gray-50 rounded-2xl p-4 border border-gray-100">
                                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Talle Remera</p>
                                    <p class="text-3xl font-black text-gray-900">{{ $selectedRegistration->tshirt_size ?: 'N/A' }}</p>
                                </div>
                                <div class="bg-gray-50 rounded-2xl p-4 border border-gray-100">
                                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Distancia</p>
                                    <p class="text-3xl font-black text-gray-900">{{ $selectedRegistration->distance->name ?? '-' }}</p>
                                </div>
                            </div>
                            
                            <div class="bg-gray-50 rounded-2xl p-4 border border-gray-100">
                                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Categoría</p>
                                <p class="text-lg font-black text-gray-900">{{ $selectedRegistration->category->name ?? '-' }}</p>
                            </div>

                            <!-- Action: Assign Bib -->
                            <div class="space-y-4 pt-4 border-t-2 border-dashed border-gray-200">
                                @if($selectedRegistration->status === 'participant')
                                    <div class="bg-orange-50 text-orange-700 p-6 rounded-2xl text-center border border-orange-200">
                                        <svg class="w-12 h-12 mx-auto mb-3 text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        <h4 class="font-black text-lg uppercase tracking-tight">KIT YA ENTREGADO</h4>
                                        <p class="text-xs font-bold mt-1 uppercase tracking-widest">Dorsal asignado: {{ $selectedRegistration->bib_number }}</p>
                                    </div>
                                @else
                                    <label class="block text-xs font-black text-gray-400 uppercase tracking-widest text-center">Asignar Nº de Dorsal</label>
                                    <input type="text" wire:model="assigned_bib_number" 
                                           placeholder="EJ: 405" 
                                           class="w-full rounded-2xl border-2 border-gray-200 bg-white focus:border-red-600 focus:ring-red-600 text-center text-4xl font-black p-4 uppercase transition-all shadow-inner">
                                    
                                    <button wire:click="checkIn" 
                                            class="w-full bg-gradient-to-r from-gray-900 to-black hover:from-black hover:to-gray-900 text-white font-black py-5 rounded-2xl uppercase tracking-widest text-lg shadow-2xl transition-all flex items-center justify-center disabled:opacity-50"
                                            {{ $selectedRegistration->payment_status !== 'paid' || !$assigned_bib_number ? 'disabled' : '' }}>
                                        <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                                        Entregar Kit
                                    </button>
                                    @if($selectedRegistration->payment_status !== 'paid')
                                        <p class="text-center text-[10px] text-red-600 font-bold uppercase tracking-widest mt-2">Debe registrar el pago primero</p>
                                    @endif
                                @endif
                            </div>
                        </div>
                    </div>
                @else
                    <div class="bg-gray-50 rounded-[2rem] h-full flex items-center justify-center border-2 border-dashed border-gray-200 p-10 min-h-[400px]">
                        <div class="text-center">
                            <svg class="mx-auto h-16 w-16 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 15l-2 5L9 9l11 4-5 2zm0 0l5 5M7.188 2.239l.777 2.897M5.136 7.965l-2.898-.777M13.95 4.05l-2.122 2.122m-5.657 5.656l-2.12 2.122"></path></svg>
                            <h3 class="mt-4 text-lg font-black text-gray-900 uppercase tracking-tighter">Esperando Búsqueda...</h3>
                            <p class="mt-2 text-sm font-medium text-gray-400">Selecciona un atleta a la izquierda para ver su estado y entregarle el kit.</p>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    @endif
</div>

@push('scripts')
<script>
    document.addEventListener('livewire:init', () => {
        Livewire.on('open-whatsapp', (event) => {
            window.open(event.url, '_blank');
        });
    });
</script>
@endpush
