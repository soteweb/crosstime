<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="overflow-x-hidden w-full">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <title>{{ $title ?? 'Itapúa Cross' }}</title>

        <!-- Google Fonts: Plus Jakarta Sans -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

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
        <header class="bg-white shadow-sm border-b border-gray-100 sticky top-0 z-50" x-data="{ mobileMenuOpen: false }">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-20">
                    <div class="flex items-center">
                        <a href="{{ route('home') }}" class="flex items-center group">
                            <img src="{{ asset('img/logo.jpg') }}" alt="Logo Itapúa Cross" class="h-12 w-auto mr-4 rounded-xl shadow-sm group-hover:scale-105 transition-transform">
                            <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest opacity-60 hidden sm:inline">by Itapúa Cross</span>
                        </a>
                    </div>
                    
                    <!-- Desktop Menu -->
                    <div class="hidden md:flex items-center space-x-6">
                        <a href="{{ route('home') }}" class="text-sm text-gray-600 hover:text-red-600 rounded-md font-bold transition-colors uppercase tracking-widest">Inicio</a>
                        @foreach(\App\Models\MenuItem::where('placement', 'header')->orderBy('order')->get() as $menu)
                            <a href="{{ str_starts_with($menu->url, 'http') ? $menu->url : url($menu->url) }}" target="{{ $menu->target }}" class="text-sm text-gray-600 hover:text-red-600 rounded-md font-bold transition-colors uppercase tracking-widest">{{ $menu->name }}</a>
                        @endforeach
                    </div>

                    <!-- Mobile Menu Button -->
                    <div class="flex items-center md:hidden">
                        <button @click="mobileMenuOpen = !mobileMenuOpen" class="text-gray-500 hover:text-red-600 focus:outline-none p-2">
                            <svg class="h-6 w-6" x-show="!mobileMenuOpen" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                            </svg>
                            <svg class="h-6 w-6" x-cloak x-show="mobileMenuOpen" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </div>
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
                            <li>
                                <a href="{{ route('home') }}" class="text-gray-400 hover:text-white text-sm font-medium transition-colors">
                                    Próximos Eventos
                                </a>
                            </li>
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
                                @auth
                                <a href="{{ route('admin.dashboard') }}" class="text-gray-400 hover:text-white text-sm font-medium transition-colors">
                                    Panel Admin
                                </a>
                                @else
                                <a href="{{ route('login') }}" class="text-gray-400 hover:text-white text-sm font-medium transition-colors">
                                    Ingresar
                                </a>
                                @endauth
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
                                    <svg class="w-4 h-4 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                    </svg>
                                </span>
                                <a href="mailto:itapuacross@gmail.com" class="text-gray-400 hover:text-white text-sm transition-colors">
                                    itapuacross@gmail.com
                                </a>
                            </li>
                            <li class="flex items-center gap-3">
                                <span class="w-8 h-8 rounded-lg bg-white/5 flex items-center justify-center flex-shrink-0">
                                    <svg class="w-4 h-4 text-green-500" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z"/>
                                        <path d="M12 0C5.373 0 0 5.373 0 12c0 2.115.554 4.104 1.523 5.824L.057 23.486l5.81-1.523A11.945 11.945 0 0012 24c6.627 0 12-5.373 12-12S18.627 0 12 0zm0 21.818a9.797 9.797 0 01-5.006-1.375l-.358-.213-3.718.975.993-3.622-.234-.372A9.772 9.772 0 012.182 12C2.182 6.579 6.579 2.182 12 2.182S21.818 6.579 21.818 12 17.421 21.818 12 21.818z"/>
                                    </svg>
                                </span>
                                <a href="https://wa.me/595981000000" target="_blank" class="text-gray-400 hover:text-white text-sm transition-colors">
                                    WhatsApp
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

