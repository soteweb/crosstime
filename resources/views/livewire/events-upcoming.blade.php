<div class="space-y-12 pb-12 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-8">
    
    <div class="mb-12 flex flex-col md:flex-row md:items-end justify-between gap-6">
        <div>
            <a href="{{ route('home') }}" class="text-gray-500 hover:text-red-600 transition-colors text-xs font-bold uppercase tracking-widest flex items-center mb-6">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Volver al Inicio
            </a>
            <h1 class="text-4xl md:text-5xl font-extrabold text-gray-900 border-l-8 border-red-600 pl-6 tracking-tight">Todos los Próximos Eventos</h1>
            <p class="text-gray-500 font-medium ml-8 mt-4 text-lg">Descubre las próximas competencias deportivas disponibles y reserva tu lugar hoy.</p>
        </div>
        
        <div class="w-full md:w-1/3 relative">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            </div>
            <input wire:model.live.debounce.300ms="search" type="text" class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-xl leading-5 bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-1 focus:ring-red-600 focus:border-red-600 sm:text-sm shadow-sm transition-shadow" placeholder="Buscar por evento o ciudad...">
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        @forelse($events as $event)
            <div class="group bg-white rounded-2xl overflow-hidden shadow-lg hover:shadow-red-500/10 hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2 border border-gray-200 flex flex-col">
                
                <!-- Cover Image / Graphic -->
                <div class="relative h-48 w-full overflow-hidden bg-gray-100 flex items-center justify-center shrink-0">
                    @if($event->featured_image)
                        <img src="{{ Str::startsWith($event->featured_image, 'http') ? $event->featured_image : asset('storage/' . $event->featured_image) }}" class="absolute inset-0 w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                    @else
                        <div class="absolute inset-0 bg-gradient-to-br from-gray-100 to-gray-200 group-hover:scale-105 transition-transform duration-500"></div>
                    @endif
                    <!-- Abstract category indicator -->
                    <div class="absolute inset-0 opacity-10 bg-[url('https://www.transparenttextures.com/patterns/diagonal-stripes.png')] mix-blend-overlay"></div>
                    @if($event->eventType)
                        <div class="absolute top-4 left-4 z-10 px-3 py-1 rounded-full text-xs text-white font-bold shadow-md uppercase tracking-wider" style="background-color: {{ $event->eventType->color ?? '#f97316' }}">
                            {{ $event->eventType->name }}
                        </div>
                    @endif
                    <h4 class="z-10 text-gray-800 font-extrabold text-5xl opacity-5 uppercase tracking-tighter">{{ explode(' ', $event->title)[0] }}</h4>
                    
                    <!-- Date Badge -->
                    <div class="absolute bottom-4 left-4 flex items-end space-x-2">
                        <div class="bg-red-600 text-white px-3 py-2 rounded-xl shadow-lg font-bold text-center leading-none">
                            <span class="block text-2xl">{{ \Carbon\Carbon::parse($event->date)->format('d') }}</span>
                            <span class="block text-xs uppercase">{{ \Carbon\Carbon::parse($event->date)->translatedFormat('M') }}</span>
                        </div>
                        
                        <div class="bg-white/90 backdrop-blur-sm text-gray-900 px-3 py-1.5 rounded-lg shadow-sm font-bold text-[10px] uppercase tracking-tighter border border-gray-200 mb-1">
                            @php
                                $eventDate = \Carbon\Carbon::parse($event->date)->startOfDay();
                                $now = \Carbon\Carbon::now()->startOfDay();
                            @endphp
                            @if($now->isSameDay($eventDate))
                                EVENTO HOY
                            @elseif($now->copy()->addDay()->isSameDay($eventDate))
                                MAÑANA
                            @else
                                @php
                                    $days = $now->diffInDays($eventDate);
                                    $months = $now->diffInMonths($eventDate);
                                @endphp
                                @if($days < 31)
                                    FALTAN {{ (int)$days }} DÍAS
                                @else
                                    FALTAN {{ (int)$months }} MESES
                                @endif
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Content Details -->
                <div class="p-6 flex flex-col flex-grow relative">
                    <div class="absolute -top-6 right-6">
                        <span class="bg-white border border-gray-200 text-gray-700 text-xs font-bold px-3 py-1 rounded-full uppercase tracking-wider shadow-md inline-flex items-center">
                            <span class="flex h-2 w-2 relative mr-2">
                              <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                              <span class="relative inline-flex rounded-full h-2 w-2 bg-red-600"></span>
                            </span>
                            ABIERTO
                        </span>
                    </div>

                    <h3 class="text-xl font-bold text-gray-900 mb-2 leading-tight group-hover:text-orange-600 transition-colors">
                        <a href="{{ route('event.show', $event) }}" class="focus:outline-none">
                            <span class="absolute inset-0" aria-hidden="true"></span>
                            {{ $event->title }}
                        </a>
                    </h3>
                    
                    <p class="text-gray-600 text-sm mb-4 line-clamp-2 break-all md:break-words">
                        {{ html_entity_decode(strip_tags($event->description)) }}
                    </p>

                    <div class="flex flex-col space-y-2 text-sm text-gray-500 mb-6 font-medium mt-auto">
                        <div class="flex items-center">
                            <svg class="w-4 h-4 mr-2 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                            <span class="truncate">{{ $event->location_address }}</span>
                        </div>
                        @if($event->organizer)
                        <div class="flex items-center text-[11px] font-bold uppercase tracking-widest text-red-600">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                            <span>Organiza: {{ $event->organizer }}</span>
                        </div>
                        @endif
                    </div>

                    <!-- CTA -->
                    <div class="flex justify-end items-center border-t border-gray-100 pt-4">
                        <div class="bg-gray-100 group-hover:bg-red-600 group-hover:text-white text-gray-600 p-2.5 rounded-full transition-colors shadow-sm">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full text-center py-20 bg-white rounded-2xl border border-gray-200 shadow-sm mt-8">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <h3 class="mt-2 text-sm font-bold text-gray-900">No hay eventos próximos</h3>
                <p class="mt-1 text-sm text-gray-500">Mantente atento a nuevas actualizaciones.</p>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    <div class="mt-12 pt-8 border-t border-gray-100">
        {{ $events->links() }}
    </div>
</div>
