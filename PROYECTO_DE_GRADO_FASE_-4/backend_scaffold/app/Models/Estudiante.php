<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Estudiante extends Model
{
    protected $table = 'estudiantes';

    protected $fillable = [
        'codigo',
        'nombre',
        'correo_electronico',
        'programa_academico',
        'semestre_actual',
        'promedio_academico',
        'asistencia_porcentaje',
        'nivel_estres',
        'situacion_economica',
        'puntaje_riesgo_total',
        'nivel_riesgo',
        'fecha_ultima_evaluacion',
    ];

    protected $casts = [
        'promedio_academico' => 'decimal:2',
        'asistencia_porcentaje' => 'decimal:2',
        'nivel_estres' => 'integer',
        'situacion_economica' => 'integer',
        'puntaje_riesgo_total' => 'decimal:2',
        'fecha_ultima_evaluacion' => 'datetime',
    ];

    public function evaluacionesRiesgo()
    {
        return $this->hasMany(EvaluacionRiesgo::class, 'estudiante_id');
    }

    public function alertas()
    {
        return $this->hasMany(Alerta::class, 'estudiante_id');
    }

    public function calcularPuntajeRiesgo(): float
    {
        $pesos = [
            'academico' => 0.40,
            'emocional' => 0.35,
            'economico' => 0.25,
        ];

        $puntajeAcademico = $this->calcularRiesgoAcademico();
        $puntajeEmocional = $this->calcularRiesgoEmocional();
        $puntajeEconomico = $this->calcularRiesgoEconomico();

        $this->puntaje_riesgo_total = round(
            $puntajeAcademico * $pesos['academico'] +
            $puntajeEmocional * $pesos['emocional'] +
            $puntajeEconomico * $pesos['economico'],
            2
        );

        $this->nivel_riesgo = $this->determinarNivelRiesgo($this->puntaje_riesgo_total);
        $this->fecha_ultima_evaluacion = now();

        $this->save();

        return (float) $this->puntaje_riesgo_total;
    }

    private function calcularRiesgoAcademico(): float
    {
        $puntaje = 0;

        if ($this->promedio_academico < 3.0) $puntaje += 8;
        elseif ($this->promedio_academico < 3.5) $puntaje += 5;

        if ($this->asistencia_porcentaje < 70) $puntaje += 7;
        elseif ($this->asistencia_porcentaje < 80) $puntaje += 4;

        return min($puntaje, 10);
    }

    private function calcularRiesgoEmocional(): float
    {
        return (float) $this->nivel_estres;
    }

    private function calcularRiesgoEconomico(): float
    {
        return (float) $this->situacion_economica;
    }

    private function determinarNivelRiesgo(float $puntaje): string
    {
        if ($puntaje >= 7.5) return 'alto';
        if ($puntaje >= 5.0) return 'medio';
        return 'bajo';
    }

    public function generarAlertaSiEsNecesario(): void
    {
        if ($this->nivel_riesgo === 'alto') {
            Alerta::create([
                'estudiante_id' => $this->id,
                'tipo' => 'riesgo_alto',
                'mensaje' => 'Estudiante identificado con alto riesgo de deserción',
                'prioridad' => 'alta',
                'estado' => 'activa'
            ]);
        }
    }
}
