<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ResultsPageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $path = resource_path('views/resultados.blade.php');
        if (file_exists($path)) {
            $html = file_get_contents($path);
            \App\Models\Page::updateOrCreate(
                ['slug' => 'clasificaciones'],
                [
                    'title' => 'Resultados',
                    'content' => $html,
                    'is_active' => true,
                ]
            );
        }
    }
}
