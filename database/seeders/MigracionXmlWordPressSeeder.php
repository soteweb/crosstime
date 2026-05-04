<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str; // Añadido para generar Slugs automáticamente

class MigracionXmlWordPressSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Limpieza de tablas (compatible con PostgreSQL)
        DB::statement('TRUNCATE TABLE event_categories, events, event_types RESTART IDENTITY CASCADE;');

        // 2. Ruta del XML
        $xmlPath = database_path('data/itapuacross.WordPress.2026-04-23.xml');

        if (!file_exists($xmlPath)) {
            $this->command->error("No se encontró el archivo XML en: " . $xmlPath);
            return;
        }

        // --- LÓGICA PARA IMÁGENES ---
        $rutaImagenes = public_path('storage/events');
        $archivosFisicos = File::exists($rutaImagenes) ? File::files($rutaImagenes) : [];
        
        $xml = simplexml_load_file($xmlPath);
        $count = 0;

        foreach ($xml->channel->item as $item) {
            $wp = $item->children('http://wordpress.org/export/1.2/');
            if ($wp->post_type != 'post' || $wp->status != 'publish') continue;

            $content = $item->children('http://purl.org/rss/1.0/modules/content/');
            
            try {
                $fecha = Carbon::parse((string)$wp->post_date);
            } catch (\Exception $e) {
                continue;
            }

            if ($fecha->year >= 2023) {
                
                // --- 3. CREACIÓN Y BÚSQUEDA AUTOMÁTICA DEL TIPO ---
                $nombreCategoriaXml = 'General'; 
                if (isset($item->category)) {
                    foreach ($item->category as $cat) {
                        if ($cat['domain'] == 'category' && (string)$cat != 'Uncategorized') {
                            $nombreCategoriaXml = (string)$cat;
                            break; 
                        }
                    }
                }

                // Buscamos si ya existe, si no, lo creamos automáticamente al vuelo
                $tipoId = DB::table('event_types')->updateOrInsert(
                    ['name' => $nombreCategoriaXml],
                    [
                        'slug' => Str::slug($nombreCategoriaXml),
                        'created_at' => now(),
                        'updated_at' => now()
                    ]
                );
                
                // Obtenemos el ID real (ya sea el existente o el nuevo)
                $tipoRealId = DB::table('event_types')->where('name', $nombreCategoriaXml)->value('id');

                // --- 4. ASIGNACIÓN ALEATORIA DE IMAGEN UUID ---
                $imagenParaBaseDatos = null;
                if (count($archivosFisicos) > 0) {
                    $archivoAleatorio = $archivosFisicos[array_rand($archivosFisicos)];
                    $imagenParaBaseDatos = $archivoAleatorio->getFilename();
                }

                // 5. Insertar en la tabla 'events' con el ID vinculado
                $eventId = DB::table('events')->insertGetId([
                    'title'            => (string) $item->title,
                    'description'      => (string) $content->encoded,
                    'event_type_id'    => $tipoRealId, // <--- ID vinculado automáticamente
                    'featured_image'   => $imagenParaBaseDatos,
                    'date'             => $fecha->format('Y-m-d'),
                    'location_address' => 'Itapúa, Paraguay',
                    'is_active'        => true,
                    'created_at'       => now(),
                    'updated_at'       => now(),
                ]);

                // 6. Categoría de inscripción
                DB::table('event_categories')->insert([
                    'event_id'   => $eventId,
                    'name'       => 'Inscripción General',
                    'price'      => 100000,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                $count++;
            }
        }

        $this->command->info("¡Éxito! Se crearon los tipos y se migraron {$count} eventos automáticamente.");
    }
}