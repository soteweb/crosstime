<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{ $title ?? 'Admin - Itapúa Cross' }}</title>
        <!-- Google Fonts: Plus Jakarta Sans -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

        <style>
            body { font-family: 'Plus Jakarta Sans', sans-serif; }
            .font-brand { font-family: 'Plus Jakarta Sans', sans-serif; }
        </style>

        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @livewireStyles
    </head>
    <body x-data="{ sidebarOpen: false }" class="bg-gray-50 text-gray-900 min-h-screen antialiased flex overflow-hidden">
        
        <!-- Mobile Sidebar Overlay -->
        <div x-show="sidebarOpen" 
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             @click="sidebarOpen = false" 
             class="fixed inset-0 z-40 bg-gray-900/50 backdrop-blur-sm lg:hidden"></div>

        <!-- Mobile Sidebar Drawer -->
        <aside x-show="sidebarOpen" 
               x-transition:enter="transition ease-out duration-300"
               x-transition:enter-start="-translate-x-full"
               x-transition:enter-end="translate-x-0"
               x-transition:leave="transition ease-in duration-300"
               x-transition:leave-start="translate-x-0"
               x-transition:leave-end="-translate-x-full"
               class="fixed inset-y-0 left-0 z-50 w-64 bg-white border-r border-gray-200 flex flex-col shadow-2xl lg:hidden">
            <div class="h-20 flex items-center justify-between px-6 border-b border-gray-100">
                <div class="flex items-center">
                    <img src="{{ asset('img/logo.jpg') }}" alt="Logo" class="h-10 w-auto mr-3 rounded-xl shadow-sm">
                    <span class="text-[9px] font-black text-gray-400 uppercase tracking-widest opacity-60">by Itapúa Cross</span>
                </div>
                <button @click="sidebarOpen = false" class="text-gray-400 hover:text-gray-900">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
            <nav class="flex-1 px-4 py-6 space-y-2 overflow-y-auto">
                @include('components.layouts.admin-nav')
            </nav>
        </aside>

        <!-- Desktop Sidebar -->
        <aside class="hidden lg:flex w-64 bg-white border-r border-gray-200 flex-col sticky top-0 h-screen shadow-sm">
            <div class="h-20 flex items-center px-6 border-b border-gray-100">
                <img src="{{ asset('img/logo.jpg') }}" alt="Logo" class="h-10 w-auto mr-3 rounded-xl shadow-sm">
                <span class="text-[9px] font-black text-gray-400 uppercase tracking-widest opacity-60">by Itapúa Cross</span>
            </div>
            <nav class="flex-1 px-4 py-6 space-y-2">
                @include('components.layouts.admin-nav')
            </nav>
            <div class="px-6 py-4 border-t border-gray-200 space-y-4">
                <a href="{{ route('home') }}" class="flex items-center text-sm font-bold text-gray-500 hover:text-red-600 transition-colors cursor-pointer">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    Volver al Frontend
                </a>
                <a href="{{ route('logout') }}" class="flex items-center text-sm font-bold text-red-400 hover:text-red-600 transition-colors cursor-pointer">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                    Cerrar Sesión
                </a>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 flex flex-col h-screen overflow-hidden">
            <!-- Topbar -->
            <header class="h-16 bg-white border-b border-gray-200 flex flex-shrink-0 items-center justify-between px-6 shadow-sm sticky top-0 z-10">
                <div class="flex items-center">
                    <button @click="sidebarOpen = true" class="mr-4 lg:hidden text-gray-500 hover:text-gray-900">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                    </button>
                    <h2 class="text-lg md:text-xl font-bold text-gray-800 truncate">{{ $title ?? 'Panel' }}</h2>
                </div>
                <div class="flex items-center space-x-2 md:space-x-4">
                    <a href="{{ route('home') }}" target="_blank" class="hidden sm:flex items-center text-xs font-bold text-red-600 bg-red-50 hover:bg-red-100 border border-red-200 px-4 py-2 rounded-xl transition-all shadow-sm group">
                        <svg class="w-4 h-4 mr-2 text-red-500 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                        Ver Sitio
                    </a>
                    <span class="bg-gray-100 text-gray-600 text-[10px] md:text-xs font-bold px-2 md:px-3 py-1 rounded-full border border-gray-200 shadow-sm whitespace-nowrap">Admin Activo</span>
                </div>
            </header>
            
            <!-- Dynamic Sub-Content Area -->
            <div class="flex-1 overflow-auto p-4 md:p-8">
                <div class="mx-auto max-w-6xl">
                    {{ $slot }}
                </div>
            </div>
        </main>

        @livewireScripts
        @stack('scripts')
    </body>
</html>
