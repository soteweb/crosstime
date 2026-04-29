<div class="space-y-6">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h2 class="text-xl font-bold text-gray-900 border-l-4 border-red-600 pl-4 uppercase tracking-tighter">Gestión de Usuarios</h2>
            <p class="text-[10px] text-gray-400 ml-4 font-bold uppercase tracking-widest mt-0.5">Control de accesos y roles</p>
        </div>
        <div>
            @if($view === 'list')
                <button wire:click="openCreate" class="px-4 py-2 bg-gray-900 text-white text-xs font-bold rounded-xl hover:bg-red-600 transition-all shadow-lg flex items-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path></svg>
                    Nuevo Usuario
                </button>
            @else
                <button wire:click="$set('view', 'list')" class="px-4 py-2 bg-white border border-gray-200 text-gray-700 text-xs font-bold rounded-xl hover:bg-gray-50 transition-all shadow-sm flex items-center">
                    <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    Volver al Listado
                </button>
            @endif
        </div>
    </div>

    @if(session()->has('message'))
        <div class="bg-green-50 border-l-4 border-green-500 p-4 rounded-r-xl shadow-sm">
            <p class="text-sm text-green-700 font-bold">{{ session('message') }}</p>
        </div>
    @endif
    @if(session()->has('error'))
        <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded-r-xl shadow-sm">
            <p class="text-sm text-red-700 font-bold">{{ session('error') }}</p>
        </div>
    @endif

    @if($view === 'list')
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="bg-gray-50 border-b border-gray-200">
                            <th class="p-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Nombre / Email</th>
                            <th class="p-4 text-xs font-bold text-gray-500 uppercase tracking-wider text-center">Rol</th>
                            <th class="p-4 text-xs font-bold text-gray-500 uppercase tracking-wider text-right">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 bg-white">
                        @foreach($users as $user)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="p-4">
                                    <p class="text-sm font-bold text-gray-900">{{ $user->name }}</p>
                                    <p class="text-[10px] text-gray-400 font-medium uppercase tracking-tighter">{{ $user->email }}</p>
                                </td>
                                <td class="p-4 text-center">
                                    @if($user->role === 'super_admin')
                                        <span class="px-2 py-1 bg-red-50 text-red-600 text-[9px] font-black rounded-lg border border-red-100 uppercase tracking-tighter">Super Admin</span>
                                    @elseif($user->role === 'organizer')
                                        <span class="px-2 py-1 bg-blue-50 text-blue-600 text-[9px] font-black rounded-lg border border-blue-100 uppercase tracking-tighter">Organizador</span>
                                    @else
                                        <span class="px-2 py-1 bg-gray-50 text-gray-600 text-[9px] font-black rounded-lg border border-gray-200 uppercase tracking-tighter">Staff</span>
                                    @endif
                                </td>
                                <td class="p-4 text-right flex justify-end gap-2">
                                    <button wire:click="editUser({{ $user->id }})" class="p-2 text-gray-400 hover:text-blue-600 transition-colors">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                    </button>
                                    @if(auth()->id() != $user->id)
                                    <button onclick="confirm('¿Eliminar usuario?') || event.stopImmediatePropagation()" wire:click="deleteUser({{ $user->id }})" class="p-2 text-gray-400 hover:text-red-600 transition-colors">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @else
        <div class="bg-white rounded-3xl shadow-xl border border-gray-200 overflow-hidden max-w-2xl mx-auto">
            <div class="p-8 space-y-6">
                <div class="grid grid-cols-1 gap-6">
                    <div>
                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Nombre Completo</label>
                        <input type="text" wire:model="name" class="w-full rounded-xl border-gray-200 bg-gray-50 p-3 font-bold">
                        @error('name') <span class="text-red-600 text-[10px] font-bold">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Email</label>
                        <input type="email" wire:model="email" class="w-full rounded-xl border-gray-200 bg-gray-50 p-3 font-bold">
                        @error('email') <span class="text-red-600 text-[10px] font-bold">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Contraseña {{ $view === 'edit' ? '(Dejar en blanco para no cambiar)' : '' }}</label>
                        <input type="password" wire:model="password" class="w-full rounded-xl border-gray-200 bg-gray-50 p-3 font-bold">
                        @error('password') <span class="text-red-600 text-[10px] font-bold">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Rol del Usuario</label>
                        <select wire:model="role" class="w-full rounded-xl border-gray-200 bg-gray-50 p-3 font-bold">
                            <option value="super_admin">Super Admin</option>
                            <option value="organizer">Organizador</option>
                            <option value="staff">Staff (Acreditaciones)</option>
                        </select>
                        @error('role') <span class="text-red-600 text-[10px] font-bold">{{ $message }}</span> @enderror
                    </div>
                </div>
            </div>
            <div class="p-6 bg-gray-50 border-t border-gray-100 flex gap-4">
                <button wire:click="$set('view', 'list')" class="flex-1 py-3 text-gray-500 font-bold uppercase tracking-widest text-[10px]">Cancelar</button>
                @if($view === 'create')
                    <button wire:click="saveUser" class="flex-[2] py-3 bg-gradient-to-b from-gray-800 to-black text-white font-black rounded-xl transition-all shadow-lg uppercase tracking-widest text-xs">Crear Usuario</button>
                @else
                    <button wire:click="updateUser" class="flex-[2] py-3 bg-gradient-to-b from-blue-500 to-blue-700 text-white font-black rounded-xl transition-all shadow-lg uppercase tracking-widest text-xs">Actualizar Usuario</button>
                @endif
            </div>
        </div>
    @endif
</div>
