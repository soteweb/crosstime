<div class="min-h-screen bg-gray-50 pb-20">
    <!-- Clean Header Section -->
    <div class="bg-white border-b border-gray-200 pt-8 pb-12">
        <div class="max-w-6xl mx-auto px-6">
            <!-- Breadcrumbs -->
            <nav class="flex mb-6 text-[10px] font-bold uppercase tracking-widest text-gray-400">
                <a href="{{ route('home') }}" class="hover:text-red-600 transition-colors">Inicio</a>
                <span class="mx-2">/</span>
                <span class="text-gray-900">Eventos</span>
                <span class="mx-2">/</span>
                <span class="text-red-600 truncate">{{ $event->title }}</span>
            </nav>

            <div class="flex flex-col md:flex-row justify-between items-start gap-6">
                <div class="flex-1">
                    @if($event->eventType)
                        <span class="inline-block px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest text-white mb-4 shadow-sm" style="background-color: {{ $event->eventType->color ?? '#f97316' }}">
                            {{ $event->eventType->name }}
                        </span>
                    @endif
                    <h1 class="text-4xl md:text-5xl font-black text-gray-900 leading-tight mb-4 uppercase tracking-tighter">
                        {{ $event->title }}
                    </h1>
                    <div class="flex flex-wrap items-center gap-y-2 gap-x-6 text-gray-500 text-sm font-bold">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 mr-2 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            {{ \Carbon\Carbon::parse($event->date)->isoFormat('dddd D [de] MMMM, Y') }}
                        </div>
                        <div class="flex items-center">
                            <svg class="w-5 h-5 mr-2 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                            {{ $event->location_address }}
                        </div>
                        @if($event->registration_deadline)
                        <div class="flex items-center text-orange-600">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            Cierre de inscripciones: {{ $event->registration_deadline->isoFormat('D [de] MMMM, HH:mm') }} hs
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="max-w-6xl mx-auto px-6 mt-12">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">
            
            <!-- Left Column: Image & Details -->
            <div class="lg:col-span-2 space-y-12">
                
                <!-- Sobere Featured Image -->
                @if($event->featured_image)
                <div class="relative group">
                    <div class="absolute -inset-1 bg-gradient-to-r from-red-600 to-orange-600 rounded-[2rem] blur opacity-10 group-hover:opacity-20 transition duration-1000"></div>
                    <div class="relative aspect-video w-full rounded-[2rem] overflow-hidden shadow-2xl bg-gray-100 border border-gray-200">
                        <img src="{{ asset('storage/' . $event->featured_image) }}" class="w-full h-full object-cover" alt="{{ $event->title }}">
                    </div>
                </div>
                @endif

                <!-- Description -->
                <div class="bg-white p-10 rounded-3xl shadow-sm border border-gray-100">
                    <h2 class="text-xl font-black text-gray-900 mb-8 uppercase tracking-tighter flex items-center">
                        <span class="w-2 h-6 bg-red-600 rounded-full mr-3"></span>
                        Información General
                    </h2>
                    <div class="prose prose-red max-w-none text-gray-600 leading-relaxed font-medium">
                        {!! $event->description !!}
                    </div>
                </div>

                <!-- Distances & Categories -->
                @if($event->distances->count() > 0)
                <div class="space-y-6">
                    <h2 class="text-xl font-black text-gray-900 uppercase tracking-tighter flex items-center px-4">
                        <span class="w-2 h-6 bg-red-600 rounded-full mr-3"></span>
                        Distancias y Categorías
                    </h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        @foreach($event->distances as $distance)
                        <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm hover:shadow-md transition-shadow">
                            <div class="flex items-center justify-between mb-4 pb-4 border-b border-gray-50">
                                <h3 class="text-lg font-black text-red-600 uppercase">{{ $distance->name }}</h3>
                                <span class="bg-gray-100 text-gray-500 text-[10px] font-black px-2 py-1 rounded uppercase tracking-widest">Competición</span>
                            </div>
                            <div class="space-y-2">
                                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Categorías:</p>
                                <div class="flex flex-wrap gap-2">
                                    @foreach($event->categories->where('distance_id', $distance->id) as $category)
                                    <span class="bg-gray-50 text-gray-600 px-3 py-1.5 rounded-xl text-xs font-bold border border-gray-100">
                                        {{ $category->name }}
                                    </span>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>

            <!-- Right Column: Sidebar Actions -->
            <div class="space-y-8">
                
                <!-- Main CTA Card -->
                <div class="bg-white p-8 rounded-[2.5rem] shadow-2xl border border-gray-100 sticky top-8">
                    @php $isPast = now()->startOfDay()->gt($event->date); @endphp

                    <div class="text-center mb-8">
                        <h3 class="text-2xl font-black text-gray-900 uppercase tracking-tighter">
                            {{ $isPast ? 'Evento Finalizado' : 'Inscripciones' }}
                        </h3>
                        @if($event->organizer)
                            <p class="text-[10px] font-black text-red-600 uppercase tracking-[0.2em] mt-2">Organiza: {{ $event->organizer }}</p>
                        @endif
                    </div>

                    @if($isPast)
                        @if($event->results_url)
                            <a href="{{ $event->results_url }}" target="_blank" class="block w-full bg-gradient-to-b from-red-500 to-red-700 hover:from-red-600 hover:to-red-800 text-white text-center font-black py-5 rounded-2xl transition-all shadow-xl shadow-red-500/40 uppercase tracking-widest text-xs mb-4">
                                Ver Resultados
                            </a>
                        @else
                            <div class="py-5 bg-gray-50 border-2 border-dashed border-gray-200 rounded-2xl text-center text-gray-400 font-bold uppercase text-xs tracking-widest">
                                Resultados Pendientes
                            </div>
                        @endif
                    @else
                        @if($event->registration_type === 'external')
                            <a href="{{ $event->external_registration_url }}" target="_blank" class="block w-full bg-gradient-to-b from-blue-500 to-blue-700 hover:from-blue-600 hover:to-blue-800 text-white text-center font-black py-5 rounded-2xl transition-all shadow-xl shadow-blue-500/40 mb-4 flex items-center justify-center text-xs uppercase tracking-widest">
                                Registrarme Externo
                                <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                            </a>
                        @else
                            <a href="{{ route('events.register', ['event' => $event]) }}" class="block w-full bg-gradient-to-b from-red-500 to-red-700 hover:from-red-600 hover:to-red-800 text-white text-center font-black py-5 rounded-2xl transition-all shadow-xl shadow-red-500/40 mb-4 flex items-center justify-center text-xs uppercase tracking-widest">
                                Inscribirme Ahora
                                <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                            </a>
                        @endif

                        <a href="{{ route('event.inscriptos', $event) }}" class="block w-full mt-4 bg-white border border-gray-200 text-gray-900 text-center font-bold py-4 rounded-2xl hover:bg-gray-50 transition-all uppercase tracking-widest text-[10px] flex items-center justify-center">
                            <svg class="w-3 h-3 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 005.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                            Ver Inscriptos
                        </a>
                    @endif

                    <div class="mt-10 space-y-5 border-t border-gray-50 pt-8">
                        <div class="flex items-center justify-between">
                            <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Largada</span>
                            <span class="text-sm font-bold text-gray-900">{{ $event->start_time ?? '--:--' }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Cierre</span>
                            <span class="text-sm font-bold text-gray-900">
                                {{ $event->registration_deadline ? $event->registration_deadline->format('d/m, H:i') : 'S/D' }}
                            </span>
                        </div>
                        <div class="pt-4">
                            @if($event->google_maps_url)
                                <a href="{{ $event->google_maps_url }}" target="_blank" class="w-full bg-gray-50 text-gray-600 px-4 py-3 rounded-xl text-[10px] font-black uppercase tracking-widest flex items-center justify-center border border-gray-100 hover:bg-gray-100 transition-colors">
                                    <svg class="w-4 h-4 mr-2 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                    Google Maps
                                </a>
                            @endif
                        </div>
                    </div>

                    <!-- Share Mini Card -->
                    <div class="mt-10 pt-8 border-t border-gray-50">
                        <p class="text-[9px] font-black text-gray-300 uppercase tracking-[0.3em] text-center mb-4">Compartir</p>
                        <div class="flex justify-center gap-3">
                            <button class="w-10 h-10 rounded-full bg-gray-50 flex items-center justify-center text-gray-400 hover:text-red-600 hover:bg-red-50 transition-all border border-gray-100">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                            </button>
                            <button class="w-10 h-10 rounded-full bg-gray-50 flex items-center justify-center text-gray-400 hover:text-red-600 hover:bg-red-50 transition-all border border-gray-100">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/></svg>
                            </button>
                            <button class="w-10 h-10 rounded-full bg-gray-50 flex items-center justify-center text-gray-400 hover:text-red-600 hover:bg-red-50 transition-all border border-gray-100">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
