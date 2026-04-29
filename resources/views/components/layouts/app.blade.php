<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
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
    <body class="bg-gray-50 text-gray-900 min-h-screen antialiased">
        
        <header class="bg-white shadow-sm border-b border-gray-100 sticky top-0 z-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-20">
                    <div class="flex items-center">
                        <a href="{{ route('home') }}" class="flex items-center group">
                            <img src="{{ asset('img/logo.jpg') }}" alt="Logo Itapúa Cross" class="h-12 w-auto mr-4 rounded-xl shadow-sm group-hover:scale-105 transition-transform">
                            <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest opacity-60">by Itapúa Cross</span>
                        </a>
                    </div>
                    <div class="flex items-center space-x-6">
                        <a href="{{ route('home') }}" class="text-sm text-gray-600 hover:text-red-600 rounded-md font-bold transition-colors uppercase tracking-widest">Eventos</a>
                        @foreach(\App\Models\MenuItem::where('placement', 'header')->orderBy('order')->get() as $menu)
                            <a href="{{ str_starts_with($menu->url, 'http') ? $menu->url : url($menu->url) }}" target="{{ $menu->target }}" class="text-sm text-gray-600 hover:text-red-600 rounded-md font-bold transition-colors uppercase tracking-widest">{{ $menu->name }}</a>
                        @endforeach
                        <a href="{{ route('admin.dashboard') }}" class="text-[11px] font-black text-gray-400 hover:text-red-600 transition-colors uppercase tracking-[0.2em] border-l border-gray-200 pl-8">Admin</a>
                    </div>
                </div>
            </div>
        </header>

        <main class="max-w-7xl mx-auto py-8 sm:px-6 lg:px-8 min-h-[50vh]">
            {{ $slot }}
        </main>

        <!-- Dynamic Footer -->
        <footer class="bg-gray-900 border-t border-gray-800 mt-12">
            <div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
                <div class="md:flex md:items-center md:justify-between flex-col md:flex-row space-y-6 md:space-y-0">
                    <div class="flex justify-center md:justify-start items-center">
                        <img src="{{ asset('img/logo.jpg') }}" alt="Logo Itapúa Cross" class="h-10 w-auto mr-4 rounded-xl grayscale opacity-50 hover:grayscale-0 hover:opacity-100 transition-all duration-300">
                        <span class="text-[9px] font-black text-gray-500 uppercase tracking-widest">by Itapúa Cross</span>
                    </div>
                    <div class="flex flex-wrap gap-4 md:gap-8 text-sm justify-center md:justify-start">
                        @forelse(\App\Models\MenuItem::where('placement', 'footer')->orderBy('order')->get() as $menu)
                            <a href="{{ str_starts_with($menu->url, 'http') ? $menu->url : url($menu->url) }}" target="{{ $menu->target }}" class="text-gray-400 hover:text-white transition-colors font-bold uppercase tracking-wider">{{ $menu->name }}</a>
                        @empty
                            <span class="text-gray-500 font-bold uppercase tracking-wider text-xs">Sin enlaces configurados</span>
                        @endforelse
                    </div>
                    <div class="flex justify-center md:justify-end text-sm text-gray-500 font-bold">
                        &copy; {{ date('Y') }} Itapúa Cross. Todos los derechos reservados.
                    </div>
                </div>
            </div>
        </footer>

        <!-- Livewire Scripts -->
        @livewireScripts
    </body>
</html>
