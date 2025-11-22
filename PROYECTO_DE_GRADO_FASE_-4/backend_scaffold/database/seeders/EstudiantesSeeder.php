<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Estudiante;

class EstudiantesSeeder extends Seeder
{
    public function run()
    {
        Estudiante::create([
            'codigo' => 'E001',
            'nombre' => 'Juan Pérez',
            'correo_electronico' => 'juan.perez@example.com',
            'programa_academico' => 'Ingeniería',
            'semestre_actual' => 3,
            'promedio_academico' => 3.4,
            'asistencia_porcentaje' => 85.0,
            'nivel_estres' => 4,
            'situacion_economica' => 3,
        ]);
    }
}
