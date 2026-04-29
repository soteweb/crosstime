<div>
    <x-slot name="title">Gestión de Menús - Itapúa Admin</x-slot>

    <div class="mb-8 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-xl font-bold text-gray-900 border-l-4 border-red-600 pl-4 uppercase tracking-tighter">Menús Dinámicos</h1>
            <p class="text-[10px] text-gray-400 ml-4 font-bold uppercase tracking-widest mt-0.5">Gestión de enlaces</p>
        </div>
        @if(!$isFormOpen)
        <button wire:click="create()" class="bg-gray-900 hover:bg-red-600 text-white text-xs font-black py-2 px-6 rounded-xl flex items-center transition-all shadow-xl uppercase tracking-widest">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            Nuevo Enlace
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
                {{ $itemId ? 'Editar Enlace' : 'Nuevo Enlace' }}
            </h3>
            <button wire:click="closeForm" class="text-gray-400 hover:text-white transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>
        <form wire:submit.prevent="store" class="p-8">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-xs font-black text-gray-400 uppercase mb-2 tracking-widest">Nombre Público</label>
                    <input type="text" wire:model="name" class="w-full rounded-xl border-gray-200 font-bold focus:border-red-600 focus:ring-red-600 p-3 bg-gray-50">
                    @error('name') <span class="text-red-500 text-[10px] font-bold mt-1 uppercase">{{ $message }}</span>@enderror
                </div>
                <div>
                    <label class="block text-xs font-black text-gray-400 uppercase mb-2 tracking-widest">URL o Ruta</label>
                    <input type="text" wire:model="url" class="w-full rounded-xl border-gray-200 font-bold focus:border-red-600 focus:ring-red-600 p-3 bg-gray-50 font-mono text-blue-600">
                    @error('url') <span class="text-red-500 text-[10px] font-bold mt-1 uppercase">{{ $message }}</span>@enderror
                </div>
                <div>
                    <label class="block text-xs font-black text-gray-400 uppercase mb-2 tracking-widest">Ubicación</label>
                    <select wire:model="placement" class="w-full rounded-xl border-gray-200 font-bold focus:border-red-600 focus:ring-red-600 p-3 bg-gray-50">
                        <option value="header">Header (Arriba)</option>
                        <option value="footer">Footer (Abajo)</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-black text-gray-400 uppercase mb-2 tracking-widest">Orden</label>
                    <input type="number" wire:model="order" class="w-full rounded-xl border-gray-200 font-bold focus:border-red-600 focus:ring-red-600 p-3 bg-gray-50">
                </div>
                <div class="md:col-span-2">
                    <label class="flex items-center cursor-pointer group">
                        <input type="checkbox" wire:model="target" value="_blank" class="w-5 h-5 text-red-600 border-gray-300 rounded focus:ring-red-600">
                        <span class="ml-3 text-sm font-bold text-gray-700 group-hover:text-red-600 transition-colors uppercase tracking-widest">Abrir en pestaña nueva</span>
                    </label>
                </div>
            </div>
            <div class="mt-6 pt-4 border-t border-gray-100 flex gap-3">
                <button type="button" wire:click="closeForm" class="flex-1 py-3 text-gray-500 font-bold uppercase tracking-widest text-[10px]">Cancelar</button>
                <button type="submit" class="flex-[2] py-3 bg-red-600 text-white font-black rounded-xl hover:bg-red-700 transition-all shadow-lg uppercase tracking-widest text-xs">Guardar Enlace</button>
            </div>
        </form>
    </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        <!-- Header Items -->
        <div class="bg-white rounded-3xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="bg-gray-50 px-8 py-6 border-b border-gray-200 flex justify-between items-center">
                <h3 class="text-sm font-black text-gray-900 uppercase tracking-widest">Header</h3>
                <span class="bg-white border border-gray-200 text-[10px] font-black px-3 py-1 rounded-full text-gray-500 uppercase">{{ $headerItems->count() }} ítems</span>
            </div>
            <ul class="divide-y divide-gray-100">
                @forelse($headerItems as $item)
                    <li class="px-8 py-4 hover:bg-gray-50 transition-colors flex justify-between items-center group">
                        <div>
                            <p class="text-sm font-bold text-gray-900">{{ $item->name }}</p>
                            <p class="text-[10px] text-gray-400 font-mono">{{ $item->url }}</p>
                        </div>
                        <div class="flex gap-2">
                            <button wire:click="edit({{ $item->id }})" class="p-2 text-gray-400 hover:text-blue-600 transition-colors"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg></button>
                            <button onclick="confirm('¿Eliminar enlace?') || event.stopImmediatePropagation()" wire:click="delete({{ $item->id }})" class="p-2 text-gray-400 hover:text-red-600 transition-colors"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg></button>
                        </div>
                    </li>
                @empty
                    <li class="p-12 text-center text-gray-400 font-bold uppercase tracking-widest text-xs">Vacío</li>
                @endforelse
            </ul>
        </div>

        <!-- Footer Items -->
        <div class="bg-white rounded-3xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="bg-gray-50 px-8 py-6 border-b border-gray-200 flex justify-between items-center">
                <h3 class="text-sm font-black text-gray-900 uppercase tracking-widest">Footer</h3>
                <span class="bg-white border border-gray-200 text-[10px] font-black px-3 py-1 rounded-full text-gray-500 uppercase">{{ $footerItems->count() }} ítems</span>
            </div>
            <ul class="divide-y divide-gray-100">
                @forelse($footerItems as $item)
                    <li class="px-8 py-4 hover:bg-gray-50 transition-colors flex justify-between items-center group">
                        <div>
                            <p class="text-sm font-bold text-gray-900">{{ $item->name }}</p>
                            <p class="text-[10px] text-gray-400 font-mono">{{ $item->url }}</p>
                        </div>
                        <div class="flex gap-2">
                            <button wire:click="edit({{ $item->id }})" class="p-2 text-gray-400 hover:text-blue-600 transition-colors"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg></button>
                            <button onclick="confirm('¿Eliminar enlace?') || event.stopImmediatePropagation()" wire:click="delete({{ $item->id }})" class="p-2 text-gray-400 hover:text-red-600 transition-colors"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg></button>
                        </div>
                    </li>
                @empty
                    <li class="p-12 text-center text-gray-400 font-bold uppercase tracking-widest text-xs">Vacío</li>
                @endforelse
            </ul>
        </div>
    </div>
</div>

<style>
    .animate-fadeIn { animation: fadeIn 0.3s ease-out; }
    @keyframes fadeIn { from { opacity: 0; transform: translateY(-10px); } to { opacity: 1; transform: translateY(0); } }
</style>
