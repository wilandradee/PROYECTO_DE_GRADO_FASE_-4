<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EvaluacionRiesgo extends Model
{
    protected $table = 'evaluaciones_riesgo';

    protected $fillable = [
        'estudiante_id',
        'datos',
        'puntaje',
    ];

    protected $casts = [
        'datos' => 'array'
    ];

    public function estudiante()
    {
        return $this->belongsTo(Estudiante::class, 'estudiante_id');
    }
}
