<div>
    <x-slot name="title">{{ $eventId ? 'Editar Evento' : 'Nuevo Evento' }} - Itapúa Admin</x-slot>

    <!-- Editor Dependencies -->
    <div wire:ignore>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/6.8.3/tinymce.min.js" referrerpolicy="origin"></script>
    </div>

    <div class="mb-8 flex items-center justify-between">
        <div class="flex items-center">
            <a href="{{ route('admin.events') }}" class="mr-4 text-gray-400 hover:text-red-600 transition-colors">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            </a>
            <div>
                <h1 class="text-3xl font-bold text-gray-900 border-l-4 border-red-600 pl-4 mb-2">
                    {{ $eventId ? 'Editar Evento' : 'Añadir Nuevo Evento' }}
                </h1>
                <p class="text-gray-500 ml-4 font-medium">Completa los detalles de la competencia.</p>
            </div>
        </div>
        <div class="hidden md:flex space-x-3">
            <a href="{{ route('admin.events') }}" class="px-6 py-2 border border-gray-300 text-gray-700 font-bold rounded-xl hover:bg-gray-50 transition-colors shadow-sm">Cancelar</a>
            <button wire:click="save" class="px-8 py-2 bg-red-600 text-white font-bold rounded-xl hover:bg-red-700 transition-all shadow-md hover:shadow-lg shadow-red-500/30">
                {{ $eventId ? 'Actualizar Evento' : 'Publicar Evento' }}
            </button>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-4 gap-8" x-data x-init="initEventEditor()">
    
        <!-- Left Wide Column: Main Content -->
        <div class="lg:col-span-3 space-y-6">
            <div class="bg-white p-8 rounded-2xl shadow-sm border border-gray-200">
                <div class="mb-6 grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="md:col-span-2">
                        <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">Título del Evento</label>
                        <input type="text" wire:model="title" placeholder="Ej: Itapúa Cross - Desafío Trail" class="w-full text-3xl font-bold text-gray-900 placeholder-gray-200 border-0 border-b-2 border-transparent hover:border-gray-50 focus:border-red-600 focus:ring-0 px-0 py-2 transition-colors">
                        @error('title') <span class="text-red-500 text-sm font-bold block mt-1">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">Organiza</label>
                        <input type="text" wire:model="organizer" placeholder="Ej: Club Ciclista" class="w-full text-xl font-bold text-gray-700 placeholder-gray-200 border-0 border-b-2 border-transparent hover:border-gray-50 focus:border-red-600 focus:ring-0 px-0 py-2 transition-colors mt-2">
                    </div>
                </div>
                
                <div class="mb-8">
                    <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">Descripción Detallada</label>
                    <div wire:ignore class="rounded-xl overflow-hidden border border-gray-200 shadow-sm focus-within:border-orange-500 transition-all">
                        <textarea id="event-description-editor" class="w-full opacity-0">{{ $description }}</textarea>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">Ubicación (Lugar)</label>
                        <input type="text" wire:model="location_address" placeholder="Ej: Costanera de Encarnación" class="w-full rounded-xl border-gray-200 bg-gray-50 shadow-sm focus:border-red-600 focus:ring-red-600 font-bold p-3">
                    </div>
                    <div>
                        <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">Enlace Google Maps</label>
                        <input type="url" wire:model="google_maps_url" placeholder="https://maps.google.com/..." class="w-full rounded-xl border-gray-200 bg-gray-50 shadow-sm focus:border-red-600 focus:ring-red-600 font-bold p-3">
                    </div>
                </div>
            </div>

            <div class="bg-blue-50/50 p-8 rounded-2xl border border-blue-100">
                <div class="flex items-center mb-6">
                    <div class="p-2 bg-blue-100 rounded-lg mr-3">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                    </div>
                    <h3 class="text-lg font-black text-blue-900">Configuración de Registro</h3>
                </div>

                <div class="space-y-6">
                    <div class="flex items-center space-x-8">
                        <label class="flex items-center cursor-pointer group">
                            <input type="radio" wire:model.live="registration_type" value="local" class="w-5 h-5 text-red-600 border-gray-300 focus:ring-red-600">
                            <span class="ml-3 text-sm font-bold text-gray-700 group-hover:text-red-600 transition-colors">Inscripción Local (Itapúa Cross)</span>
                        </label>
                        <label class="flex items-center cursor-pointer group">
                            <input type="radio" wire:model.live="registration_type" value="external" class="w-5 h-5 text-blue-600 border-gray-300 focus:ring-blue-500">
                            <span class="ml-3 text-sm font-black text-gray-700 group-hover:text-blue-600 transition-colors">Enlace Externo (Otra Plataforma)</span>
                        </label>
                    </div>

                    @if($registration_type === 'external')
                        <div class="animate-fadeIn p-4 bg-white rounded-xl border border-blue-200 shadow-sm">
                            <label class="block text-[10px] font-black text-blue-600 uppercase mb-2 tracking-widest">URL de Inscripción Externa</label>
                            <input type="url" wire:model="external_registration_url" placeholder="https://plataforma-externa.com/inscripcion" class="w-full rounded-lg border-blue-100 bg-blue-50/30 focus:border-blue-500 focus:ring-blue-500 font-bold p-3 text-sm">
                        </div>
                    @else
                    @if($registration_type === 'local')
                        <div class="space-y-8 animate-fadeIn">
                            <!-- Distances -->
                            <div class="p-6 bg-white rounded-xl border border-gray-200 shadow-sm">
                                <div class="flex items-center justify-between mb-4">
                                    <h4 class="text-sm font-black text-gray-900 uppercase tracking-widest">Distancias / Carreras</h4>
                                    <button type="button" wire:click="addDistance" class="text-xs font-bold text-orange-600 hover:text-orange-700 flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                        Añadir Distancia
                                    </button>
                                </div>
                                <div class="space-y-3">
                                    @foreach($distances as $index => $distance)
                                        <div class="flex items-center gap-3">
                                            <input type="text" wire:model="distances.{{ $index }}.name" placeholder="Ej: 10K, 21K, 5K Nocturno" class="flex-1 rounded-lg border-gray-200 bg-gray-50 text-sm font-bold">
                                            <button type="button" wire:click="removeDistance({{ $index }})" class="p-2 text-red-500 hover:bg-red-50 rounded-lg transition-colors">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                            </button>
                                        </div>
                                    @endforeach
                                    @if(empty($distances))
                                        <p class="text-xs text-gray-400 italic">No hay distancias definidas. Los atletas elegirán la categoría directamente.</p>
                                    @endif
                                </div>
                            </div>

                            <!-- Categories -->
                            <div class="p-6 bg-white rounded-xl border border-gray-200 shadow-sm">
                                <div class="flex items-center justify-between mb-4">
                                    <h4 class="text-sm font-black text-gray-900 uppercase tracking-widest">Categorías</h4>
                                    <button type="button" wire:click="addCategory" class="text-xs font-bold text-orange-600 hover:text-orange-700 flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                        Añadir Categoría
                                    </button>
                                </div>
                                <div class="space-y-4">
                                    @foreach($categories as $index => $category)
                                        <div class="p-4 bg-gray-50 rounded-xl border border-gray-100 relative group">
                                            <button type="button" wire:click="removeCategory({{ $index }})" class="absolute top-2 right-2 p-1 text-gray-400 hover:text-red-500 rounded transition-colors">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                            </button>
                                            
                                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-3">
                                                <div class="md:col-span-2">
                                                    <label class="block text-[10px] font-black text-gray-400 uppercase mb-1">Nombre de Categoría</label>
                                                    <input type="text" wire:model="categories.{{ $index }}.name" placeholder="Ej: Elite Masculino, Femenino 18-29" class="w-full rounded-lg border-gray-200 text-sm font-bold">
                                                </div>
                                                <div>
                                                    <label class="block text-[10px] font-black text-gray-400 uppercase mb-1">Precio (Gs)</label>
                                                    <input type="number" wire:model="categories.{{ $index }}.price" class="w-full rounded-lg border-gray-200 text-sm font-bold">
                                                </div>
                                            </div>

                                            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                                                <div>
                                                    <label class="block text-[10px] font-black text-gray-400 uppercase mb-1">Género</label>
                                                    <select wire:model="categories.{{ $index }}.gender_restriction" class="w-full rounded-lg border-gray-200 text-sm font-bold">
                                                        <option value="">Ambos</option>
                                                        <option value="M">Masculino</option>
                                                        <option value="F">Femenino</option>
                                                    </select>
                                                </div>
                                                <div>
                                                    <label class="block text-[10px] font-black text-gray-400 uppercase mb-1">Edad Mín.</label>
                                                    <input type="number" wire:model="categories.{{ $index }}.min_age" class="w-full rounded-lg border-gray-200 text-sm font-bold">
                                                </div>
                                                <div>
                                                    <label class="block text-[10px] font-black text-gray-400 uppercase mb-1">Edad Máx.</label>
                                                    <input type="number" wire:model="categories.{{ $index }}.max_age" class="w-full rounded-lg border-gray-200 text-sm font-bold">
                                                </div>
                                                <div>
                                                    <label class="block text-[10px] font-black text-gray-400 uppercase mb-1">Distancia</label>
                                                    <select wire:model="categories.{{ $index }}.event_distance_id" class="w-full rounded-lg border-gray-200 text-sm font-bold">
                                                        <option value="">Todas</option>
                                                        @foreach($eventDistances as $dist)
                                                            <option value="{{ $dist->id }}">{{ $dist->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            
                                            <div class="mt-4 pt-3 border-t border-gray-100 flex items-center">
                                                <label class="flex items-center cursor-pointer group">
                                                    <input type="checkbox" wire:model="categories.{{ $index }}.includes_tshirt" class="w-4 h-4 text-orange-600 border-gray-300 rounded focus:ring-orange-500">
                                                    <span class="ml-2 text-[10px] font-black text-gray-500 uppercase tracking-widest group-hover:text-orange-600 transition-colors">Atletas reciben remera en esta categoría</span>
                                                </label>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <!-- Payment & T-Shirt Settings -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="p-6 bg-white rounded-xl border border-gray-200 shadow-sm">
                                    <h4 class="text-sm font-black text-gray-900 uppercase tracking-widest mb-4">Métodos de Pago</h4>
                                    <div class="space-y-3">
                                        @foreach(['Efectivo', 'Transferencia', 'Giros', 'Otro'] as $method)
                                            <label class="flex items-center cursor-pointer group">
                                                <input type="checkbox" wire:model.live="payment_methods" value="{{ $method }}" class="w-4 h-4 text-orange-600 border-gray-300 rounded focus:ring-orange-500">
                                                <span class="ml-3 text-sm font-bold text-gray-700 group-hover:text-orange-600 transition-colors">{{ $method }}</span>
                                            </label>
                                        @endforeach
                                    </div>
                                </div>

                                <div class="p-6 bg-white rounded-xl border border-gray-200 shadow-sm">
                                    <div class="flex items-center justify-between mb-4">
                                        <h4 class="text-sm font-black text-gray-900 uppercase tracking-widest">Uso de Remera</h4>
                                        <input type="checkbox" wire:model.live="has_tshirts" class="w-5 h-5 text-orange-600 border-gray-300 rounded focus:ring-orange-500">
                                    </div>
                                    
                                    @if($has_tshirts)
                                        <div class="animate-fadeIn">
                                            <label class="block text-[10px] font-black text-gray-400 uppercase mb-2">Talles Disponibles (separados por coma)</label>
                                            <input type="text" value="{{ implode(', ', $tshirt_sizes) }}" 
                                                   x-on:blur="@this.set('tshirt_sizes', $event.target.value.split(',').map(s => s.trim()).filter(s => s !== ''))"
                                                   class="w-full rounded-lg border-gray-200 text-sm font-bold bg-gray-50"
                                                   placeholder="S, M, L, XL">
                                        </div>
                                    @else
                                        <p class="text-xs text-gray-400 italic">No se solicitará talle de remera en este evento.</p>
                                    @endif
                                </div>
                            </div>

                            <!-- Payment Instructions (shown only when Transferencia or Giros is selected) -->
                            @if(in_array('Transferencia', $payment_methods) || in_array('Giros', $payment_methods))
                            <div class="animate-fadeIn p-6 bg-blue-50 rounded-xl border border-blue-200 shadow-sm mt-6">
                                <div class="flex items-center mb-3">
                                    <svg class="w-5 h-5 text-blue-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    <h4 class="text-sm font-black text-blue-900 uppercase tracking-widest">
                                        Instrucciones para
                                        {{ implode(' / ', array_filter(['Transferencia', 'Giros'], fn($m) => in_array($m, $payment_methods))) }}
                                    </h4>
                                </div>
                                <textarea wire:model="payment_instructions" rows="5" placeholder="Ej: Transferir al Banco ITAÚ, Cuenta Corriente Nro. 1234567, a nombre de Juan Pérez, CI 1.234.567. Enviar comprobante al WhatsApp 0981-000000." class="w-full rounded-xl border-blue-200 bg-white shadow-sm focus:border-blue-500 focus:ring-blue-500 font-medium p-4 text-sm"></textarea>
                                <p class="text-[10px] text-blue-500 mt-2 font-bold uppercase tracking-widest">Este texto se mostrará al atleta cuando elija ese método de pago.</p>
                            </div>
                            @endif

                            <!-- Clubs Selection -->
                            <div class="p-6 bg-white rounded-xl border border-gray-200 shadow-sm mt-6">
                                <div class="flex items-center justify-between mb-4">
                                    <h4 class="text-sm font-black text-gray-900 uppercase tracking-widest">Clubes Participantes</h4>
                                    <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Opcional</span>
                                </div>
                                <p class="text-[10px] text-gray-500 mb-4 font-bold uppercase tracking-widest leading-relaxed">
                                    Si seleccionas clubes específicos, los atletas solo podrán elegir entre ellos. Si lo dejas vacío, podrán elegir cualquier club de la base de datos global.
                                </p>
                                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-3 max-h-60 overflow-y-auto p-2 bg-gray-50 rounded-lg border border-gray-100">
                                    @foreach($clubsList as $club)
                                        <label class="flex items-center p-2 bg-white rounded-lg border border-gray-100 cursor-pointer hover:border-red-200 transition-colors group">
                                            <input type="checkbox" wire:model="selected_clubs" value="{{ $club->id }}" class="w-4 h-4 text-red-600 border-gray-300 rounded focus:ring-red-600">
                                            <div class="ml-3 flex items-center gap-2">
                                                @if($club->logo)
                                                    <img src="{{ asset('storage/' . $club->logo) }}" class="w-5 h-5 rounded object-cover">
                                                @endif
                                                <span class="text-xs font-bold text-gray-700 group-hover:text-red-600 transition-colors">{{ $club->name }}</span>
                                            </div>
                                        </label>
                                    @endforeach
                                </div>
                                @if($clubsList->isEmpty())
                                    <div class="text-center py-4">
                                        <p class="text-xs text-gray-400 font-bold uppercase tracking-widest">No hay clubes registrados.</p>
                                        <a href="{{ route('admin.clubs') }}" class="text-[10px] text-red-600 font-black uppercase hover:underline mt-1 block">Gestionar Clubes</a>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endif
                @endif
                </div>
            </div>
        </div>

        <!-- Right Narrow Column: Sidebar Settings -->
        <div class="lg:col-span-1 space-y-6">
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-200">
                <h3 class="text-sm font-black text-gray-900 border-b border-gray-100 pb-3 mb-4 uppercase tracking-widest">Publicación</h3>
                
                <div class="space-y-4 mb-6">
                    <div class="flex items-center justify-between">
                        <span class="text-sm font-bold text-gray-600">Estado:</span>
                        <div class="flex items-center">
                            <input id="is_active" type="checkbox" wire:model="is_active" class="h-5 w-5 text-green-600 focus:ring-green-500 border-gray-300 rounded cursor-pointer">
                            <label for="is_active" class="ml-2 block text-sm font-bold text-gray-900 cursor-pointer">Activo</label>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-bold text-gray-600 mb-2">Fecha del Evento</label>
                            <input type="date" wire:model="date" class="w-full rounded-xl border-gray-200 bg-gray-50 focus:border-orange-500 focus:ring-orange-500 font-bold p-3 text-sm">
                            @error('date') <span class="text-red-500 text-[10px] font-bold mt-1">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-600 mb-2">Hora de Largada</label>
                            <input type="text" wire:model="start_time" placeholder="Ej: 08:00 hs" class="w-full rounded-xl border-gray-200 bg-gray-50 focus:border-orange-500 focus:ring-orange-500 font-bold p-3 text-sm">
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-600 mb-2">Cierre de Inscripción</label>
                        <input type="datetime-local" wire:model="registration_deadline" class="w-full rounded-xl border-gray-200 bg-gray-50 focus:border-orange-500 focus:ring-orange-500 font-bold p-3 text-sm">
                        @error('registration_deadline') <span class="text-red-500 text-[10px] font-bold mt-1">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-600 mb-2">Tipo de Evento</label>
                        <select wire:model="event_type_id" class="w-full rounded-xl border-gray-200 bg-gray-50 focus:border-orange-500 focus:ring-orange-500 font-bold p-3 text-sm">
                            <option value="">-- Seleccionar --</option>
                            @foreach($eventTypes as $type)
                                <option value="{{ $type->id }}">{{ $type->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-600 mb-2">Lista de Inscriptos (Enlace Externo)</label>
                        <input type="url" wire:model="external_inscribed_url" placeholder="https://..." class="w-full rounded-xl border-gray-200 bg-gray-50 focus:border-orange-500 focus:ring-orange-500 font-bold p-3 text-sm">
                        <p class="text-[10px] text-gray-400 mt-1 font-bold uppercase tracking-widest">Para mostrar una lista en otra plataforma.</p>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-600 mb-2">Enlace de Resultados</label>
                        <input type="url" wire:model="results_url" placeholder="https://..." class="w-full rounded-xl border-gray-200 bg-gray-50 focus:border-orange-500 focus:ring-orange-500 font-bold p-3 text-sm">
                        <p class="text-[10px] text-gray-400 mt-1 font-bold uppercase tracking-widest">Para eventos ya realizados.</p>
                    </div>
                </div>

                <div class="space-y-3">
                    <button wire:click="save" class="w-full py-3 bg-gradient-to-r from-orange-500 to-yellow-500 text-white font-black rounded-xl shadow-md hover:shadow-lg transition-all text-sm">
                        {{ $eventId ? 'Guardar Cambios' : 'Publicar Ahora' }}
                    </button>
                    <a href="{{ route('admin.events') }}" class="block w-full text-center py-3 border border-gray-200 rounded-xl text-sm font-bold text-gray-600 hover:bg-gray-50 transition-all">
                        Cancelar
                    </a>
                </div>
            </div>

            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-200">
                <h3 class="text-sm font-black text-gray-900 border-b border-gray-100 pb-3 mb-4 uppercase tracking-widest">Imagen Destacada</h3>
                
                <div class="space-y-4">
                    @if ($featured_image)
                        <div class="relative rounded-xl overflow-hidden border border-gray-200 shadow-inner">
                            <img src="{{ $featured_image->temporaryUrl() }}" class="w-full h-40 object-cover">
                            <div class="absolute inset-0 bg-black/20 flex items-center justify-center opacity-0 hover:opacity-100 transition-opacity">
                                <span class="bg-white text-xs font-black px-3 py-1 rounded-full shadow-sm text-gray-900">Previsualización</span>
                            </div>
                        </div>
                    @elseif ($existing_featured_image)
                        <div class="relative rounded-xl overflow-hidden border border-gray-200">
                            <img src="{{ asset('storage/' . $existing_featured_image) }}" class="w-full h-40 object-cover">
                        </div>
                    @else
                        <div class="w-full h-40 border-2 border-dashed border-gray-200 rounded-xl flex flex-col items-center justify-center text-gray-400 bg-gray-50/50">
                            <svg class="w-8 h-8 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            <span class="text-[10px] font-bold uppercase tracking-widest">Sin Imagen</span>
                        </div>
                    @endif

                    <div class="relative">
                        <input type="file" wire:model="featured_image" accept="image/*" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">
                        <div class="w-full py-2 bg-orange-50 text-orange-700 text-[10px] font-black uppercase tracking-widest text-center rounded-lg border border-orange-100 shadow-sm group-hover:bg-orange-100 transition-colors">
                            Seleccionar Archivo
                        </div>
                    </div>
                    @error('featured_image') <span class="text-red-500 text-[10px] font-bold block mt-1">{{ $message }}</span> @enderror
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function initEventEditor() {
    if (typeof tinymce === 'undefined') return;
    tinymce.remove('#event-description-editor');
    tinymce.init({
        selector: '#event-description-editor',
        plugins: 'code lists link image table fullscreen autolink media searchreplace wordcount',
        toolbar: 'undo redo | blocks | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist | link image media | table | code fullscreen',
        extended_valid_elements: '*[*]',
        promotion: false,
        branding: false,
        min_height: 400,
        images_upload_url: '{{ route("admin.upload-image") }}',
        images_upload_credentials: true,
        automatic_uploads: true,
        file_picker_types: 'image',
        images_upload_handler: function (blobInfo, progress) {
            return new Promise(function (resolve, reject) {
                var xhr = new XMLHttpRequest();
                xhr.open('POST', '{{ route("admin.upload-image") }}');
                var token = document.querySelector('meta[name="csrf-token"]');
                if (token) xhr.setRequestHeader('X-CSRF-TOKEN', token.content);
                xhr.upload.onprogress = function (e) {
                    progress(e.loaded / e.total * 100);
                };
                xhr.onload = function () {
                    if (xhr.status === 200) {
                        resolve(JSON.parse(xhr.responseText).location);
                    } else {
                        reject('Error al subir: ' + xhr.status);
                    }
                };
                xhr.onerror = function () { reject('Error de red'); };
                var formData = new FormData();
                formData.append('file', blobInfo.blob(), blobInfo.filename());
                xhr.send(formData);
            });
        },
        content_style: 'body { font-family: ui-sans-serif, system-ui, sans-serif; font-size: 16px; line-height: 1.6; color: #1f2937; padding: 1rem; } img { max-width: 100%; height: auto; } p { margin-bottom: 1rem; }',
        setup: function (editor) {
            editor.on('input change blur', function () {
                @this.set('description', editor.getContent());
            });
        }
    });
}
</script>
@endpush
