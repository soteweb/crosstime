<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Event;

class EventSeeder extends Seeder
{
    public function run(): void
    {
        $events = [
            [
                'title' => 'EPOPEYA DE LA INDEPENDENCIA',
                'description' => 'Gran evento de running con un circuito histórico impresionante en conmemoración de nuestra independencia.',
                'date' => '2026-05-14',
                'location_address' => 'Encarnación, Costanera Republica del Paraguay',
                'latitude' => -27.3305,
                'longitude' => -55.8667,
                'is_active' => true,
                'categories' => [
                    ['name' => '5K General', 'price' => 80000],
                    ['name' => '10K Élite', 'price' => 120000],
                ]
            ],
            [
                'title' => 'SAN JUAN CORRE 5K, 10K y 21K',
                'description' => 'La tradicional corrida de San Juan vuelve con más fuerza. Prepárate para el asfalto y el calor de la competencia.',
                'date' => '2026-06-24',
                'location_address' => 'Plaza de Armas, Encarnación',
                'latitude' => -27.3333,
                'longitude' => -55.8666,
                'is_active' => true,
                'categories' => [
                    ['name' => '5K Amateur', 'price' => 70000],
                    ['name' => '10K Avanzado', 'price' => 100000],
                    ['name' => '21K Profesional', 'price' => 150000],
                ]
            ],
            [
                'title' => 'COPA CAMBYRETA XCO 2026',
                'description' => 'El mejor circuito de Mountain Bike de la región. Barro, saltos y mucha adrenalina pura te esperan.',
                'date' => '2026-08-15',
                'location_address' => 'Cambyretá, Itapúa',
                'latitude' => -27.3000,
                'longitude' => -55.8500,
                'is_active' => true,
                'categories' => [
                    ['name' => 'Categoría Principal XCO', 'price' => 90000],
                    ['name' => 'Promocional', 'price' => 60000],
                ]
            ]
        ];

        foreach ($events as $e) {
            $cat = $e['categories'];
            unset($e['categories']);
            $event = Event::create($e);
            foreach ($cat as $c) {
                $event->categories()->create($c);
            }
        }
    }
}
