<div class="max-w-3xl mx-auto space-y-6" x-data="{ step: @entangle('step') }">
    <div class="bg-white p-8 md:p-10 rounded-3xl shadow-2xl border border-gray-100 relative overflow-hidden">
        <!-- Decoration -->
        <div class="absolute top-0 right-0 -mt-10 -mr-10 w-40 h-40 bg-red-500 rounded-full opacity-10 blur-3xl pointer-events-none"></div>
        <div class="absolute bottom-0 left-0 -mb-10 -ml-10 w-32 h-32 bg-gray-900 rounded-full opacity-10 blur-3xl pointer-events-none"></div>

        <!-- Header -->
        <div class="mb-8 text-center">
            <h2 class="text-3xl font-bold mb-2 text-gray-900 tracking-tight leading-tight uppercase">
                {{ $event->title }}
            </h2>
            <div class="flex items-center justify-center space-x-4 text-xs font-bold text-gray-400 uppercase tracking-widest">
                <span class="flex items-center">
                    <svg class="w-4 h-4 mr-1 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    {{ \Carbon\Carbon::parse($event->date)->format('d/m/Y') }}
                </span>
                <span class="flex items-center">
                    <svg class="w-4 h-4 mr-1 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path></svg>
                    {{ $event->location_address }}
                </span>
            </div>
        </div>

        <!-- Progress Steps -->
        @if($step < 5)
            <div class="mb-10 px-4">
                <div class="relative flex justify-between">
                    <div class="absolute top-4 left-0 w-full h-0.5 bg-gray-100 -translate-y-1/2"></div>
                    <div class="absolute top-4 left-0 h-0.5 bg-red-600 -translate-y-1/2 transition-all duration-500" style="width: {{ ($step - 1) * 33.33 }}%"></div>

                    @foreach([1 => 'Cédula', 2 => 'Datos', 3 => 'Carrera', 4 => 'Pago'] as $s => $label)
                        <div class="relative flex flex-col items-center gap-2">
                            <div class="w-8 h-8 rounded-full flex items-center justify-center text-xs font-bold transition-all duration-300 z-10
                                {{ $step >= $s ? 'bg-red-600 text-white shadow-lg shadow-red-200' : 'bg-white border-2 border-gray-200 text-gray-300' }}">
                                @if($step > $s)
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                                @else
                                    {{ $s }}
                                @endif
                            </div>
                            <span class="text-[9px] font-black uppercase tracking-widest {{ $step >= $s ? 'text-red-600' : 'text-gray-300' }}">{{ $label }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Wizard Steps Content -->
        <div class="min-h-[300px]">

            <!-- Step 1: Identification -->
            @if($step == 1)
                <div class="animate-fadeIn space-y-8">
                    <div class="text-center">
                        <h3 class="text-xl font-bold text-gray-900 mb-2">Comencemos con tu Identificación</h3>
                        <p class="text-gray-400 text-sm font-medium">Ingresa tu número de documento para comenzar.</p>
                    </div>
                    <div class="max-w-sm mx-auto">
                        <div class="reg-field">
                            <label class="reg-label">Número de Cédula / DNI</label>
                            <input type="text" wire:model="identity_document" placeholder="Ej: 1234567"
                                class="reg-input text-2xl font-bold text-center tracking-widest">
                            @error('identity_document') <span class="reg-error">{{ $message }}</span> @enderror
                        </div>
                        <button wire:click="goToStep2"
                            class="w-full mt-8 bg-gray-900 text-white font-black py-4 rounded-xl hover:bg-red-600 transition-all shadow-xl uppercase tracking-widest text-sm">
                            Continuar →
                        </button>
                    </div>
                </div>
            @endif

            <!-- Step 2: Personal Data -->
            @if($step == 2)
                <div class="animate-fadeIn space-y-2">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-1 h-6 bg-red-600 rounded-full"></div>
                        <h3 class="text-xl font-bold text-gray-900">Tus Datos Personales</h3>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-2">
                        <div class="reg-field">
                            <label class="reg-label">Nombres</label>
                            <input type="text" wire:model="name" class="reg-input">
                            @error('name') <span class="reg-error">{{ $message }}</span> @enderror
                        </div>
                        <div class="reg-field">
                            <label class="reg-label">Apellidos</label>
                            <input type="text" wire:model="last_name" class="reg-input">
                            @error('last_name') <span class="reg-error">{{ $message }}</span> @enderror
                        </div>
                        <div class="reg-field">
                            <label class="reg-label">Género</label>
                            <select wire:model="gender" class="reg-select">
                                <option value="">Seleccionar</option>
                                <option value="M">Masculino</option>
                                <option value="F">Femenino</option>
                            </select>
                            @error('gender') <span class="reg-error">{{ $message }}</span> @enderror
                        </div>
                        <div class="reg-field">
                            <label class="reg-label">Fecha de Nacimiento</label>
                            <input type="date" wire:model="date_of_birth" class="reg-input">
                            @error('date_of_birth') <span class="reg-error">{{ $message }}</span> @enderror
                        </div>
                        <div class="reg-field">
                            <label class="reg-label">Grupo Sanguíneo</label>
                            <select wire:model="blood_type" class="reg-select">
                                <option value="">Desconocido</option>
                                @foreach(['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'] as $bt)
                                    <option value="{{ $bt }}">{{ $bt }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="reg-field">
                            <label class="reg-label">Club / Asociación</label>
                            <input type="text" wire:model="club" placeholder="Opcional" class="reg-input">
                        </div>
                        <div class="reg-field">
                            <label class="reg-label">WhatsApp</label>
                            <input type="text" wire:model="phone" placeholder="Ej: 0981..." class="reg-input">
                            @error('phone') <span class="reg-error">{{ $message }}</span> @enderror
                        </div>
                        <div class="reg-field">
                            <label class="reg-label">Correo Electrónico</label>
                            <input type="email" wire:model="email" class="reg-input">
                            @error('email') <span class="reg-error">{{ $message }}</span> @enderror
                        </div>
                        <div class="reg-field">
                            <label class="reg-label">Ciudad</label>
                            <input type="text" wire:model="city" class="reg-input">
                            @error('city') <span class="reg-error">{{ $message }}</span> @enderror
                        </div>
                        <div class="reg-field">
                            <label class="reg-label">País</label>
                            <input type="text" wire:model="country" class="reg-input">
                        </div>
                    </div>

                    <div class="flex gap-4 pt-6">
                        <button wire:click="$set('step', 1)" class="reg-btn-back">← Atrás</button>
                        <button wire:click="goToStep3" class="reg-btn-next">Continuar →</button>
                    </div>
                </div>
            @endif

            <!-- Step 3: Event Details & Options -->
            @if($step == 3)
                <div class="animate-fadeIn space-y-6">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-1 h-6 bg-red-600 rounded-full"></div>
                        <h3 class="text-xl font-bold text-gray-900">Opciones de Competencia</h3>
                    </div>

                    @if($event->distances->isNotEmpty())
                        <div>
                            <label class="reg-label mb-3 block">1. Selecciona tu Carrera (Distancia)</label>
                            <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                                @foreach($event->distances as $dist)
                                    <label class="cursor-pointer">
                                        <input type="radio" wire:model.live="selected_distance_id" value="{{ $dist->id }}" class="peer sr-only">
                                        <div class="p-4 bg-white border-2 border-gray-200 rounded-xl text-center font-bold text-gray-400 peer-checked:border-red-600 peer-checked:bg-red-50 peer-checked:text-red-600 transition-all">
                                            {{ $dist->name }}
                                        </div>
                                    </label>
                                @endforeach
                            </div>
                            @error('selected_distance_id') <span class="reg-error mt-2">{{ $message }}</span> @enderror
                        </div>
                    @endif

                    <div>
                        <label class="reg-label mb-3 block">2. Tu Categoría Sugerida</label>
                        <div class="space-y-2">
                            @forelse($this->availableCategories as $cat)
                                <label class="cursor-pointer block group">
                                    <input type="radio" wire:model="selected_category_id" value="{{ $cat->id }}" class="peer sr-only">
                                    <div class="p-4 bg-white border-2 border-gray-200 rounded-xl flex items-center justify-between peer-checked:border-red-600 peer-checked:bg-red-50 transition-all group-hover:border-gray-300">
                                        <div>
                                            <p class="font-black text-gray-800 uppercase tracking-tight">{{ $cat->name }}</p>
                                            <p class="text-[10px] font-bold text-gray-400 uppercase">Edad: {{ $cat->min_age ?? '0' }}-{{ $cat->max_age ?? '99' }} años</p>
                                        </div>
                                        <div class="text-right">
                                            <p class="text-lg font-bold text-red-600">Gs. {{ number_format($cat->price, 0, ',', '.') }}</p>
                                        </div>
                                    </div>
                                </label>
                            @empty
                                <div class="p-8 border-2 border-dashed border-gray-100 rounded-xl text-center text-gray-400 font-bold italic">
                                    No se encontraron categorías disponibles para tu edad/género.
                                    @if($event->distances->isNotEmpty() && !$selected_distance_id)
                                        Por favor selecciona una distancia primero.
                                    @endif
                                </div>
                            @endforelse
                        </div>
                        @error('selected_category_id') <span class="reg-error mt-2">{{ $message }}</span> @enderror
                    </div>

                    @if($event->registration_settings['has_tshirts'] ?? false)
                        <div>
                            <label class="reg-label mb-3 block">Talle de Remera</label>
                            <div class="flex flex-wrap gap-2">
                                @foreach($event->registration_settings['tshirt_sizes'] ?? [] as $size)
                                    <label class="cursor-pointer">
                                        <input type="radio" wire:model="tshirt_size" value="{{ $size }}" class="peer sr-only">
                                        <div class="w-12 h-12 flex items-center justify-center bg-white border-2 border-gray-200 rounded-lg font-bold text-gray-400 peer-checked:border-red-600 peer-checked:bg-red-50 peer-checked:text-red-600 transition-all">
                                            {{ $size }}
                                        </div>
                                    </label>
                                @endforeach
                            </div>
                            @error('tshirt_size') <span class="reg-error mt-2">{{ $message }}</span> @enderror
                        </div>
                    @endif

                    <div class="reg-field">
                        <label class="reg-label">Observaciones / Comentario</label>
                        <textarea wire:model="observation" placeholder="¿Algo que debamos saber?" rows="3"
                            class="reg-input resize-none pt-2"></textarea>
                    </div>

                    <div class="bg-amber-50 p-5 rounded-xl border border-amber-200">
                        <label class="flex items-start space-x-4 cursor-pointer">
                            <input type="checkbox" wire:model="accepts_waiver"
                                class="w-5 h-5 mt-0.5 rounded border-amber-300 text-amber-600 focus:ring-amber-500 shrink-0">
                            <div class="text-xs font-bold text-amber-900 leading-relaxed">
                                Acepto el <a href="#" class="underline font-black text-red-600">Deslinde de Responsabilidades</a> y confirmo que me encuentro en condiciones físicas y médicas aptas para participar.
                                @error('accepts_waiver') <p class="text-red-600 font-black mt-1">{{ $message }}</p> @enderror
                            </div>
                        </label>
                    </div>

                    <div class="flex gap-4 pt-2">
                        <button wire:click="$set('step', 2)" class="reg-btn-back">← Atrás</button>
                        <button wire:click="goToStep4" class="reg-btn-next">Continuar →</button>
                    </div>
                </div>
            @endif

            <!-- Step 4: Payment Selection & Finish -->
            @if($step == 4)
                <div class="animate-fadeIn space-y-8">
                    <div class="text-center">
                        <h3 class="text-xl font-black text-gray-900 mb-2">¿Cómo deseas realizar el pago?</h3>
                        <p class="text-gray-400 text-sm font-medium">Selecciona el medio de pago para tu reserva.</p>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 max-w-xl mx-auto">
                        @foreach($event->registration_settings['payment_methods'] ?? ['Efectivo', 'Transferencia'] as $method)
                            <label class="cursor-pointer group">
                                <input type="radio" wire:model="payment_method" value="{{ $method }}" class="peer sr-only">
                                <div class="p-6 bg-white border-2 border-gray-200 rounded-xl flex flex-col items-center justify-center space-y-3 peer-checked:border-red-600 peer-checked:bg-red-50 transition-all group-hover:border-gray-300 shadow-sm">
                                    <div class="p-3 bg-gray-100 rounded-xl peer-checked:bg-red-100 group-[.peer:checked~div>&]:bg-red-100">
                                        @if($method == 'Efectivo')
                                            <svg class="w-8 h-8 text-gray-400 peer-checked:text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                                        @elseif($method == 'Transferencia')
                                            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path></svg>
                                        @else
                                            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
                                        @endif
                                    </div>
                                    <span class="font-black text-gray-700 uppercase tracking-tighter text-sm">{{ $method }}</span>
                                </div>
                            </label>
                        @endforeach
                    </div>
                    @error('payment_method') <p class="text-red-500 text-xs font-bold text-center">{{ $message }}</p> @enderror

                    @if(($payment_method == 'Transferencia' || $payment_method == 'Giros') && $event->payment_instructions)
                        <div class="animate-fadeIn p-5 bg-blue-50 border border-blue-200 rounded-xl max-w-xl mx-auto">
                            <h4 class="text-xs font-black text-blue-700 uppercase tracking-widest mb-2">Instrucciones para {{ $payment_method }}</h4>
                            <div class="text-sm font-medium text-blue-900 whitespace-pre-line">
                                {{ $event->payment_instructions }}
                            </div>
                        </div>
                    @endif

                    <div class="bg-gray-50 p-5 rounded-xl border border-gray-200 max-w-xl mx-auto">
                        <div class="flex justify-between items-center mb-3">
                            <span class="text-sm font-bold text-gray-500">Categoría:</span>
                            <span class="text-sm font-black text-gray-900 uppercase">
                                {{ $event->categories->find($selected_category_id)->name ?? '-' }}
                            </span>
                        </div>
                        <div class="flex justify-between items-center pt-3 border-t border-gray-200">
                            <span class="text-base font-black text-gray-900">Total a Pagar:</span>
                            <span class="text-2xl font-black text-red-600 tracking-tight">
                                Gs. {{ number_format($event->categories->find($selected_category_id)->price ?? 0, 0, ',', '.') }}
                            </span>
                        </div>
                    </div>

                    <div class="flex gap-4 pt-2 max-w-xl mx-auto">
                        <button wire:click="$set('step', 3)" class="reg-btn-back">← Atrás</button>
                        <button wire:click="register" wire:loading.attr="disabled"
                            class="flex-[2] py-4 bg-gray-900 text-white font-black rounded-xl hover:bg-red-600 transition-all shadow-xl uppercase tracking-widest text-sm">
                            <span wire:loading.remove>Finalizar Inscripción</span>
                            <span wire:loading>Procesando...</span>
                        </button>
                    </div>
                </div>
            @endif

            <!-- Step 5: Success -->
            @if($step == 5)
                <div class="animate-fadeIn py-10 text-center space-y-6">
                    <div class="w-24 h-24 bg-green-100 text-green-500 rounded-full flex items-center justify-center mx-auto shadow-inner">
                        <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                    </div>
                    <div>
                        <h3 class="text-3xl font-black text-gray-900 mb-2 tracking-tighter">¡Inscripción Registrada!</h3>
                        <p class="text-gray-400 font-medium max-w-xs mx-auto">Tu lugar ha sido reservado. Ahora debes proceder al pago para confirmar tu participación.</p>
                    </div>

                    <div class="bg-gray-50 p-6 rounded-xl border border-gray-200 max-w-sm mx-auto text-left">
                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-4 border-b border-gray-200 pb-2">Resumen</p>
                        <p class="text-sm font-bold text-gray-500 mb-2">Atleta: <span class="text-gray-900 font-black">{{ $name }} {{ $last_name }}</span></p>
                        <p class="text-sm font-bold text-gray-500 mb-2">Categoría: <span class="text-gray-900 font-black uppercase">{{ $event->categories->find($selected_category_id)->name ?? '-' }}</span></p>
                        <p class="text-sm font-bold text-gray-500">Pago: <span class="text-red-600 font-black">{{ $payment_method }}</span></p>

                        @if(($payment_method == 'Transferencia' || $payment_method == 'Giros') && $event->payment_instructions)
                            <div class="mt-4 p-4 bg-blue-50 rounded-xl border border-blue-100">
                                <p class="text-[10px] font-black text-blue-600 uppercase tracking-widest mb-2">Pasos a seguir:</p>
                                <p class="text-xs font-bold text-blue-900 whitespace-pre-line leading-relaxed">{{ $event->payment_instructions }}</p>
                            </div>
                        @endif
                    </div>

                    <div class="pt-6">
                        <a href="/" class="px-10 py-4 bg-gray-900 text-white font-black rounded-xl hover:bg-red-600 transition-all shadow-xl inline-block uppercase tracking-widest text-sm">
                            Volver al Inicio
                        </a>
                    </div>
                </div>
            @endif

        </div>
    </div>
</div>

<style>
    /* ── Registro: campo con línea inferior ── */
    .reg-field {
        @apply relative pb-1 pt-1;
        border-bottom: 2px solid #e5e7eb;
        transition: border-color 0.2s;
        margin-bottom: 1.25rem;
    }
    .reg-field:focus-within {
        border-color: #dc2626;
    }

    .reg-label {
        display: block;
        font-size: 0.65rem;
        font-weight: 900;
        letter-spacing: 0.12em;
        text-transform: uppercase;
        color: #9ca3af;
        margin-bottom: 0.25rem;
        transition: color 0.2s;
    }
    .reg-field:focus-within .reg-label {
        color: #dc2626;
    }

    .reg-input,
    .reg-select {
        display: block;
        width: 100%;
        background: transparent;
        border: none;
        outline: none;
        box-shadow: none !important;
        padding: 0.375rem 0;
        font-size: 0.9375rem;
        font-weight: 700;
        color: #111827;
        appearance: none;
        -webkit-appearance: none;
        border-radius: 0;
    }
    .reg-input::placeholder {
        color: #d1d5db;
        font-weight: 500;
    }
    .reg-input:focus,
    .reg-select:focus {
        outline: none;
        box-shadow: none;
        ring: 0;
    }

    /* custom chevron for selects */
    .reg-select {
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%239ca3af' stroke-width='2.5'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' d='M19 9l-7 7-7-7'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 0.25rem center;
        background-size: 1rem;
        padding-right: 1.5rem;
    }

    .reg-error {
        display: block;
        font-size: 0.7rem;
        font-weight: 700;
        color: #dc2626;
        margin-top: 0.25rem;
    }

    /* Buttons */
    .reg-btn-back {
        @apply flex-1 py-4 bg-gray-100 text-gray-500 font-black rounded-xl hover:bg-gray-200 transition-all uppercase tracking-widest text-xs;
    }
    .reg-btn-next {
        @apply flex-[2] py-4 bg-gray-900 text-white font-black rounded-xl hover:bg-red-600 transition-all shadow-xl uppercase tracking-widest text-sm;
    }

    /* Fade animation */
    .animate-fadeIn {
        animation: fadeIn 0.35s ease-out;
    }
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(8px); }
        to   { opacity: 1; transform: translateY(0); }
    }
</style>
