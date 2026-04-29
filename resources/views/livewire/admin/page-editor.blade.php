<div>
    <x-slot name="title">{{ $pageId ? 'Editar Página' : 'Nueva Página' }} - Itapúa Admin</x-slot>

    <!-- Editor Dependencies -->
    <div wire:ignore>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/6.8.3/tinymce.min.js" referrerpolicy="origin"></script>
    </div>

    <div class="mb-8 flex items-center justify-between">
        <div class="flex items-center">
            <a href="{{ route('admin.pages') }}" class="mr-4 text-gray-400 hover:text-red-600 transition-colors">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            </a>
            <div>
                <h1 class="text-3xl font-bold text-gray-900 border-l-4 border-red-600 pl-4 mb-2">
                    {{ $pageId ? 'Editar Página' : 'Añadir Nueva Página' }}
                </h1>
            </div>
        </div>
        <div class="hidden md:flex space-x-3">
            <a href="{{ route('admin.pages') }}" class="px-6 py-2 border border-gray-300 text-gray-700 font-bold rounded-xl hover:bg-gray-50 transition-colors shadow-sm">Cancelar</a>
            <button wire:click="store" class="block w-full sm:w-auto px-8 py-2 bg-red-600 text-white font-bold rounded-xl hover:bg-red-700 transition-all shadow-md hover:shadow-lg shadow-red-500/30">
                {{ $pageId ? 'Actualizar CMS' : 'Publicar' }}
            </button>
        </div>
    </div>

    <!-- Main Editor Fullscreen Layout -->
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-8" x-data="{
        initEditor() {
            if (typeof tinymce !== 'undefined') {
                tinymce.init({
                    selector: '#cms-editor-full',
                    plugins: 'code lists link image table fullscreen autolink media searchreplace wordcount',
                    toolbar: 'undo redo | blocks | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist | link image media | table | code fullscreen',
                    extended_valid_elements: '*[*]',
                    promotion: false,
                    branding: false,
                    min_height: 600,
                    content_style: `
                        body { font-family: ui-sans-serif, system-ui, sans-serif; font-size: 16px; line-height: 1.6; color: #1f2937; padding: 1rem; }
                        svg { max-width: 24px; max-height: 24px; display: inline-block; vertical-align: middle; fill: currentColor; }
                        img { max-width: 100%; height: auto; border-radius: 0.5rem; }
                        p { margin-bottom: 1rem; }
                        h1, h2, h3, h4 { font-weight: 800; margin-top: 2rem; margin-bottom: 1rem; color: #111827; }
                        a { color: #E30613; text-decoration: none; font-weight: 600; }
                        a:hover { text-decoration: underline; }
                    `,
                    setup: function (editor) {
                        editor.on('input change blur', function () {
                            @this.set('content', editor.getContent());
                        });
                    }
                });
            }
        }
    }" x-init="initEditor()">
    
        <!-- Left Wide Column: Visual Editor -->
        <div class="lg:col-span-3 space-y-6">
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-200">
                <input type="text" wire:model="title" placeholder="Añade un título" class="w-full text-3xl font-bold text-gray-900 placeholder-gray-300 border-0 border-b-2 border-transparent hover:border-gray-100 focus:border-red-600 focus:ring-0 px-0 py-2 transition-colors mb-6">
                @error('title') <span class="text-red-500 text-sm font-bold block mb-4 -mt-4">{{ $message }}</span> @enderror
                
                <div wire:ignore class="rounded-xl overflow-hidden border border-gray-300 shadow-sm focus-within:border-red-600 focus-within:ring-1 focus-within:ring-red-600 transition-shadow">
                    <textarea id="cms-editor-full" class="w-full opacity-0">{{ $content }}</textarea>
                </div>
                @error('content') <span class="text-red-500 text-sm font-bold block mt-2">{{ $message }}</span> @enderror
            </div>
        </div>

        <!-- Right Narrow Column: Settings -->
        <div class="lg:col-span-1 space-y-6">
            
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-200">
                <h3 class="text-base font-bold text-gray-900 border-b border-gray-100 pb-3 mb-4">Publicación</h3>
                
                <div class="space-y-4">
                    <div class="flex items-center justify-between">
                        <span class="text-sm font-bold text-gray-600">Estado:</span>
                        <div class="flex items-center">
                            <input id="is_active" type="checkbox" wire:model="is_active" class="h-5 w-5 text-green-600 focus:ring-green-500 border-gray-300 rounded cursor-pointer transition-colors">
                            <label for="is_active" class="ml-2 block text-sm font-bold text-gray-900 cursor-pointer select-none">
                                Público
                            </label>
                        </div>
                    </div>
                </div>

                <div class="mt-6 pt-4 border-t border-gray-100 flex flex-col space-y-3">
                    <button wire:click="store" class="w-full flex justify-center py-3 px-4 border border-transparent rounded-xl shadow-md text-sm font-bold text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-600 transition-all">
                        {{ $pageId ? 'Actualizar Página' : 'Publicar Ahora' }}
                    </button>
                    @if($pageId && $slug)
                    <a href="/{{ $slug }}" target="_blank" class="w-full text-center py-3 px-4 border border-gray-300 rounded-xl shadow-sm text-sm font-bold text-gray-700 bg-white hover:bg-gray-50 transition-colors">
                        Vista Previa
                    </a>
                    @endif
                </div>
            </div>

            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-200">
                <h3 class="text-base font-bold text-gray-900 border-b border-gray-100 pb-3 mb-4">Atributos de Página</h3>
                
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Slug (URL)</label>
                    <div class="flex rounded-lg shadow-sm">
                        <span class="inline-flex items-center px-3 rounded-l-lg border border-r-0 border-gray-300 bg-gray-50 text-gray-500 sm:text-xs">
                          itapuacross.com.py/
                        </span>
                        <input type="text" wire:model="slug" class="flex-1 min-w-0 block w-full px-3 py-2 rounded-none rounded-r-lg focus:ring-red-600 focus:border-red-600 sm:text-sm border-gray-300 bg-white placeholder-gray-300" placeholder="contacto">
                    </div>
                    <p class="text-xs text-gray-400 mt-2">Déjalo en blanco para auto-asignarlo desde el Título.</p>
                    @error('slug') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>
            </div>

        </div>
    </div>
</div>
