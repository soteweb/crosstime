<!-- SECCIÓN: RESUMEN -->
<a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'bg-red-50 text-red-600' : 'text-gray-600 hover:bg-gray-50' }} flex items-center px-4 py-3 text-sm font-bold rounded-lg transition-colors cursor-pointer">
    <svg class="w-5 h-5 mr-3 {{ request()->routeIs('admin.dashboard') ? 'text-red-600' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
    Dashboard
</a>

<!-- SECCIÓN: OPERACIONES -->
<div class="px-4 pt-8 pb-2 text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">Operaciones</div>

<a href="{{ route('admin.athletes') }}" class="{{ request()->routeIs('admin.athletes') ? 'bg-red-50 text-red-600' : 'text-gray-600 hover:bg-gray-50' }} flex items-center px-4 py-3 text-sm font-bold rounded-lg transition-colors cursor-pointer">
    <svg class="w-5 h-5 mr-3 {{ request()->routeIs('admin.athletes') ? 'text-red-600' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
    Inscripciones
</a>

<a href="{{ route('admin.check-in') }}" class="{{ request()->routeIs('admin.check-in') ? 'bg-red-50 text-red-600' : 'text-gray-600 hover:bg-gray-50' }} flex items-center px-4 py-3 text-sm font-bold rounded-lg transition-colors cursor-pointer">
    <svg class="w-5 h-5 mr-3 {{ request()->routeIs('admin.check-in') ? 'text-red-600' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
    Check-In / Kits
</a>

<a href="{{ route('admin.clubs') }}" class="{{ request()->routeIs('admin.clubs') ? 'bg-red-50 text-red-600' : 'text-gray-600 hover:bg-gray-50' }} flex items-center px-4 py-3 text-sm font-bold rounded-lg transition-colors cursor-pointer">
    <svg class="w-5 h-5 mr-3 {{ request()->routeIs('admin.clubs') ? 'text-red-600' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
    Gestión de Clubes
</a>

@if(in_array(auth()->user()->role, [\App\Models\User::ROLE_SUPER_ADMIN, \App\Models\User::ROLE_ORGANIZER]))
<!-- SECCIÓN: CONFIGURACIÓN -->
<div class="px-4 pt-8 pb-2 text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">Configuración</div>

<a href="{{ route('admin.events') }}" class="{{ request()->routeIs('admin.events') ? 'bg-red-50 text-red-600' : 'text-gray-600 hover:bg-gray-50' }} flex items-center px-4 py-3 text-sm font-bold rounded-lg transition-colors cursor-pointer">
    <svg class="w-5 h-5 mr-3 {{ request()->routeIs('admin.events') ? 'text-red-600' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
    Gestión de Eventos
</a>

<a href="{{ route('admin.event-types') }}" class="{{ request()->routeIs('admin.event-types') ? 'bg-red-50 text-red-600' : 'text-gray-600 hover:bg-gray-50' }} flex items-center px-4 py-3 text-sm font-bold rounded-lg transition-colors cursor-pointer">
    <svg class="w-5 h-5 mr-3 {{ request()->routeIs('admin.event-types') ? 'text-red-600' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path></svg>
    Tipos de Evento
</a>

<!-- SECCIÓN: CONTENIDO -->
<div class="px-4 pt-8 pb-2 text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">Contenido Web</div>

<a href="{{ route('admin.pages') }}" class="{{ request()->routeIs('admin.pages') ? 'bg-red-50 text-red-600' : 'text-gray-600 hover:bg-gray-50' }} flex items-center px-4 py-3 text-sm font-bold rounded-lg transition-colors cursor-pointer">
    <svg class="w-5 h-5 mr-3 {{ request()->routeIs('admin.pages') ? 'text-red-600' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
    Páginas (CMS)
</a>

<a href="{{ route('admin.menus') }}" class="{{ request()->routeIs('admin.menus') ? 'bg-red-50 text-red-600' : 'text-gray-600 hover:bg-gray-50' }} flex items-center px-4 py-3 text-sm font-bold rounded-lg transition-colors cursor-pointer">
    <svg class="w-5 h-5 mr-3 {{ request()->routeIs('admin.menus') ? 'text-red-600' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"></path></svg>
    Menús (Navegación)
</a>
@endif

<!-- SECCIÓN: SISTEMA -->
<div class="px-4 pt-8 pb-2 text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">Administración</div>

@if(in_array(auth()->user()->role, [\App\Models\User::ROLE_SUPER_ADMIN, \App\Models\User::ROLE_ORGANIZER]))
<a href="{{ route('admin.finance') }}" class="{{ request()->routeIs('admin.finance') ? 'bg-red-50 text-red-600' : 'text-gray-600 hover:bg-gray-50' }} flex items-center px-4 py-3 text-sm font-bold rounded-lg transition-colors cursor-pointer">
    <svg class="w-5 h-5 mr-3 {{ request()->routeIs('admin.finance') ? 'text-red-600' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
    Finanzas / Reportes
</a>
@endif

@if(auth()->user()->role === \App\Models\User::ROLE_SUPER_ADMIN)
<a href="{{ route('admin.users') }}" class="{{ request()->routeIs('admin.users') ? 'bg-red-50 text-red-600' : 'text-gray-600 hover:bg-gray-50' }} flex items-center px-4 py-3 text-sm font-bold rounded-lg transition-colors cursor-pointer">
    <svg class="w-5 h-5 mr-3 {{ request()->routeIs('admin.users') ? 'text-red-600' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
    Gestión de Usuarios
</a>

<a href="{{ route('admin.backup') }}" class="{{ request()->routeIs('admin.backup*') ? 'bg-red-50 text-red-600' : 'text-gray-600 hover:bg-gray-50' }} flex items-center px-4 py-3 text-sm font-bold rounded-lg transition-colors cursor-pointer">
    <svg class="w-5 h-5 mr-3 {{ request()->routeIs('admin.backup*') ? 'text-red-600' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2 1.5 3 3.5 3h9c2 0 3.5-1 3.5-3V7c0-2-1.5-3-3.5-3h-9C5.5 4 4 5 4 7zm0 5h16m-16 5h16M7 4h10"></path></svg>
    Base de Datos (Backup)
</a>
@endif

