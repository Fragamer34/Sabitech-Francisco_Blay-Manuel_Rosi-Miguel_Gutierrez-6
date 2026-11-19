<?php

namespace Database\Seeders;

use App\Models\Charla;
use App\Models\Ponente;
use App\Models\Sala;
use Illuminate\Database\Seeder;

/**
 * Datos de prueba para el calendario: 1 ponente, 2 salas y 4 charlas en el mes actual.
 * Se puede ejecutar varias veces sin duplicar nada:
 *   php artisan db:seed --class=CharlasDemoSeeder
 */
class CharlasDemoSeeder extends Seeder
{
    public function run(): void
    {
        $ponente = Ponente::firstOrCreate(
            ['emailPonente' => 'ponente.demo@sabitech.test'],
            [
                'nombrePonente'       => 'Laura',
                'apellidosPonente'    => 'Martín Soler',
                'especialidadPonente' => 'Desarrollo web',
                'biografiaPonente'    => 'Ponente de ejemplo para probar el calendario.',
            ],
        );

        $salaA = Sala::firstOrCreate(
            ['nombreSala' => 'Sala A'],
            ['ubicacionSala' => 'Planta baja', 'capacidadSala' => 40],
        );
        $salaB = Sala::firstOrCreate(
            ['nombreSala' => 'Sala B'],
            ['ubicacionSala' => 'Primera planta', 'capacidadSala' => 20],
        );

        // Fechas relativas a hoy para que siempre haya charlas visibles y reservables
        $charlas = [
            ['Introducción a React', 'Rutas, controladores y Eloquent.',        today(),                       '10:00', '11:30', $salaA, 30, 'programada'],
            ['MySQL avanzado',         'Índices, joins y optimización.',           today(),                       '12:00', '13:00', $salaB, 20, 'programada'],
            ['Seguridad web',          'XSS, CSRF e inyección SQL.',               today()->addDays(7),           '16:00', '17:30', $salaA, 2,  'programada'],
            ['Git en equipo',          'Ramas, pull requests y conflictos.',       today()->addDays(3),           '09:30', '10:30', $salaA, 30, 'cancelada'],
        ];

        foreach ($charlas as [$titulo, $contenido, $fecha, $inicio, $fin, $sala, $aforo, $estado]) {
            Charla::firstOrCreate(
                ['tituloCharla' => $titulo, 'fechaCharla' => $fecha->toDateString()],
                [
                    'contenidoCharla'  => $contenido,
                    'idPonente'        => $ponente->idPonente,
                    'idSala'           => $sala->idSala,
                    'horaInicioCharla' => $inicio,
                    'horaFinCharla'    => $fin,
                    'aforoCharla'      => $aforo,
                    'estadoCharla'     => $estado,
                ],
            );
        }
    }
}
