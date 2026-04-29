<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <!-- Header Decorativo del CMS -->
    <div class="mb-10 text-center md:text-left">
        <h1 class="text-4xl md:text-6xl font-black text-gray-900 tracking-tighter mb-4">{{ $page->title }}</h1>
        <div class="h-1.5 w-24 bg-orange-500 rounded-full mx-auto md:mx-0"></div>
    </div>

    <!-- Contenido Trix / CMS (Debe ser renderizado como HTML crudo) -->
    <div class="prose prose-lg prose-orange max-w-none prose-img:rounded-2xl prose-img:shadow-lg prose-headings:font-black bg-white p-8 md:p-12 rounded-3xl shadow-sm border border-gray-100">
        {!! $page->content !!}
    </div>
</div>
