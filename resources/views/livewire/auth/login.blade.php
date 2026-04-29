<div class="min-h-[70vh] flex flex-col justify-center py-12 sm:px-6 lg:px-8">
    <div class="sm:mx-auto sm:w-full sm:max-w-md">
        <img class="mx-auto h-20 w-auto rounded-2xl shadow-xl" src="{{ asset('img/logo.jpg') }}" alt="Itapúa Cross">
        <h2 class="mt-6 text-center text-3xl font-black text-gray-900 uppercase tracking-tighter">
            Panel Administrativo
        </h2>
        <p class="mt-2 text-center text-sm text-gray-400 font-bold uppercase tracking-widest">
            Ingresa tus credenciales
        </p>
    </div>

    <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
        <div class="bg-white py-8 px-4 shadow-2xl shadow-red-500/10 sm:rounded-[2rem] sm:px-10 border border-gray-100">
            <form wire:submit="login" class="space-y-6">
                <div>
                    <label for="email" class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">
                        Correo Electrónico
                    </label>
                    <div class="mt-1">
                        <input wire:model="email" id="email" name="email" type="email" autocomplete="email" required class="appearance-none block w-full px-4 py-4 border border-gray-200 rounded-2xl shadow-sm placeholder-gray-400 focus:outline-none focus:ring-red-500 focus:border-red-500 font-bold transition-all bg-gray-50">
                    </div>
                    @error('email') <span class="text-red-600 text-xs font-bold mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label for="password" class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">
                        Contraseña
                    </label>
                    <div class="mt-1">
                        <input wire:model="password" id="password" name="password" type="password" autocomplete="current-password" required class="appearance-none block w-full px-4 py-4 border border-gray-200 rounded-2xl shadow-sm placeholder-gray-400 focus:outline-none focus:ring-red-500 focus:border-red-500 font-bold transition-all bg-gray-50">
                    </div>
                </div>

                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <input wire:model="remember" id="remember-me" name="remember-me" type="checkbox" class="h-4 w-4 text-red-600 focus:ring-red-500 border-gray-300 rounded">
                        <label for="remember-me" class="ml-2 block text-xs font-bold text-gray-500 uppercase">
                            Recordarme
                        </label>
                    </div>
                </div>

                <div>
                    <button type="submit" class="w-full flex justify-center py-4 px-4 border border-transparent rounded-2xl shadow-xl shadow-red-500/30 text-xs font-black text-white bg-gradient-to-b from-red-500 to-red-700 hover:from-red-600 hover:to-red-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 uppercase tracking-[0.2em] transition-all">
                        Acceder al Panel
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
