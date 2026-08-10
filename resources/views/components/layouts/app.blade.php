<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="overflow-x-hidden w-full">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <title>{{ $title ?? 'Itapúa Cross' }}</title>

        <!-- Google Fonts: Plus Jakarta Sans & Material Symbols -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet" />

        <style>
            body { font-family: 'Plus Jakarta Sans', sans-serif; }
            .font-brand { font-family: 'Plus Jakarta Sans', sans-serif; }
        </style>

        <!-- Tailwind CSS (Vite) -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        <!-- Livewire Styles -->
        @livewireStyles
    </head>
    <body class="bg-gray-50 text-gray-900 min-h-screen antialiased overflow-x-hidden w-full">
        
        <!-- Header -->
        <header class="bg-white shadow-sm border-b border-gray-100 sticky top-0 z-50 h-16 transition-colors" x-data="{ mobileMenuOpen: false }">
            <div class="h-full px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto flex justify-between items-center">
                <a href="{{ route('home') }}" class="flex items-center gap-2 group">
                    <img src="{{ asset('img/logo.jpg') }}" alt="Logo Itapúa Cross" class="h-10 w-auto rounded-xl shadow-sm group-hover:scale-105 transition-transform">
                    <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest opacity-60 hidden sm:inline">by Itapúa Cross</span>
                </a>
                
                <!-- Desktop Menu -->
                <nav class="hidden md:flex items-center gap-8">
                    <a href="{{ route('home') }}" class="text-gray-600 hover:text-red-600 transition-colors text-xs font-bold tracking-wider uppercase">Inicio</a>
                    @foreach(\App\Models\MenuItem::where('placement', 'header')->orderBy('order')->get() as $menu)
                        <a href="{{ str_starts_with($menu->url, 'http') ? $menu->url : url($menu->url) }}" target="{{ $menu->target }}" class="text-gray-600 hover:text-red-600 transition-colors text-xs font-bold tracking-wider uppercase">{{ $menu->name }}</a>
                    @endforeach
                </nav>

                <!-- CTA Button -->
                <div class="hidden md:flex items-center gap-4">
                    <a href="{{ route('home') }}#proximos-eventos" class="bg-red-600 text-white hover:bg-red-700 transition-colors px-6 py-2.5 rounded-full text-xs font-bold uppercase shadow-sm flex items-center gap-2 hover:-translate-y-0.5">
                        <span class="material-symbols-outlined text-[18px]">event</span>
                        Eventos
                    </a>
                </div>

                <!-- Mobile Menu Button -->
                <button @click="mobileMenuOpen = !mobileMenuOpen" class="md:hidden text-gray-900 p-2 focus:outline-none">
                    <span class="material-symbols-outlined" x-show="!mobileMenuOpen">menu</span>
                    <span class="material-symbols-outlined" x-show="mobileMenuOpen" x-cloak>close</span>
                </button>
            </div>

            <!-- Mobile Menu Dropdown -->
            <div x-cloak x-show="mobileMenuOpen" class="md:hidden bg-white border-t border-gray-100 shadow-xl absolute w-full left-0" 
                 x-transition:enter="transition ease-out duration-200" 
                 x-transition:enter-start="opacity-0 -translate-y-2" 
                 x-transition:enter-end="opacity-100 translate-y-0" 
                 x-transition:leave="transition ease-in duration-150" 
                 x-transition:leave-start="opacity-100 translate-y-0" 
                 x-transition:leave-end="opacity-0 -translate-y-2">
                <div class="px-4 pt-2 pb-6 space-y-1">
                    <a href="{{ route('home') }}" class="block px-3 py-4 text-sm font-bold text-gray-900 border-b border-gray-50 uppercase tracking-widest hover:bg-gray-50 hover:text-red-600 transition-colors">Inicio</a>
                    @foreach(\App\Models\MenuItem::where('placement', 'header')->orderBy('order')->get() as $menu)
                        <a href="{{ str_starts_with($menu->url, 'http') ? $menu->url : url($menu->url) }}" target="{{ $menu->target }}" class="block px-3 py-4 text-sm font-bold text-gray-900 border-b border-gray-50 uppercase tracking-widest hover:bg-gray-50 hover:text-red-600 transition-colors">{{ $menu->name }}</a>
                    @endforeach
                    <a href="{{ route('home') }}#proximos-eventos" class="block px-3 py-4 text-sm font-bold text-red-600 border-b border-gray-50 uppercase tracking-widest hover:bg-gray-50 flex items-center gap-2">
                        <span class="material-symbols-outlined text-[18px]">event</span>
                        Eventos
                    </a>
                </div>
            </div>
        </header>

        <main class="max-w-7xl mx-auto py-8 sm:px-6 lg:px-8 min-h-[50vh]">
            {{ $slot }}
        </main>

        <!-- Footer -->
        <footer class="bg-gray-900 border-t border-white/5 mt-12">

            <!-- Main Footer Content -->
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-12">

                    <!-- Column 1: Logo + Description -->
                    <div class="space-y-6">
                        <a href="{{ route('home') }}" class="inline-flex items-center group">
                            <img src="{{ asset('img/logo.jpg') }}" alt="Logo Itapúa Cross"
                                 class="h-12 w-auto rounded-xl grayscale opacity-60 group-hover:grayscale-0 group-hover:opacity-100 transition-all duration-500 mr-3">
                        </a>
                        <p class="text-gray-400 text-sm leading-relaxed max-w-xs">
                            Organización de Eventos Deportivos en Itapúa, Paraguay. Cicloturismo, Running, Carreras de Aventura y más.
                        </p>

                    </div>

                    <!-- Column 2: Quick Links -->
                    <div>
                        <h3 class="text-white text-xs font-black uppercase tracking-[0.2em] mb-6">
                            ENLACES RÁPIDOS
                        </h3>
                        <ul class="space-y-3">
                            @forelse(\App\Models\MenuItem::where('placement', 'footer')->orderBy('order')->get() as $menu)
                            <li>
                                <a href="{{ str_starts_with($menu->url, 'http') ? $menu->url : url($menu->url) }}"
                                   target="{{ $menu->target }}"
                                   class="text-gray-400 hover:text-white text-sm font-medium transition-colors">
                                    {{ $menu->name }}
                                </a>
                            </li>
                            @empty
                            @endforelse
                            <li>
                                <a href="{{ route('login') }}" class="text-gray-400 hover:text-white text-sm font-medium transition-colors">
                                    Ingresar
                                </a>
                            </li>
                        </ul>
                    </div>

                    <!-- Column 3: Contact -->
                    <div>
                        <h3 class="text-white text-xs font-black uppercase tracking-[0.2em] mb-6">
                            CONTACTO
                        </h3>
                        <ul class="space-y-4">
                            <li class="flex items-center gap-3">
                                <span class="w-8 h-8 rounded-lg bg-white/5 flex items-center justify-center flex-shrink-0">
                                    <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                    </svg>
                                </span>
                                <a href="tel:+59571206649" class="text-gray-400 hover:text-white text-sm transition-colors">
                                    Oficina: +59571206649
                                </a>
                            </li>
                            <li class="flex items-center gap-3">
                                <span class="w-8 h-8 rounded-lg bg-white/5 flex items-center justify-center flex-shrink-0">
                                    <svg class="w-4 h-4 text-blue-600" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                      <path fill-rule="evenodd" d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.988C18.343 21.128 22 16.991 22 12z" clip-rule="evenodd" />
                                    </svg>
                                </span>
                                <a href="https://facebook.com/itapuacross2019" target="_blank" class="text-gray-400 hover:text-white text-sm transition-colors">
                                    Facebook
                                </a>
                            </li>
                            <li class="flex items-center gap-3">
                                <span class="w-8 h-8 rounded-lg bg-white/5 flex items-center justify-center flex-shrink-0">
                                    <svg class="w-4 h-4 text-pink-500" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                      <path fill-rule="evenodd" d="M12.315 2c2.43 0 2.784.013 3.808.06 1.064.049 1.791.218 2.427.465a4.902 4.902 0 011.772 1.153 4.902 4.902 0 011.153 1.772c.247.636.416 1.363.465 2.427.048 1.067.06 1.407.06 4.123v.08c0 2.643-.012 2.987-.06 4.043-.049 1.064-.218 1.791-.465 2.427a4.902 4.902 0 01-1.153 1.772 4.902 4.902 0 01-1.772 1.153c-.636.247-1.363.416-2.427.465-1.067.048-1.407.06-4.123.06h-.08c-2.643 0-2.987-.012-4.043-.06-1.064-.049-1.791-.218-2.427-.465a4.902 4.902 0 01-1.772-1.153 4.902 4.902 0 01-1.153-1.772c-.247-.636-.416-1.363-.465-2.427-.047-1.024-.06-1.379-.06-3.808v-.63c0-2.43.013-2.784.06-3.808.049-1.064.218-1.791.465-2.427a4.902 4.902 0 011.153-1.772A4.902 4.902 0 015.45 2.525c.636-.247 1.363-.416 2.427-.465C8.901 2.013 9.256 2 11.685 2h.63zm-.081 1.802h-.468c-2.456 0-2.784.011-3.807.058-.975.045-1.504.207-1.857.344-.467.182-.8.398-1.15.748-.35.35-.566.683-.748 1.15-.137.353-.3.882-.344 1.857-.047 1.023-.058 1.351-.058 3.807v.468c0 2.456.011 2.784.058 3.807.045.975.207 1.504.344 1.857.182.466.399.8.748 1.15.35.35.683.566 1.15.748.353.137.882.3 1.857.344 1.054.048 1.37.058 4.041.058h.08c2.597 0 2.917-.01 3.96-.058.976-.045 1.505-.207 1.858-.344.466-.182.8-.398 1.15-.748.35-.35.566-.683.748-1.15.137-.353.3-.882.344-1.857.048-1.055.058-1.37.058-4.041v-.08c0-2.597-.01-2.917-.058-3.96-.045-.976-.207-1.505-.344-1.858a3.097 3.097 0 00-.748-1.15 3.098 3.098 0 00-1.15-.748c-.353-.137-.882-.3-1.857-.344-1.023-.047-1.351-.058-3.807-.058zM12 6.865a5.135 5.135 0 110 10.27 5.135 5.135 0 010-10.27zm0 1.802a3.333 3.333 0 100 6.666 3.333 3.333 0 000-6.666zm5.338-3.205a1.2 1.2 0 110 2.4 1.2 1.2 0 010-2.4z" clip-rule="evenodd" />
                                    </svg>
                                </span>
                                <a href="https://instagram.com/itapuacross" target="_blank" class="text-gray-400 hover:text-white text-sm transition-colors">
                                    Instagram
                                </a>
                            </li>
                        </ul>
                    </div>

                </div>
            </div>

            <!-- Bottom Bar -->
            <div class="border-t border-white/5">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-5 flex flex-col md:flex-row items-center justify-between gap-3">
                    <p class="text-gray-600 text-xs uppercase tracking-widest font-bold">
                        &copy; {{ date('Y') }} Itapúa Cross &mdash; Todos los derechos reservados.
                        &nbsp;|&nbsp;
                        Powered by <a href="https://soteweb.com" target="_blank" class="text-gray-500 hover:text-red-500 transition-colors">Soteweb</a>
                    </p>
                    {{-- Hidden admin access - only those who know look here --}}
                    <a href="{{ route('admin.dashboard') }}"
                       class="text-[10px] text-gray-800 hover:text-gray-600 transition-colors font-mono tracking-widest">
                        ·
                    </a>
                </div>
            </div>

        </footer>

        <!-- Livewire Scripts -->
        @livewireScripts
        @stack('scripts')
    </body>
</html>

