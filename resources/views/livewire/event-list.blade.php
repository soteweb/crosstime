<div class="space-y-12 pb-12">
    <style>
        .embla { overflow: hidden; }
        .embla__container { display: flex; }
        .embla__slide { flex: 0 0 100%; min-width: 0; }
        .carousel-overlay { background: linear-gradient(to right, rgba(0,0,0,0.8) 0%, rgba(0,0,0,0.4) 50%, rgba(0,0,0,0) 100%); }
    </style>

    <div class="-mt-8 mb-12 w-screen relative left-1/2 right-1/2 -ml-[50vw] -mr-[50vw]">
        <section class="w-full relative bg-gray-900 h-[400px] md:h-[500px]">
            <div class="embla h-full" id="hero-carousel">
                <div class="embla__container h-full">
                    @foreach(App\Models\Event::whereNotNull('featured_image')->where('is_active', true)->where('date', '>=', now()->startOfDay())->take(3)->get() as $e)
                        <!-- Slide -->
                        <div class="embla__slide relative h-full">
                            <div class="absolute inset-0 w-full h-full bg-cover bg-center" style="background-image: url('{{ Str::startsWith($e->featured_image, 'http') ? $e->featured_image : asset('storage/' . $e->featured_image) }}');"></div>
                            <div class="absolute inset-0 carousel-overlay"></div>
                            <div class="absolute inset-0 flex items-center">
                                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full z-10">
                                    <div class="max-w-2xl text-white">
                                        <span class="inline-block bg-red-600 text-white text-xs font-bold tracking-widest px-3 py-1 rounded-full mb-4 uppercase shadow-sm">
                                            @if($e->eventType) {{ $e->eventType->name }} @else DESTACADO @endif
                                        </span>
                                        <h1 class="text-4xl md:text-5xl font-black text-white mb-2 leading-tight uppercase drop-shadow-md">{{ $e->title }}</h1>
                                        <p class="text-lg font-bold text-gray-300 mb-8 flex items-center gap-2 drop-shadow-sm">
                                            <span class="material-symbols-outlined text-[20px]">calendar_month</span> {{ \Carbon\Carbon::parse($e->date)->translatedFormat('j \d\e F') }}
                                            <span class="mx-2 opacity-50">|</span>
                                            <span class="material-symbols-outlined text-[20px]">location_on</span> {{ Str::limit($e->location_address, 25) }}
                                        </p>
                                        <a href="{{ route('event.show', $e) }}" class="bg-red-600 hover:bg-red-700 text-white transition-colors px-8 py-4 rounded-full text-xs font-bold uppercase flex items-center gap-2 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 inline-flex w-max">
                                            Más información
                                            <span class="material-symbols-outlined text-[18px]">arrow_forward</span>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            
            <!-- Carousel Controls -->
            <div class="absolute bottom-8 left-0 w-full z-20">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex justify-end gap-3">
                    <button class="w-10 h-10 rounded-full bg-white/10 hover:bg-white/20 backdrop-blur-md flex items-center justify-center text-white transition-colors border border-white/20" id="prev-btn">
                        <span class="material-symbols-outlined">chevron_left</span>
                    </button>
                    <button class="w-10 h-10 rounded-full bg-white/10 hover:bg-white/20 backdrop-blur-md flex items-center justify-center text-white transition-colors border border-white/20" id="next-btn">
                        <span class="material-symbols-outlined">chevron_right</span>
                    </button>
                </div>
            </div>
        </section>
    </div>

    @push('scripts')
    <script src="https://unpkg.com/embla-carousel/embla-carousel.umd.js"></script>
    <script src="https://unpkg.com/embla-carousel-autoplay/embla-carousel-autoplay.umd.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const emblaNode = document.querySelector('.embla');
            if (emblaNode) {
                const options = { loop: true };
                const plugins = [EmblaCarouselAutoplay({ delay: 5000, stopOnInteraction: false })];
                const emblaApi = EmblaCarousel(emblaNode, options, plugins);
                
                document.getElementById('prev-btn').addEventListener('click', () => emblaApi.scrollPrev(), false);
                document.getElementById('next-btn').addEventListener('click', () => emblaApi.scrollNext(), false);
            }
        });
    </script>
    @endpush

    <!-- Eventos Section -->
    <div id="proximos-eventos" class="scroll-mt-24">
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
                <div class="bg-white rounded-xl border border-gray-200 overflow-hidden shadow-md flex flex-col group cursor-pointer hover:border-red-600/50 transition-all hover:shadow-xl relative" onclick="window.location='{{ route('event.show', $event) }}'">
                    <!-- Cover Image -->
                    <div class="h-48 bg-gray-100 relative overflow-hidden">
                        @if($event->featured_image)
                            <img src="{{ Str::startsWith($event->featured_image, 'http') ? $event->featured_image : asset('storage/' . $event->featured_image) }}" class="absolute inset-0 w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                        @else
                            <div class="absolute inset-0 bg-gradient-to-br from-gray-100 to-gray-200 group-hover:scale-105 transition-transform duration-500"></div>
                        @endif
                        
                        <div class="absolute inset-0 opacity-10 bg-[url('https://www.transparenttextures.com/patterns/diagonal-stripes.png')] mix-blend-overlay"></div>
                        
                        <div class="absolute top-4 left-4 text-white text-xs font-bold px-3 py-1 rounded-full uppercase z-10 shadow-sm tracking-widest" @if($event->eventType && $event->eventType->color) style="background-color: {{ $event->eventType->color }}" @else class="bg-red-600" @endif>
                            @if($event->eventType) {{ $event->eventType->name }} @else EVENTO @endif
                        </div>
                        
                        <div class="absolute bottom-4 right-4 bg-white text-green-600 font-bold text-[10px] px-3 py-1.5 rounded-full shadow-sm flex items-center gap-1.5 z-10 uppercase tracking-widest border border-gray-100">
                            <span class="w-2 h-2 rounded-full bg-green-500 animate-pulse"></span> ABIERTO
                        </div>
                    </div>

                    <!-- Content Details -->
                    <div class="p-5 flex flex-col flex-grow">
                        <h3 class="text-xl font-bold mb-2 group-hover:text-red-600 transition-colors text-gray-900 leading-tight">
                            {{ $event->title }}
                        </h3>
                        
                        <p class="text-sm text-gray-500 line-clamp-2 mb-4 break-words">
                            {{ html_entity_decode(strip_tags($event->description), ENT_QUOTES, 'UTF-8') }}
                        </p>

                        <div class="mt-auto space-y-2 text-xs font-medium text-gray-500 pt-2 border-t border-gray-50">
                            <div class="flex items-center gap-2">
                                <span class="material-symbols-outlined text-[16px] text-red-500">calendar_month</span>
                                <span>{{ \Carbon\Carbon::parse($event->date)->translatedFormat('d M, Y') }}</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="material-symbols-outlined text-[16px] text-red-500">location_on</span>
                                <span class="truncate">{{ $event->location_address }}</span>
                            </div>
                            @if($event->organizer)
                            <div class="flex items-center gap-2">
                                <span class="material-symbols-outlined text-[16px] text-red-500">storefront</span>
                                <span class="truncate uppercase tracking-wider text-[10px]">{{ $event->organizer }}</span>
                            </div>
                            @endif
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
    <div class="bg-gray-900 mx-0 sm:-mx-6 md:-mx-12 px-6 md:px-12 py-16 rounded-[40px] mt-20 overflow-hidden">
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
