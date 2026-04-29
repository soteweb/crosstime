<div>
    <x-slot name="title">Gestión de Páginas (CMS) - Itapúa Admin</x-slot>

    <div wire:ignore>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/6.8.3/tinymce.min.js" referrerpolicy="origin"></script>
    </div>

    <div class="mb-6 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-xl font-bold text-gray-900 border-l-4 border-red-600 pl-4 uppercase tracking-tighter">Páginas CMS</h1>
            <p class="text-[10px] text-gray-400 ml-4 font-bold uppercase tracking-widest mt-0.5">Contenido dinámico</p>
        </div>
        <a href="{{ route('admin.pages.create') }}" class="bg-gray-900 hover:bg-red-600 text-white text-xs font-black py-2 px-6 rounded-xl flex items-center transition-all shadow-xl uppercase tracking-widest">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            Nueva Página
        </a>
    </div>

    @if (session()->has('message'))
        <div class="bg-green-50 border-l-4 border-green-500 text-green-700 px-6 py-4 rounded-r-xl relative mb-6 shadow-sm" role="alert">
            <span class="block sm:inline font-bold">{{ session('message') }}</span>
        </div>
    @endif

    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-4 text-left text-[10px] font-bold text-gray-400 uppercase tracking-wider">Título</th>
                    <th class="px-6 py-4 text-left text-[10px] font-bold text-gray-400 uppercase tracking-wider">Ruta</th>
                    <th class="px-6 py-4 text-center text-[10px] font-bold text-gray-400 uppercase tracking-wider">Estado</th>
                    <th class="px-6 py-4 text-right text-[10px] font-bold text-gray-400 uppercase tracking-wider">Acciones</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($pages as $page)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-bold text-gray-900">{{ $page->title }}</div>
                            <div class="text-[9px] text-gray-400 font-bold uppercase tracking-tighter">{{ $page->created_at->format('d M, Y') }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-xs text-blue-600 font-mono">
                            /{{ $page->slug }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            @if($page->is_active)
                                <span class="px-2 py-0.5 inline-flex text-[9px] font-black rounded bg-green-50 text-green-700 border border-green-100 uppercase">Público</span>
                            @else
                                <span class="px-2 py-0.5 inline-flex text-[9px] font-black rounded bg-red-50 text-red-700 border border-red-100 uppercase">Oculto</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right">
                            <div class="flex justify-end items-center gap-2">
                                <a href="/{{ $page->slug }}" target="_blank" class="p-2 text-gray-400 hover:text-gray-900 transition-colors">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                </a>
                                <a href="{{ route('admin.pages.edit', $page->id) }}" class="p-2 text-gray-400 hover:text-blue-600 transition-colors">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                </a>
                                <button wire:click="confirmDelete({{ $page->id }})" class="p-2 text-gray-400 hover:text-red-600 transition-colors">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="px-6 py-20 text-center text-gray-400 font-bold uppercase tracking-widest text-xs">CMS Vacío.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Delete Modal -->
    @if($isDeleteModalOpen)
    <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-black/60 backdrop-blur-sm transition-opacity" aria-hidden="true" wire:click="closeDeleteModal"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div class="relative z-10 inline-block align-bottom bg-white rounded-3xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <div class="p-8">
                    <div class="sm:flex sm:items-start">
                        <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-2xl bg-red-50 sm:mx-0 sm:h-10 sm:w-10 text-red-600">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                            <h3 class="text-xl font-black text-gray-900 uppercase tracking-tighter" id="modal-title">Eliminar Página</h3>
                            <p class="text-sm text-gray-500 font-medium mt-2">¿Estás seguro de que deseas eliminar esta página permanentemente?</p>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 p-6 flex flex-row-reverse gap-3">
                    <button wire:click="destroy" class="px-6 py-3 bg-red-600 text-white font-black rounded-2xl hover:bg-red-700 transition-all text-xs uppercase tracking-widest">Eliminar</button>
                    <button wire:click="closeDeleteModal" class="px-6 py-3 bg-white border border-gray-200 text-gray-500 font-bold rounded-2xl hover:bg-gray-100 transition-all text-xs uppercase tracking-widest">Cancelar</button>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
