<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Event;
use App\Models\EventDistance;
use App\Models\EventCategory;
use App\Models\Athlete;
use App\Models\Registration;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class MisionesCopaDonPedroSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Eliminar el evento duplicado si existiera
        $duplicateEvent = Event::where('slug', 'encuentro-ciclistas-misiones-copa-don-pedro')->first();
        if ($duplicateEvent) {
            Registration::where('event_id', $duplicateEvent->id)->delete();
            $duplicateEvent->categories()->delete();
            $duplicateEvent->distances()->delete();
            $duplicateEvent->delete();
            $this->command->info('🗑️ Evento duplicado "encuentro-ciclistas-misiones-copa-don-pedro" eliminado con éxito.');
        }

        // 2. Localizar el evento ORIGINAL oficial
        $realEvent = Event::where('slug', 'encuentro-de-ciclistas-copa-don-pedro-4ta-edicion')->first();
        if (!$realEvent) {
            $realEvent = Event::find(57);
        }
        if (!$realEvent) {
            $realEvent = Event::where('title', 'like', '%COPA DON PEDRO%')->first();
        }

        if (!$realEvent) {
            $this->command->error('❌ No se encontró el evento oficial "ENCUENTRO DE CICLISTAS - COPA DON PEDRO 4TA. EDICION".');
            return;
        }

        $this->command->info("🎯 Evento oficial identificado: [ID {$realEvent->id}] {$realEvent->title}");

        // 3. Helper para buscar la categoría correspondiente en el evento oficial
        $findCategory = function($event, $distKm, $gender, $keyword) {
            // Distancia que coincida con 50 o 27
            $distance = $event->distances()
                ->where('name', 'like', "%{$distKm}%")
                ->first();

            $query = EventCategory::where('event_id', $event->id);
            if ($distance) {
                $query->where('event_distance_id', $distance->id);
            }
            if ($gender) {
                $query->where(function($q) use ($gender) {
                    $q->where('gender_restriction', $gender)
                      ->orWhereNull('gender_restriction');
                });
            }

            $categories = $query->get();
            foreach ($categories as $cat) {
                if (stripos($cat->name, $keyword) !== false) {
                    return [$cat, $distance ?? $cat->distance];
                }
            }

            // Búsqueda más amplia por si las restricciones de género difieren
            $fallbackCat = EventCategory::where('event_id', $event->id)
                ->where('name', 'like', "%{$keyword}%")
                ->first();

            return [$fallbackCat, $fallbackCat ? ($distance ?? $fallbackCat->distance) : null];
        };

        // 4. Datos de los 8 atletas
        $athletesData = [
            [
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
                'dist_km' => '50',
                'cat_keyword' => 'OPEN',
                'observation' => null,
                'registration_date' => '2026-10-22 15:44:00',
            ],
            [
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
                'dist_km' => '50',
                'cat_keyword' => 'OPEN',
                'observation' => null,
                'registration_date' => '2026-10-22 15:39:00',
            ],
            [
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
                'dist_km' => '27',
                'cat_keyword' => '+30',
                'observation' => 'Pagado por el club Ignacianos de ciclista Cic',
                'registration_date' => '2026-10-22 15:20:00',
            ],
            [
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
                'dist_km' => '27',
                'cat_keyword' => '-30',
                'observation' => null,
                'registration_date' => '2026-10-22 14:56:00',
            ],
            [
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
                'dist_km' => '27',
                'cat_keyword' => '+30',
                'observation' => null,
                'registration_date' => '2026-10-22 14:52:00',
            ],
            [
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
                'dist_km' => '27',
                'cat_keyword' => '-30',
                'observation' => null,
                'registration_date' => '2026-10-22 14:42:00',
            ],
            [
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
                'dist_km' => '50',
                'cat_keyword' => 'ELITE',
                'observation' => null,
                'registration_date' => '2026-10-22 14:29:00',
            ],
            [
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
                'dist_km' => '50',
                'cat_keyword' => 'MASTER B',
                'observation' => null,
                'registration_date' => '2026-10-22 14:26:00',
            ],
        ];

        foreach ($athletesData as $data) {
            // Actualizar o crear atleta por Cédula (no duplicar)
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

            // Verificar si ya está inscripto en el evento oficial
            $existingReg = Registration::where('event_id', $realEvent->id)
                ->where('athlete_id', $athlete->id)
                ->first();

            [$category, $distance] = $findCategory($realEvent, $data['dist_km'], $data['gender'], $data['cat_keyword']);

            if ($existingReg) {
                // Ya estaba inscripto, preservamos su dorsal original y actualizamos observación si la hubiera
                if (!empty($data['observation'])) {
                    $existingReg->update(['observation' => $data['observation']]);
                }
                $this->command->info("ℹ️ {$data['name']} {$data['last_name']} ya estaba inscripto en el evento oficial (Dorsal #{$existingReg->bib_number}).");
            } else {
                // Calcular el siguiente dorsal correlativo disponible en el evento oficial
                $maxBib = Registration::where('event_id', $realEvent->id)
                    ->get()
                    ->map(function ($r) {
                        return is_numeric($r->bib_number) ? (int)$r->bib_number : 0;
                    })
                    ->max();

                $nextBib = ($maxBib ?? 0) + 1;
                $regDate = Carbon::parse($data['registration_date']);

                Registration::create([
                    'event_id' => $realEvent->id,
                    'event_category_id' => $category ? $category->id : null,
                    'event_distance_id' => $distance ? $distance->id : ($category ? $category->event_distance_id : null),
                    'athlete_id' => $athlete->id,
                    'bib_number' => (string)$nextBib,
                    'payment_status' => 'pending',
                    'status' => 'pre_inscribed',
                    'payment_method' => 'Transferencia',
                    'observation' => $data['observation'],
                    'waiver_accepted_at' => $regDate,
                    'created_at' => $regDate,
                    'updated_at' => $regDate,
                ]);

                $catName = $category ? $category->name : 'N/A';
                $this->command->info("✅ Inscripto con éxito: {$data['name']} {$data['last_name']} -> Dorsal #{$nextBib} en categoría {$catName}");
            }
        }

        $total = Registration::where('event_id', $realEvent->id)->count();
        $this->command->info("🏁 Proceso finalizado. Total de inscriptos en el evento oficial: {$total}");
    }
}
