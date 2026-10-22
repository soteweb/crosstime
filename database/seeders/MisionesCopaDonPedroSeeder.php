<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Event;
use App\Models\EventType;
use App\Models\EventDistance;
use App\Models\EventCategory;
use App\Models\Athlete;
use App\Models\Registration;
use Carbon\Carbon;

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

        // 3. Distancias (updateOrCreate para no romper relaciones existentes)
        $dist27 = EventDistance::updateOrCreate(
            ['event_id' => $event->id, 'name' => '27 KM (Promocional)']
        );

        $dist50 = EventDistance::updateOrCreate(
            ['event_id' => $event->id, 'name' => '50 KM (Principal / Elite)']
        );

        $price = 120000;

        // 4. Categorías para 27 KM (Masculino y Femenino)
        $cats27 = [
            ['name' => '27KM - Juvenil (12 a 18 años)', 'min' => 12, 'max' => 18],
            ['name' => '27KM - -30 (hasta 29 años)', 'min' => 19, 'max' => 29],
            ['name' => '27KM - +30 (30 a 39 años)', 'min' => 30, 'max' => 39],
            ['name' => '27KM - +40 (40 a 49 años)', 'min' => 40, 'max' => 49],
            ['name' => '27KM - +50 (50 años en adelante)', 'min' => 50, 'max' => 99],
        ];

        foreach (['M', 'F'] as $gender) {
            foreach ($cats27 as $c) {
                EventCategory::updateOrCreate(
                    [
                        'event_id' => $event->id,
                        'name' => $c['name'] . ' ' . ($gender === 'M' ? 'Masculino' : 'Femenino'),
                    ],
                    [
                        'event_distance_id' => $dist27->id,
                        'price' => $price,
                        'min_age' => $c['min'],
                        'max_age' => $c['max'],
                        'gender_restriction' => $gender,
                    ]
                );
            }
        }

        // 5. Categorías para 50 KM Masculino
        $cats50M = [
            ['name' => '50KM - Sub23 (19 a 22 años)', 'min' => 19, 'max' => 22],
            ['name' => '50KM - Elite (23 años en adelante)', 'min' => 23, 'max' => 99],
            ['name' => '50KM - Open (23 a 34 años)', 'min' => 23, 'max' => 34],
            ['name' => '50KM - Master A (35 a 39 años)', 'min' => 35, 'max' => 39],
            ['name' => '50KM - Master B (40 a 49 años)', 'min' => 40, 'max' => 49],
            ['name' => '50KM - Master C (50 años en adelante)', 'min' => 50, 'max' => 99],
        ];

        foreach ($cats50M as $c) {
            EventCategory::updateOrCreate(
                [
                    'event_id' => $event->id,
                    'name' => $c['name'] . ' Masculino',
                ],
                [
                    'event_distance_id' => $dist50->id,
                    'price' => $price,
                    'min_age' => $c['min'],
                    'max_age' => $c['max'],
                    'gender_restriction' => 'M',
                ]
            );
        }

        // 6. Categorías para 50 KM Femenino
        $cats50F = [
            ['name' => '50KM - Sub23 (19 a 22 años)', 'min' => 19, 'max' => 22],
            ['name' => '50KM - Elite (23 años en adelante)', 'min' => 23, 'max' => 99],
            ['name' => '50KM - Open (23 a 34 años)', 'min' => 23, 'max' => 34],
            ['name' => '50KM - Master A (35 a 39 años)', 'min' => 35, 'max' => 39],
            ['name' => '50KM - Master B (40 a 49 años)', 'min' => 40, 'max' => 49],
        ];

        foreach ($cats50F as $c) {
            EventCategory::updateOrCreate(
                [
                    'event_id' => $event->id,
                    'name' => $c['name'] . ' Femenino',
                ],
                [
                    'event_distance_id' => $dist50->id,
                    'price' => $price,
                    'min_age' => $c['min'],
                    'max_age' => $c['max'],
                    'gender_restriction' => 'F',
                ]
            );
        }

        // 7. Atletas e Inscripciones del HTML
        $athletesData = [
            [
                'dorsal' => '8',
                'ci' => '6027066',
                'name' => 'Cristhian',
                'last_name' => 'Centurión',
                'gender' => 'M',
                'dob' => '1995-04-08',
                'phone' => '0975613611',
                'email' => 'centupycenturion@gmail.com',
                'city' => 'Santa Rosa',
                'country' => 'Paraguay',
                'blood_type' => 'O+',
                'club' => 'Club de ciclistas roseños',
                'distance_name' => '50 KM (Principal / Elite)',
                'category_name' => '50KM - Open (23 a 34 años) Masculino',
                'payment_status' => 'pending',
                'payment_method' => 'Transferencia',
                'status' => 'pre_inscribed',
                'registration_date' => '2026-10-22 15:44:00',
                'observation' => null,
            ],
            [
                'dorsal' => '7',
                'ci' => '5166495',
                'name' => 'Fabian',
                'last_name' => 'Llano',
                'gender' => 'M',
                'dob' => '1996-01-31',
                'phone' => '0971729271',
                'email' => 'fabianllano023@gmail.com',
                'city' => 'San Juan Bautista',
                'country' => 'Paraguay',
                'blood_type' => 'O+',
                'club' => 'Team Aventure',
                'distance_name' => '50 KM (Principal / Elite)',
                'category_name' => '50KM - Open (23 a 34 años) Masculino',
                'payment_status' => 'pending',
                'payment_method' => 'Transferencia',
                'status' => 'pre_inscribed',
                'registration_date' => '2026-10-22 15:39:00',
                'observation' => null,
            ],
            [
                'dorsal' => '6',
                'ci' => '3660566',
                'name' => 'Limpio Concepción',
                'last_name' => 'Martinez Enrique',
                'gender' => 'M',
                'dob' => '1993-12-09',
                'phone' => '0985477308',
                'email' => 'enri93martinez@gmail.com',
                'city' => 'San Ignacio',
                'country' => 'Paraguay',
                'blood_type' => 'A+',
                'club' => 'Club Ignacionos de ciclista Cic',
                'distance_name' => '27 KM (Promocional)',
                'category_name' => '27KM - +30 (30 a 39 años) Masculino',
                'payment_status' => 'pending',
                'payment_method' => 'Transferencia',
                'status' => 'pre_inscribed',
                'registration_date' => '2026-10-22 15:20:00',
                'observation' => 'Pagado por el club Ignacianos de ciclista Cic',
            ],
            [
                'dorsal' => '5',
                'ci' => '6088452',
                'name' => 'Magali',
                'last_name' => 'Insfrán',
                'gender' => 'F',
                'dob' => '2007-02-12',
                'phone' => '0975132497',
                'email' => 'magaliinsfrangomez@gmail.com',
                'city' => 'San Juan Bautista',
                'country' => 'Paraguay',
                'blood_type' => 'O+',
                'club' => 'Team Aventure',
                'distance_name' => '27 KM (Promocional)',
                'category_name' => '27KM - -30 (hasta 29 años) Femenino',
                'payment_status' => 'pending',
                'payment_method' => 'Transferencia',
                'status' => 'pre_inscribed',
                'registration_date' => '2026-10-22 14:56:00',
                'observation' => null,
            ],
            [
                'dorsal' => '4',
                'ci' => '4537358',
                'name' => 'Juan Brito',
                'last_name' => 'Villalba Aguilera',
                'gender' => 'M',
                'dob' => '1996-03-21',
                'phone' => '0972791729',
                'email' => 'villalbaaguilerajuanbrito7@gmail.com',
                'city' => 'San Juan Bautista',
                'country' => 'Paraguay',
                'blood_type' => 'O+',
                'club' => 'Team aventure',
                'distance_name' => '27 KM (Promocional)',
                'category_name' => '27KM - +30 (30 a 39 años) Masculino',
                'payment_status' => 'pending',
                'payment_method' => 'Transferencia',
                'status' => 'pre_inscribed',
                'registration_date' => '2026-10-22 14:52:00',
                'observation' => null,
            ],
            [
                'dorsal' => '3',
                'ci' => '4545514',
                'name' => 'Romina del Pilar',
                'last_name' => 'Álvarez Meza',
                'gender' => 'F',
                'dob' => '1998-08-15',
                'phone' => '0972137407',
                'email' => 'delpilarmeza@gmail.com',
                'city' => 'Santa Rosa de Lima',
                'country' => 'Paraguay',
                'blood_type' => 'O+',
                'club' => 'Club de Ciclistas Roseños',
                'distance_name' => '27 KM (Promocional)',
                'category_name' => '27KM - -30 (hasta 29 años) Femenino',
                'payment_status' => 'pending',
                'payment_method' => 'Transferencia',
                'status' => 'pre_inscribed',
                'registration_date' => '2026-10-22 14:42:00',
                'observation' => null,
            ],
            [
                'dorsal' => '2',
                'ci' => '5571156',
                'name' => 'Nohelli',
                'last_name' => 'Nuñez',
                'gender' => 'F',
                'dob' => '1993-04-16',
                'phone' => '0981651900',
                'email' => 'nohelli562@gmail.com',
                'city' => 'San Juan',
                'country' => 'Paraguay',
                'blood_type' => 'O+',
                'club' => 'Team Aventure SJB',
                'distance_name' => '50 KM (Principal / Elite)',
                'category_name' => '50KM - Elite (23 años en adelante) Femenino',
                'payment_status' => 'pending',
                'payment_method' => 'Transferencia',
                'status' => 'pre_inscribed',
                'registration_date' => '2026-10-22 14:29:00',
                'observation' => null,
            ],
            [
                'dorsal' => '1',
                'ci' => '3613708',
                'name' => 'DERLIS',
                'last_name' => 'VARGAS',
                'gender' => 'M',
                'dob' => '1982-01-23',
                'phone' => '0983435755',
                'email' => 'derolimpero@gmail.com',
                'city' => 'ENCARNACION',
                'country' => 'Paraguay',
                'blood_type' => null,
                'club' => null,
                'distance_name' => '50 KM (Principal / Elite)',
                'category_name' => '50KM - Master B (40 a 49 años) Masculino',
                'payment_status' => 'pending',
                'payment_method' => 'Transferencia',
                'status' => 'pre_inscribed',
                'registration_date' => '2026-10-22 14:26:00',
                'observation' => null,
            ],
        ];

        foreach ($athletesData as $data) {
            // Unificar por CI para no duplicar si el atleta ya existe en la BD histórica
            $athlete = Athlete::updateOrCreate(
                ['identity_document' => $data['ci']],
                [
                    'name' => $data['name'],
                    'last_name' => $data['last_name'],
                    'gender' => $data['gender'],
                    'blood_type' => $data['blood_type'],
                    'club' => $data['club'],
                    'date_of_birth' => $data['dob'],
                    'city' => $data['city'],
                    'country' => $data['country'],
                    'email' => $data['email'],
                    'phone' => $data['phone'],
                ]
            );

            // Obtener la categoría y distancia en la base de datos
            $cat = EventCategory::where('event_id', $event->id)
                ->where('name', $data['category_name'])
                ->first();

            $dist = EventDistance::where('event_id', $event->id)
                ->where('name', $data['distance_name'])
                ->first();

            $regDate = Carbon::parse($data['registration_date']);

            // Crear o actualizar la inscripción
            Registration::updateOrCreate(
                [
                    'event_id' => $event->id,
                    'athlete_id' => $athlete->id,
                ],
                [
                    'event_category_id' => $cat ? $cat->id : null,
                    'event_distance_id' => $dist ? $dist->id : ($cat ? $cat->event_distance_id : null),
                    'bib_number' => $data['dorsal'],
                    'payment_status' => $data['payment_status'],
                    'status' => $data['status'],
                    'payment_method' => $data['payment_method'],
                    'observation' => $data['observation'],
                    'waiver_accepted_at' => $regDate,
                    'created_at' => $regDate,
                    'updated_at' => $regDate,
                ]
            );
        }

        $this->command->info('✅ Evento "Copa Don Pedro" y sus 8 atletas inscriptos han sido cargados con éxito.');
    }
}
