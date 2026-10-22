<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Event;
use App\Models\EventType;
use App\Models\EventDistance;
use App\Models\EventCategory;

class MisionesCopaDonPedroSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Tipo de evento
        $eventType = EventType::firstOrCreate(
            ['slug' => 'ciclismo'],
            ['name' => 'Ciclismo', 'color' => '#ef4444']
        );

        // 2. Crear o actualizar Evento
        $event = Event::updateOrCreate(
            ['slug' => 'encuentro-ciclistas-misiones-copa-don-pedro'],
            [
                'title' => 'Encuentro de Ciclistas en Misiones | Copa Don Pedro',
                'description' => '🏆 Final del Campeonato - Copa Don Pedro. Evento oficial organizado por Team Aventure y Círculo Misionense. Sede: La Floresta Hotel.',
                'date' => '2026-10-25',
                'start_time' => '08:30:00',
                'location_address' => 'La Floresta Hotel, San Ignacio, Misiones',
                'organizer' => 'Team Aventure & Círculo Misionense',
                'event_type_id' => $eventType->id,
                'is_active' => true,
                'registration_type' => 'internal',
                'payment_instructions' => "<p><strong>Titular:</strong> ITAPUA CROSS EAS &mdash; <strong>RUC:</strong> 80142717-7</p>
<p><strong>ueno bank:</strong> Caja de Ahorro: 6191647271</p>
<p><strong>Giros Tigo:</strong> 0985-724233</p>
<p><strong>Tarifa Única:</strong> Gs. 120.000</p>
<p>Una vez efectuada tu transferencia o giro de Gs. 120.000, guarda tu comprobante de pago para presentarlo en la acreditación.</p>",
            ]
        );

        // 3. Limpiar distancias y categorías previas de este evento si existieran
        $event->categories()->delete();
        $event->distances()->delete();

        // 4. Distancias
        $dist27 = EventDistance::create([
            'event_id' => $event->id,
            'name' => '27 KM (Promocional)',
        ]);

        $dist50 = EventDistance::create([
            'event_id' => $event->id,
            'name' => '50 KM (Principal / Elite)',
        ]);

        $price = 120000;

        // 5. Categorías para 27 KM (Masculino y Femenino)
        $cats27 = [
            ['name' => '27KM - Juvenil (12 a 18 años)', 'min' => 12, 'max' => 18],
            ['name' => '27KM - -30 (hasta 29 años)', 'min' => 19, 'max' => 29],
            ['name' => '27KM - +30 (30 a 39 años)', 'min' => 30, 'max' => 39],
            ['name' => '27KM - +40 (40 a 49 años)', 'min' => 40, 'max' => 49],
            ['name' => '27KM - +50 (50 años en adelante)', 'min' => 50, 'max' => 99],
        ];

        foreach (['M', 'F'] as $gender) {
            foreach ($cats27 as $c) {
                EventCategory::create([
                    'event_id' => $event->id,
                    'event_distance_id' => $dist27->id,
                    'name' => $c['name'] . ' ' . ($gender === 'M' ? 'Masculino' : 'Femenino'),
                    'price' => $price,
                    'min_age' => $c['min'],
                    'max_age' => $c['max'],
                    'gender_restriction' => $gender,
                ]);
            }
        }

        // 6. Categorías para 50 KM Masculino
        $cats50M = [
            ['name' => '50KM - Sub23 (19 a 22 años)', 'min' => 19, 'max' => 22],
            ['name' => '50KM - Elite (23 años en adelante)', 'min' => 23, 'max' => 99],
            ['name' => '50KM - Open (23 a 34 años)', 'min' => 23, 'max' => 34],
            ['name' => '50KM - Master A (35 a 39 años)', 'min' => 35, 'max' => 39],
            ['name' => '50KM - Master B (40 a 49 años)', 'min' => 40, 'max' => 49],
            ['name' => '50KM - Master C (50 años en adelante)', 'min' => 50, 'max' => 99],
        ];

        foreach ($cats50M as $c) {
            EventCategory::create([
                'event_id' => $event->id,
                'event_distance_id' => $dist50->id,
                'name' => $c['name'] . ' Masculino',
                'price' => $price,
                'min_age' => $c['min'],
                'max_age' => $c['max'],
                'gender_restriction' => 'M',
            ]);
        }

        // 7. Categorías para 50 KM Femenino (según reglamento oficial, sin Master C)
        $cats50F = [
            ['name' => '50KM - Sub23 (19 a 22 años)', 'min' => 19, 'max' => 22],
            ['name' => '50KM - Elite (23 años en adelante)', 'min' => 23, 'max' => 99],
            ['name' => '50KM - Open (23 a 34 años)', 'min' => 23, 'max' => 34],
            ['name' => '50KM - Master A (35 a 39 años)', 'min' => 35, 'max' => 39],
            ['name' => '50KM - Master B (40 a 49 años)', 'min' => 40, 'max' => 49],
        ];

        foreach ($cats50F as $c) {
            EventCategory::create([
                'event_id' => $event->id,
                'event_distance_id' => $dist50->id,
                'name' => $c['name'] . ' Femenino',
                'price' => $price,
                'min_age' => $c['min'],
                'max_age' => $c['max'],
                'gender_restriction' => 'F',
            ]);
        }
    }
}
