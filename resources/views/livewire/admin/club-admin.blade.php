<div>
    <x-slot name="title">Gestión de Clubes - Itapúa Admin</x-slot>

    <div class="mb-8 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-xl font-bold text-gray-900 border-l-4 border-red-600 pl-4 uppercase tracking-tighter">Gestión de Clubes</h1>
            <p class="text-[10px] text-gray-400 ml-4 font-bold uppercase tracking-widest mt-0.5">Base de datos de instituciones</p>
        </div>
        @if(!$isFormOpen)
        <button wire:click="create()" class="bg-gray-900 hover:bg-red-600 text-white text-xs font-black py-2 px-6 rounded-xl flex items-center transition-all shadow-xl uppercase tracking-widest">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            Nuevo Club
        </button>
        @endif
    </div>

    @if (session()->has('message'))
        <div class="bg-green-50 border-l-4 border-green-500 text-green-700 px-6 py-4 rounded-r-xl relative mb-6 shadow-sm animate-fadeIn" role="alert">
            <span class="block sm:inline font-bold">{{ session('message') }}</span>
        </div>
    @endif

    @if($isFormOpen)
    <div class="bg-white rounded-3xl shadow-2xl border border-gray-100 overflow-hidden mb-8 animate-fadeIn">
        <div class="bg-gray-900 p-6 flex justify-between items-center text-white">
            <h3 class="text-xl font-black uppercase tracking-tighter">
                {{ $clubId ? 'Editar Club' : 'Nuevo Club' }}
            </h3>
            <button wire:click="closeForm" class="text-gray-400 hover:text-white transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>
        <form wire:submit.prevent="store" class="p-8">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="md:col-span-2">
                    <label class="block text-xs font-black text-gray-400 uppercase mb-2 tracking-widest">Nombre del Club</label>
                    <input type="text" wire:model="name" class="w-full rounded-xl border-gray-200 font-bold focus:border-red-600 focus:ring-red-600 p-3 bg-gray-50">
                    @error('name') <span class="text-red-500 text-[10px] font-bold mt-1 uppercase">{{ $message }}</span>@enderror
                </div>
                <div>
                    <label class="block text-xs font-black text-gray-400 uppercase mb-2 tracking-widest">Ciudad / Localidad</label>
                    <input type="text" wire:model="city" class="w-full rounded-xl border-gray-200 font-bold focus:border-red-600 focus:ring-red-600 p-3 bg-gray-50">
                    @error('city') <span class="text-red-500 text-[10px] font-bold mt-1 uppercase">{{ $message }}</span>@enderror
                </div>
                <div>
                    <label class="block text-xs font-black text-gray-400 uppercase mb-2 tracking-widest">Estado</label>
                    <select wire:model="is_active" class="w-full rounded-xl border-gray-200 font-bold focus:border-red-600 focus:ring-red-600 p-3 bg-gray-50">
                        <option value="1">Activo</option>
                        <option value="0">Inactivo</option>
                    </select>
                </div>
                <div class="md:col-span-2">
                    <label class="block text-xs font-black text-gray-400 uppercase mb-2 tracking-widest">Logo (Opcional)</label>
                    <div class="flex items-center gap-6">
                        @if ($newLogo)
                            <img src="{{ $newLogo->temporaryUrl() }}" class="w-20 h-20 rounded-2xl object-cover border-2 border-red-600">
                        @elseif ($logo)
                            <img src="{{ asset('storage/' . $logo) }}" class="w-20 h-20 rounded-2xl object-cover border border-gray-200">
                        @else
                            <div class="w-20 h-20 rounded-2xl bg-gray-100 flex items-center justify-center text-gray-300">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            </div>
                        @endif
                        <input type="file" wire:model="newLogo" class="text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-black file:bg-gray-900 file:text-white hover:file:bg-red-600 transition-all cursor-pointer">
                    </div>
                    @error('newLogo') <span class="text-red-500 text-[10px] font-bold mt-1 uppercase">{{ $message }}</span>@enderror
                </div>
            </div>
            <div class="mt-6 pt-4 border-t border-gray-100 flex gap-3">
                <button type="button" wire:click="closeForm" class="flex-1 py-2 text-gray-500 font-bold uppercase tracking-widest text-[10px] hover:text-gray-700 transition-colors">
                    Cancelar
                </button>
                <button type="submit" class="flex-[2] py-2 bg-red-600 text-white font-black rounded-xl hover:bg-red-700 transition-all shadow-lg uppercase tracking-widest text-xs">
                    Guardar Club
                </button>
            </div>
        </form>
    </div>
    @endif

    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">Club</th>
                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">Ubicación</th>
                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">Estado</th>
                    <th class="px-6 py-4 text-right text-xs font-bold text-gray-400 uppercase tracking-wider">Acciones</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($clubs as $club)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-4">
                                @if($club->logo)
                                    <img src="{{ asset('storage/' . $club->logo) }}" class="w-10 h-10 rounded-lg object-cover">
                                @else
                                    <div class="w-10 h-10 rounded-lg bg-gray-100 flex items-center justify-center text-gray-300">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                                    </div>
                                @endif
                                <div class="text-sm font-bold text-gray-900">{{ $club->name }}</div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-xs font-medium text-gray-600">{{ $club->city ?: 'No especificada' }}</div>
                        </td>
                        <td class="px-6 py-4">
                            @if($club->is_active)
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-black bg-green-100 text-green-700 uppercase tracking-widest">Activo</span>
                            @else
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-black bg-gray-100 text-gray-500 uppercase tracking-widest">Inactivo</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex justify-end items-center gap-2">
                                <button wire:click="edit({{ $club->id }})" class="p-2 text-gray-400 hover:text-blue-600 transition-colors">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                </button>
                                <button onclick="confirm('¿Estás seguro? Los atletas vinculados perderán su relación con este club.') || event.stopImmediatePropagation()" wire:click="delete({{ $club->id }})" class="p-2 text-gray-400 hover:text-red-600 transition-colors">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-6 py-20 text-center">
                            <p class="text-gray-400 font-bold uppercase tracking-widest text-xs">No hay clubes registrados.</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<style>
    .animate-fadeIn { animation: fadeIn 0.3s ease-out; }
    @keyframes fadeIn { from { opacity: 0; transform: translateY(-10px); } to { opacity: 1; transform: translateY(0); } }
</style>
