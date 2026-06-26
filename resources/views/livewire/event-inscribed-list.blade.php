<div class="py-12 bg-gray-50 min-h-screen">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <a href="{{ route('event.show', $event) }}" class="inline-flex items-center text-red-600 font-bold hover:text-red-700 transition-colors mb-4">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Volver al Evento
            </a>
            <div class="flex flex-col md:flex-row md:items-end justify-between gap-4">
                <div>
                    <h1 class="text-4xl font-bold text-gray-900 leading-tight">Listado de Inscriptos</h1>
                    <p class="text-xl text-gray-500 font-medium">{{ $event->title }}</p>
                </div>
                <div class="bg-white px-6 py-3 rounded-2xl shadow-sm border border-gray-100">
                    <span class="block text-xs font-bold text-gray-400 uppercase tracking-widest">Total Participantes</span>
                    <span class="text-2xl font-bold text-red-600">{{ $registrations->count() }}</span>
                </div>
            </div>
        </div>

        <!-- Table -->
        <div class="bg-white rounded-3xl shadow-xl shadow-red-100/50 border border-gray-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-900 text-white">
                            <th class="p-5 text-xs font-bold uppercase tracking-widest">Dorsal</th>
                            <th class="p-5 text-xs font-bold uppercase tracking-widest">Estado</th>
                            <th class="p-5 text-xs font-bold uppercase tracking-widest">Nombre y Apellido</th>
                            <th class="p-5 text-xs font-bold uppercase tracking-widest text-center">Género</th>
                            <th class="p-5 text-xs font-bold uppercase tracking-widest">Categoría</th>
                            <th class="p-5 text-xs font-bold uppercase tracking-widest">Localidad</th>
                            <th class="p-5 text-xs font-bold uppercase tracking-widest">Club</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($registrations as $index => $reg)
                            <tr class="hover:bg-orange-50/30 transition-colors">
                                <td class="p-5 font-black text-gray-400 text-sm">
                                    {{ $reg->bib_number ? '#' . $reg->bib_number : '-' }}
                                </td>
                                <td class="p-5">
                                    @if($reg->status === 'pre_inscribed')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-yellow-100 text-yellow-800 uppercase border border-yellow-200">
                                            Pre Inscripto
                                        </span>
                                    @elseif($reg->status === 'inscribed')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-blue-100 text-blue-800 uppercase border border-blue-100">
                                            Inscripto
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-green-100 text-green-800 uppercase border border-green-200">
                                            ACREDITADO {{ $reg->bib_number ? '#' . $reg->bib_number : '' }}
                                        </span>
                                    @endif
                                </td>
                                <td class="p-5">
                                    <div class="text-sm font-bold text-gray-900 uppercase">{{ $reg->athlete->name }} {{ $reg->athlete->last_name }}</div>
                                </td>
                                <td class="p-5 text-center">
                                    <span class="text-xs font-black {{ $reg->athlete->gender === 'M' ? 'text-blue-600' : 'text-pink-600' }}">
                                        {{ $reg->athlete->gender }}
                                    </span>
                                </td>
                                <td class="p-5">
                                    <span class="text-xs font-bold text-red-600 uppercase">{{ $reg->category->name }}</span>
                                </td>
                                <td class="p-5">
                                    <span class="text-sm text-gray-600 font-medium">{{ $reg->athlete->city }}</span>
                                </td>
                                <td class="p-5 text-sm text-gray-500 font-medium">
                                    {{ $reg->athlete->club ?? '-' }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="p-20 text-center">
                                    <div class="flex flex-col items-center">
                                        <svg class="w-16 h-16 text-gray-200 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 005.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                                        <p class="text-gray-500 font-bold">Aún no hay inscriptos para este evento.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
