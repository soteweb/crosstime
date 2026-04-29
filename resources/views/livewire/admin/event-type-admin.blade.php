<div>
    <x-slot name="title">Tipos de Evento - Itapúa Admin</x-slot>

    <div class="mb-8 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-xl font-bold text-gray-900 border-l-4 border-red-600 pl-4 uppercase tracking-tighter">Tipos de Evento</h1>
            <p class="text-[10px] text-gray-400 ml-4 font-bold uppercase tracking-widest mt-0.5">Gestión de etiquetas</p>
        </div>
        @if(!$isFormOpen)
        <button wire:click="create()" class="bg-gray-900 hover:bg-red-600 text-white text-xs font-black py-2 px-6 rounded-xl flex items-center transition-all shadow-xl uppercase tracking-widest">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            Nuevo Tipo
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
                {{ $typeId ? 'Editar Tipo de Evento' : 'Nuevo Tipo de Evento' }}
            </h3>
            <button wire:click="closeForm" class="text-gray-400 hover:text-white transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>
        <form wire:submit.prevent="store" class="p-8">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-xs font-black text-gray-400 uppercase mb-2 tracking-widest">Nombre</label>
                    <input type="text" wire:model="name" class="w-full rounded-xl border-gray-200 font-bold focus:border-red-600 focus:ring-red-600 p-3 bg-gray-50">
                    @error('name') <span class="text-red-500 text-[10px] font-bold mt-1 uppercase">{{ $message }}</span>@enderror
                </div>
                <div>
                    <label class="block text-xs font-black text-gray-400 uppercase mb-2 tracking-widest">Slug (Opcional)</label>
                    <input type="text" wire:model="slug" class="w-full rounded-xl border-gray-200 font-bold focus:border-red-600 focus:ring-red-600 p-3 bg-gray-50">
                    @error('slug') <span class="text-red-500 text-[10px] font-bold mt-1 uppercase">{{ $message }}</span>@enderror
                </div>
                <div class="md:col-span-2">
                    <label class="block text-xs font-black text-gray-400 uppercase mb-2 tracking-widest">Color del Badge</label>
                    <div class="flex items-center gap-4">
                        <input type="color" wire:model="color" class="h-14 w-14 border-0 rounded-xl cursor-pointer bg-transparent">
                        <input type="text" wire:model="color" class="flex-1 rounded-xl border-gray-200 font-mono font-bold focus:border-red-600 focus:ring-red-600 p-3 bg-gray-50 uppercase" placeholder="#E30613">
                    </div>
                    @error('color') <span class="text-red-500 text-[10px] font-bold mt-1 uppercase">{{ $message }}</span>@enderror
                </div>
            </div>
            <div class="mt-6 pt-4 border-t border-gray-100 flex gap-3">
                <button type="button" wire:click="closeForm" class="flex-1 py-2 text-gray-500 font-bold uppercase tracking-widest text-[10px] hover:text-gray-700 transition-colors">
                    Cancelar
                </button>
                <button type="submit" class="flex-[2] py-2 bg-red-600 text-white font-black rounded-xl hover:bg-red-700 transition-all shadow-lg uppercase tracking-widest text-xs">
                    Guardar
                </button>
            </div>
        </form>
    </div>
    @endif

    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">Nombre</th>
                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">Visualización</th>
                    <th class="px-6 py-4 text-right text-xs font-bold text-gray-400 uppercase tracking-wider">Acciones</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($types as $type)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4">
                            <div class="text-sm font-bold text-gray-900">{{ $type->name }}</div>
                            <div class="text-[10px] text-gray-400 font-mono uppercase">{{ $type->slug }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-4 py-1.5 inline-flex text-[10px] font-black rounded-full text-white uppercase tracking-widest shadow-sm" style="background-color: {{ $type->color ?? '#f97316' }}">
                                {{ $type->name }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex justify-end items-center gap-2">
                                <button wire:click="edit({{ $type->id }})" class="p-2 text-gray-400 hover:text-blue-600 transition-colors">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                </button>
                                <button onclick="confirm('¿Estás seguro? Los eventos vinculados perderán esta categoría.') || event.stopImmediatePropagation()" wire:click="delete({{ $type->id }})" class="p-2 text-gray-400 hover:text-red-600 transition-colors">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="px-6 py-20 text-center">
                            <p class="text-gray-400 font-bold uppercase tracking-widest text-xs">No hay tipos de eventos registrados.</p>
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
