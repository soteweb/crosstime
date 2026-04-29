<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use App\Models\Event;
use Carbon\Carbon;

class ImportWPEvents extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'wp:import-events';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Importa eventos desde el REST API de WordPress de Itapua Cross';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Iniciando extracción desde la API de WordPress (Itapúa Cross)...');

        // Using 20 as limit to prevent Guzzle connect exceptions on slow servers
        $url = 'https://itapuacross.com.py/wp-json/wp/v2/posts?per_page=20';
        $response = Http::withoutVerifying()->get($url);

        if ($response->failed()) {
            $this->error('No se pudo conectar a la API.');
            return;
        }

        $posts = $response->json();
        $this->info('Se encontraron ' . count($posts) . ' publicaciones recientes.');

        foreach ($posts as $post) {
            $title = html_entity_decode(strip_tags($post['title']['rendered']));
            
            // Si el titulo ya existe (de los seeders) lo evitamos
            if (Event::where('title', 'like', "%{$title}%")->exists()) {
                $this->warn("El evento '{$title}' ya existe en la base de datos local. Saltando...");
                continue;
            }

            $this->info("Importando: " . $title);

            // Generar un extracto corto de la descripción (limpiando el HTML gigantesco)
            $descriptionRaw = html_entity_decode(strip_tags($post['excerpt']['rendered'] ?? $post['content']['rendered']));
            $description = \Illuminate\Support\Str::words($descriptionRaw, 40, '...');

            // Establecemos datos mock para los faltantes que un WP no tiene en sus posts normales (locations, map logic)
            // Asumiremos que es Encarnación de forma predeterminada
            
            $date = Carbon::parse($post['date'])->addMonth()->format('Y-m-d'); // Empujamos la fecha para que se vea en el futuro como 'próximos eventos'

            $event = Event::create([
                'title' => $title,
                'description' => $description,
                'date' => $date,
                'location_address' => 'Ubicación Pendiente - Itapúa',
                'latitude' => -27.3305,
                'longitude' => -55.8667,
                'is_active' => true,
            ]);

            // Categoría base generica
            $event->categories()->create([
                'name' => 'Categoría General (Importado)',
                'price' => 80000
            ]);
        }

        $this->info('¡Migración Finalizada Exitosamente!');
    }
}
