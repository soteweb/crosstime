<div class="space-y-12 pb-12">
    <!-- Hero Carousel Section (AlpineJS) -->
    <div x-data="{
            activeSlide: 0,
            slides: [
                @foreach(App\Models\Event::whereNotNull('featured_image')->where('is_active', true)->where('date', '>=', now()->startOfDay())->take(3)->get() as $e)
                { 
                    image: '{{ Str::startsWith($e->featured_image, "http") ? $e->featured_image : asset("storage/" . $e->featured_image) }}', 
                    subtitle: '{{ Str::limit($e->location_address, 20) }}', 
                    title: '{{ addslashes($e->title) }}',
                    url: '{{ route('event.show', $e) }}'
                }{{ $loop->last ? '' : ',' }}
                @endforeach
            ]
        }"
        x-init="setInterval(() => activeSlide = (activeSlide === slides.length - 1) ? 0 : activeSlide + 1, 4000)"
        class="relative w-full h-[300px] md:h-[500px] rounded-3xl overflow-hidden shadow-2xl mt-4 bg-gray-100 border border-gray-200 hidden md:block"
    >
        <!-- Alpine Slides -->
        <template x-for="(slide, index) in slides" :key="index">
            <a :href="slide.url" 
                 x-show="activeSlide === index" 
                 x-transition:enter="transition ease-out duration-1000"
                 x-transition:enter-start="opacity-0 scale-105"
                 x-transition:enter-end="opacity-100 scale-100"
                 x-transition:leave="transition ease-in duration-1000"
                 x-transition:leave-start="opacity-100 scale-100"
                 x-transition:leave-end="opacity-0 scale-105"
                 class="absolute inset-0 group">
                
                <img :src="slide.image" class="w-full h-full object-contain md:object-cover opacity-90 mix-blend-multiply group-hover:scale-105 transition-transform duration-[2000ms]" :alt="slide.title">
                
                <!-- Gradient Overlay Light -->
                <div class="absolute inset-0 bg-gradient-to-t from-white via-white/80 to-transparent mix-blend-normal"></div>
                <div class="absolute inset-0 bg-gradient-to-r from-white via-white/50 to-transparent"></div>
                
                <!-- Text Overlay -->
                <div class="absolute bottom-10 left-6 md:left-12 md:bottom-16">
                    <span class="px-4 py-1.5 bg-red-600 text-white text-xs font-bold uppercase rounded-full shadow-md mb-4 inline-block" x-text="slide.subtitle"></span>
                    <h2 class="text-3xl md:text-6xl font-extrabold text-gray-900 tracking-tighter drop-shadow-sm group-hover:text-red-600 transition-colors" x-text="slide.title"></h2>
                    <div class="mt-4 flex items-center gap-3">
                        <p class="text-sm md:text-lg text-gray-600 max-w-2xl font-medium drop-shadow-sm">
                            Encuentra las mejores competencias deportivas en un solo lugar.
                        </p>
                        <div class="hidden md:flex bg-red-600 text-white p-2 rounded-full shadow-lg group-hover:translate-x-2 transition-transform">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                        </div>
                    </div>
                </div>
            </a>
        </template>

        <!-- Indicators -->
        <div class="absolute bottom-4 left-0 right-0 flex justify-center space-x-2">
            <template x-for="(slide, index) in slides" :key="index">
                <button @click="activeSlide = index" 
                        class="w-3 h-3 rounded-full transition-all duration-300 shadow-sm border border-gray-300"
                        :class="activeSlide === index ? 'bg-red-600 w-8 border-red-600' : 'bg-gray-300 hover:bg-gray-400'">
                </button>
            </template>
        </div>
    </div>

    <!-- Eventos Section -->
    <div>
        <div class="flex items-center justify-between mb-8">
            <h3 class="text-3xl font-bold text-gray-900 border-l-4 border-red-600 pl-4">Próximos Eventos</h3>
            @if($totalEvents > 6)
            <a href="{{ route('events.upcoming') }}" class="text-red-600 hover:text-red-700 transition-colors text-sm font-bold uppercase tracking-wider flex items-center">
                Ver Todos 
                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
            </a>
            @endif
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @forelse($events as $index => $event)
                <div class="group bg-white rounded-2xl overflow-hidden shadow-lg hover:shadow-red-500/10 hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2 border border-gray-200">
                    
                    <!-- Cover Image / Graphic -->
                    <div class="relative h-48 w-full overflow-hidden bg-gray-100 flex items-center justify-center">
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
                    <div class="p-6 relative">
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
                            {{ strip_tags($event->description) }}
                        </p>

                        <div class="flex flex-col space-y-2 text-sm text-gray-500 mb-6 font-medium">
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
                        <div class="flex justify-end items-center mt-auto border-t border-gray-100 pt-4">
                            <div class="bg-gray-100 group-hover:bg-red-600 group-hover:text-white text-gray-600 p-2.5 rounded-full transition-colors shadow-sm">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full text-center py-20 bg-white rounded-2xl border border-gray-200 shadow-sm mt-8">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <h3 class="mt-2 text-sm font-bold text-gray-900">No hay eventos activos</h3>
                    <p class="mt-1 text-sm text-gray-500">Revisa pronto para conocer nuevas competencias.</p>
                </div>
            @endforelse
        </div>

        @if($totalEvents > 6)
            <div class="mt-12 flex justify-center">
                <a href="{{ route('events.upcoming') }}" class="bg-white border-2 border-red-600 text-red-600 hover:bg-red-600 hover:text-white font-bold py-3 px-8 rounded-2xl transition-all duration-300 shadow-xl hover:shadow-red-500/30 uppercase tracking-widest text-sm inline-flex w-max">
                    Ver Todos los Eventos ({{ $totalEvents }})
                </a>
            </div>
        @endif
    </div>

    <!-- Resultados Section -->
    @if(count($pastEvents) > 0)
    <div class="bg-gray-900 -mx-6 md:-mx-12 px-6 md:px-12 py-16 rounded-[40px] mt-20">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-12 gap-4">
            <div>
                <h3 class="text-3xl font-bold text-white border-l-4 border-red-600 pl-4 mb-2">Resultados</h3>
                <p class="text-gray-400 font-medium ml-5">Eventos ya realizados y clasificaciones.</p>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($pastEvents as $event)
                <div class="bg-white/5 border border-white/10 rounded-3xl overflow-hidden hover:bg-white/10 transition-all duration-300 group">
                    <div class="p-6">
                        <div class="flex justify-between items-start mb-6">
                            <div class="bg-white/10 px-3 py-1.5 rounded-xl text-red-600 text-[10px] font-bold uppercase tracking-widest border border-white/10">
                                {{ \Carbon\Carbon::parse($event->date)->isoFormat('MMMM, Y') }}
                            </div>
                            <span class="text-gray-500 text-[10px] font-black uppercase tracking-widest">FINALIZADO</span>
                        </div>

                        <h4 class="text-xl font-bold text-white mb-4 line-clamp-1 group-hover:text-red-600 transition-colors">{{ $event->title }}</h4>
                        
                        <div class="space-y-2 mb-8">
                            <div class="flex items-center text-xs text-gray-400 font-bold">
                                <svg class="w-4 h-4 mr-2 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                {{ $event->location_address }}
                            </div>
                            @if($event->organizer)
                            <div class="flex items-center text-[10px] text-gray-500 font-black uppercase tracking-tighter">
                                <svg class="w-4 h-4 mr-2 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                                {{ $event->organizer }}
                            </div>
                            @endif
                        </div>

                        <div class="grid grid-cols-2 gap-3">
                            <a href="{{ route('event.show', $event) }}" class="py-3 bg-white/5 hover:bg-white/20 text-white text-[10px] font-black uppercase tracking-widest text-center rounded-xl transition-all border border-white/5">
                                Ver Detalles
                            </a>
                            @if($event->results_url)
                            <a href="{{ $event->results_url }}" target="_blank" class="py-3 bg-gradient-to-b from-red-500 to-red-700 hover:from-red-600 hover:to-red-800 text-white text-[10px] font-bold uppercase tracking-widest text-center rounded-xl transition-all shadow-lg shadow-red-900/40">
                                Ver Resultados
                            </a>
                            @else
                            <button disabled class="py-3 bg-gray-800 text-gray-600 text-[10px] font-black uppercase tracking-widest text-center rounded-xl cursor-not-allowed">
                                Pendiente
                            </button>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        @if(\App\Models\Event::where('is_active', true)->where('date', '<', now()->startOfDay())->count() > 6)
            <div class="mt-12 flex justify-center">
                <a href="{{ route('events.past') }}" class="bg-gray-900 border-2 border-white/20 text-white hover:bg-white hover:text-gray-900 font-bold py-3 px-8 rounded-2xl transition-all duration-300 shadow-xl uppercase tracking-widest text-sm inline-flex w-max">
                    Ver Todo el Historial
                </a>
            </div>
        @endif
    </div>
    @endif
</div>
