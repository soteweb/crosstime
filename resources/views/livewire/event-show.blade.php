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
                    <div class="prose prose-red max-w-none text-gray-600 leading-relaxed font-medium overflow-x-auto break-words">
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
                            <div class="space-y-4">
                                @php
                                    $distCats = $event->categories->where('event_distance_id', $distance->id)->sortBy('min_age');
                                    $males = $distCats->filter(fn($c) => strtoupper($c->gender_restriction) === 'M');
                                    $females = $distCats->filter(fn($c) => strtoupper($c->gender_restriction) === 'F');
                                    $mixed = $distCats->filter(fn($c) => !in_array(strtoupper($c->gender_restriction), ['M', 'F']));
                                @endphp

                                <div class="grid grid-cols-1 {{ ($males->count() > 0 && $females->count() > 0) ? 'sm:grid-cols-2' : '' }} gap-6">
                                    @if($males->count() > 0)
                                    <div>
                                        <div class="flex items-center text-blue-600 mb-3 border-b border-blue-100 pb-2">
                                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                            <span class="text-[10px] font-black uppercase tracking-widest">Masculino</span>
                                        </div>
                                        <div class="flex flex-col gap-2">
                                            @foreach($males as $category)
                                                <div class="bg-blue-50/50 text-blue-900 px-3 py-2 rounded-xl text-[11px] sm:text-xs font-bold border border-blue-100 flex flex-col 2xl:flex-row items-start 2xl:items-center justify-between gap-1.5 hover:bg-blue-100 transition-colors">
                                                    <span class="leading-tight">{{ $category->name }}</span>
                                                    @if($category->min_age || $category->max_age)
                                                        <span class="text-[9px] font-black tracking-widest text-blue-600 bg-white px-2 py-1 rounded-lg shadow-sm whitespace-nowrap">
                                                            {{ $category->min_age ?? '0' }} A {{ $category->max_age ?? '+' }} AÑOS
                                                        </span>
                                                    @endif
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                    @endif

                                    @if($females->count() > 0)
                                    <div>
                                        <div class="flex items-center text-pink-600 mb-3 border-b border-pink-100 pb-2">
                                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                            <span class="text-[10px] font-black uppercase tracking-widest">Femenino</span>
                                        </div>
                                        <div class="flex flex-col gap-2">
                                            @foreach($females as $category)
                                                <div class="bg-pink-50/50 text-pink-900 px-3 py-2 rounded-xl text-[11px] sm:text-xs font-bold border border-pink-100 flex flex-col 2xl:flex-row items-start 2xl:items-center justify-between gap-1.5 hover:bg-pink-100 transition-colors">
                                                    <span class="leading-tight">{{ $category->name }}</span>
                                                    @if($category->min_age || $category->max_age)
                                                        <span class="text-[9px] font-black tracking-widest text-pink-600 bg-white px-2 py-1 rounded-lg shadow-sm whitespace-nowrap">
                                                            {{ $category->min_age ?? '0' }} A {{ $category->max_age ?? '+' }} AÑOS
                                                        </span>
                                                    @endif
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                    @endif
                                </div>

                                @if($mixed->count() > 0)
                                <div class="{{ ($males->count() > 0 || $females->count() > 0) ? 'mt-4 pt-4 border-t border-gray-100' : '' }}">
                                    <div class="flex items-center text-gray-600 mb-3">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                                        <span class="text-[10px] font-black uppercase tracking-widest">General / Sin Especificar</span>
                                    </div>
                                    <div class="flex flex-wrap gap-2">
                                        @foreach($mixed as $category)
                                            <div class="bg-gray-50 text-gray-700 px-3 py-2 rounded-xl text-xs font-bold border border-gray-200 flex items-center">
                                                <span>{{ $category->name }}</span>
                                                @if($category->min_age || $category->max_age)
                                                    <span class="ml-2 text-[9px] font-black tracking-widest text-gray-500 bg-white px-2 py-1 rounded-lg shadow-sm">
                                                        {{ $category->min_age ?? '0' }} A {{ $category->max_age ?? '+' }} AÑOS
                                                    </span>
                                                @endif
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                                @endif
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
                                Inscribirme Ahora
                                <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                            </a>
                        @else
                            <a href="{{ route('events.register', ['event' => $event]) }}" class="block w-full bg-gradient-to-b from-red-500 to-red-700 hover:from-red-600 hover:to-red-800 text-white text-center font-black py-5 rounded-2xl transition-all shadow-xl shadow-red-500/40 mb-4 flex items-center justify-center text-xs uppercase tracking-widest">
                                Inscribirme Ahora
                                <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                            </a>
                        @endif

                        @if($event->external_inscribed_url)
                            <a href="{{ $event->external_inscribed_url }}" target="_blank" class="block w-full mt-4 bg-white border border-blue-200 text-blue-900 text-center font-bold py-4 rounded-2xl hover:bg-blue-50 transition-all uppercase tracking-widest text-[10px] flex items-center justify-center shadow-sm">
                                <svg class="w-3 h-3 mr-2 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                                Ver Inscriptos
                            </a>
                        @else
                            <a href="{{ route('event.inscriptos', $event) }}" class="block w-full mt-4 bg-white border border-gray-200 text-gray-900 text-center font-bold py-4 rounded-2xl hover:bg-gray-50 transition-all uppercase tracking-widest text-[10px] flex items-center justify-center">
                                <svg class="w-3 h-3 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 005.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                                Ver Inscriptos
                            </a>
                        @endif
                    @endif

                    @if(!$isPast)
                    <div class="mt-10 space-y-5 border-t border-gray-50 pt-8">
                        <div class="flex items-center justify-between">
                            <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Largada</span>
                            <span class="text-sm font-bold text-gray-900">{{ $event->start_time ?? '--:--' }} hs</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Cierre</span>
                            <span class="text-sm font-bold text-gray-900">
                                {{ $event->registration_deadline ? $event->registration_deadline->format('d/m, H:i') : 'S/D' }} hs
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
                    @endif

                    <!-- Share Mini Card -->
                    <div class="mt-10 pt-8 border-t border-gray-50">
                        <p class="text-[9px] font-black text-gray-300 uppercase tracking-[0.3em] text-center mb-4">Compartir</p>
                        <div class="flex justify-center gap-3">
                            <!-- Facebook -->
                            <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(request()->fullUrl()) }}" target="_blank" 
                               class="w-10 h-10 rounded-full bg-gray-50 flex items-center justify-center text-gray-400 hover:text-blue-600 hover:bg-blue-50 transition-all border border-gray-100">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                            </a>
                            <!-- Instagram -->
                            <a href="https://instagram.com" target="_blank" 
                               class="w-10 h-10 rounded-full bg-gray-50 flex items-center justify-center text-gray-400 hover:text-pink-600 hover:bg-pink-50 transition-all border border-gray-100">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
                            </a>
                            <!-- WhatsApp -->
                            <a href="https://wa.me/?text={{ urlencode($event->title . ' ' . request()->fullUrl()) }}" target="_blank" 
                               class="w-10 h-10 rounded-full bg-gray-50 flex items-center justify-center text-gray-400 hover:text-green-600 hover:bg-green-50 transition-all border border-gray-100">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
