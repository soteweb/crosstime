<div>
    <x-slot name="title">Gestión de Eventos - Itapúa Admin</x-slot>

    <!-- Header Section -->
    <div class="mb-6 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-xl font-bold text-gray-900 border-l-4 border-red-600 pl-4 uppercase tracking-tighter">Gestión de Eventos</h1>
            <p class="text-[10px] text-gray-400 ml-4 font-bold uppercase tracking-widest mt-0.5">Control de competencias</p>
        </div>
        <a href="{{ route('admin.events.create') }}" class="bg-gray-900 hover:bg-red-600 text-white text-xs font-black py-2 px-6 rounded-xl flex items-center transition-all shadow-xl uppercase tracking-widest">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            Nuevo Evento
        </a>
    </div>

    <!-- Filters & Search -->
    <div class="bg-white p-3 rounded-xl shadow-sm border border-gray-200 mb-6 flex flex-col md:flex-row gap-4 items-center">
        <div class="relative flex-1 w-full">
            <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            </span>
            <input wire:model.live="search" type="text" placeholder="Buscar eventos por título..." class="block w-full pl-10 pr-3 py-1.5 border border-gray-300 rounded-lg leading-5 bg-gray-50 placeholder-gray-500 focus:outline-none focus:ring-1 focus:ring-red-600 focus:border-red-600 text-xs transition-all">
        </div>
    </div>

    @if (session()->has('message'))
        <div class="bg-green-50 border-l-4 border-green-500 text-green-700 px-6 py-4 rounded-r-xl relative mb-6 shadow-sm" role="alert">
            <span class="block sm:inline font-bold">{{ session('message') }}</span>
        </div>
    @endif

    <!-- Events Table -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-4 text-left text-[10px] font-bold text-gray-400 uppercase tracking-wider">Evento</th>
                        <th class="px-6 py-4 text-left text-[10px] font-bold text-gray-400 uppercase tracking-wider">Fecha / Lugar</th>
                        <th class="px-6 py-4 text-left text-[10px] font-bold text-gray-400 uppercase tracking-wider">Tipo / Inscripción</th>
                        <th class="px-6 py-4 text-right text-[10px] font-bold text-gray-400 uppercase tracking-wider">Acciones</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($events as $event)
                        <tr class="hover:bg-gray-50 transition-colors border-l-4 border-transparent hover:border-red-600">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="h-10 w-10 flex-shrink-0 rounded-lg overflow-hidden bg-gray-100 border border-gray-200">
                                        @if($event->featured_image)
                                            <img class="h-full w-full object-cover" src="{{ asset('storage/' . $event->featured_image) }}" alt="">
                                        @else
                                            <div class="h-full w-full flex items-center justify-center text-gray-300">
                                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-bold text-gray-900">{{ $event->title }}</div>
                                        <div class="text-[9px] text-gray-400 font-bold uppercase tracking-tighter">ID: #E{{ $event->id }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-xs text-gray-900 font-bold italic">{{ \Carbon\Carbon::parse($event->date)->format('d/m/Y') }}</div>
                                <div class="text-[9px] text-gray-400 flex items-center uppercase font-bold tracking-tighter">
                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                    {{ $event->location_address }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($event->eventType)
                                    <div class="mb-1">
                                        <span class="px-2 py-0.5 inline-flex text-[9px] font-black rounded text-white shadow-sm uppercase tracking-tighter" style="background-color: {{ $event->eventType->color ?? '#f97316' }}">
                                            {{ $event->eventType->name }}
                                        </span>
                                    </div>
                                @endif
                                <span class="px-2 py-0.5 border {{ $event->registration_type === 'external' ? 'border-blue-200 text-blue-700 bg-blue-50' : 'border-green-200 text-green-700 bg-green-50' }} text-[9px] font-black rounded uppercase tracking-tighter">
                                    {{ $event->registration_type === 'external' ? 'Externa' : 'Local' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right">
                                <div class="flex justify-end items-center gap-2">
                                    <a href="{{ route('admin.events.edit', $event->id) }}" class="p-2 text-gray-400 hover:text-blue-600 transition-colors">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                    </a>
                                    <button wire:click="confirmDelete({{ $event->id }})" class="p-2 text-gray-400 hover:text-red-600 transition-colors">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="px-6 py-20 text-center text-gray-400 font-bold uppercase tracking-widest text-xs">No hay eventos.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Delete Modal -->
    @if($isDeleteModalOpen)
    <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-black/60 backdrop-blur-sm transition-opacity" aria-hidden="true" wire:click="$set('isDeleteModalOpen', false)"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div class="relative z-10 inline-block align-bottom bg-white rounded-3xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <div class="p-8">
                    <div class="sm:flex sm:items-start">
                        <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-2xl bg-red-50 sm:mx-0 sm:h-10 sm:w-10 text-red-600">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                            <h3 class="text-xl font-black text-gray-900 uppercase tracking-tighter" id="modal-title">Eliminar Evento</h3>
                            <p class="text-sm text-gray-500 font-medium mt-2">Esta acción es permanente y borrará todas las inscripciones asociadas.</p>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 p-6 flex flex-row-reverse gap-3">
                    <button wire:click="deleteEvent" class="px-6 py-3 bg-red-600 text-white font-black rounded-2xl hover:bg-red-700 transition-all text-xs uppercase tracking-widest">Eliminar</button>
                    <button wire:click="$set('isDeleteModalOpen', false)" class="px-6 py-3 bg-white border border-gray-200 text-gray-500 font-bold rounded-2xl hover:bg-gray-100 transition-all text-xs uppercase tracking-widest">Cancelar</button>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
