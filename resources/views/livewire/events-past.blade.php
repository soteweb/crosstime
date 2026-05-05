<div class="space-y-12 pb-12 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-8">
    
    <div class="mb-12">
        <a href="{{ route('home') }}" class="text-gray-500 hover:text-red-600 transition-colors text-xs font-bold uppercase tracking-widest flex items-center mb-6">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Volver al Inicio
        </a>
        <h1 class="text-4xl md:text-5xl font-extrabold text-gray-900 border-l-8 border-red-600 pl-6 tracking-tight">Historial de Resultados</h1>
        <p class="text-gray-500 font-medium ml-8 mt-4 text-lg">Consulta las clasificaciones y resultados de todas nuestras competencias anteriores.</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        @forelse($events as $event)
            <div class="bg-gray-900 border border-gray-800 rounded-3xl overflow-hidden hover:bg-gray-800 transition-all duration-300 group shadow-xl">
                <div class="p-6">
                    <div class="flex justify-between items-start mb-6">
                        <div class="bg-white/10 px-3 py-1.5 rounded-xl text-red-500 text-[10px] font-bold uppercase tracking-widest border border-white/10">
                            {{ \Carbon\Carbon::parse($event->date)->isoFormat('MMMM, Y') }}
                        </div>
                        <span class="text-gray-500 text-[10px] font-black uppercase tracking-widest">FINALIZADO</span>
                    </div>

                    <h4 class="text-xl font-bold text-white mb-4 line-clamp-1 group-hover:text-red-500 transition-colors">{{ $event->title }}</h4>
                    
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
        @empty
            <div class="col-span-full text-center py-20 bg-gray-50 rounded-2xl border border-gray-200 shadow-sm mt-8">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                <h3 class="mt-2 text-sm font-bold text-gray-900">No hay resultados</h3>
                <p class="mt-1 text-sm text-gray-500">Aún no hay eventos finalizados.</p>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    <div class="mt-12 pt-8">
        {{ $events->links() }}
    </div>
</div>
