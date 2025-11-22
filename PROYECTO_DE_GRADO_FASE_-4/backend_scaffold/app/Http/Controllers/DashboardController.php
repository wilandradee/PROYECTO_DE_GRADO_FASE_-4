<?php

namespace App\Http\Controllers;

use App\Models\Estudiante;
use App\Models\Alerta;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Exception;
use Illuminate\Support\Facades\Log;

class DashboardController extends Controller
{
    public function obtenerDatosTiempoReal(): JsonResponse
    {
        $datosDashboard = [
            'metricas' => [
                'total_estudiantes' => Estudiante::count(),
                'estudiantes_alto_riesgo' => Estudiante::where('nivel_riesgo', 'alto')->count(),
                'estudiantes_medio_riesgo' => Estudiante::where('nivel_riesgo', 'medio')->count(),
                'alertas_activas' => Alerta::where('estado', 'activa')->count(),
                'intervenciones_hoy' => Alerta::whereDate('created_at', today())
                    ->where('estado', 'resuelta')
                    ->count()
            ],
            'alertas_recientes' => Alerta::with('estudiante')
                ->where('estado', 'activa')
                ->orderBy('created_at', 'desc')
                ->limit(10)
                ->get()
                ->map(function ($alerta) {
                    return [
                        'id' => $alerta->id,
                        'mensaje' => $alerta->mensaje,
                        'prioridad' => $alerta->prioridad,
                        'estudiante' => [
                            'id' => $alerta->estudiante->id,
                            'nombre' => $alerta->estudiante->nombre,
                            'programa' => $alerta->estudiante->programa_academico
                        ],
                        'fecha_creacion' => $alerta->created_at->toIsoString()
                    ];
                }),
            'tendencias_riesgo' => $this->obtenerTendenciasRiesgo(),
            'ultima_actualizacion' => now()->toIsoString()
        ];

        return response()->json($datosDashboard);
    }

    public function evaluarRiesgoEstudiante(Request $request, int $estudianteId): JsonResponse
    {
        $validado = $request->validate([
            'datos_academicos.promedio' => 'required|numeric',
            'datos_academicos.asistencia' => 'required|numeric',
            'datos_emocionales.nivel_estres' => 'required|integer',
            'datos_economicos.situacion_economica' => 'required|integer'
        ]);

        try {
            $estudiante = Estudiante::findOrFail($estudianteId);

            $estudiante->update([
                'promedio_academico' => $validado['datos_academicos']['promedio'],
                'asistencia_porcentaje' => $validado['datos_academicos']['asistencia'],
                'nivel_estres' => $validado['datos_emocionales']['nivel_estres'],
                'situacion_economica' => $validado['datos_economicos']['situacion_economica']
            ]);

            $puntajeRiesgo = $estudiante->calcularPuntajeRiesgo();
            $estudiante->generarAlertaSiEsNecesario();

            return response()->json([
                'exito' => true,
                'estudiante' => $estudiante->nombre,
                'puntaje_riesgo' => $puntajeRiesgo,
                'nivel_riesgo' => $estudiante->nivel_riesgo,
                'recomendaciones' => $this->generarRecomendaciones($estudiante->nivel_riesgo)
            ]);

        } catch (Exception $e) {
            Log::error('Error evaluando riesgo estudiante: ' . $e->getMessage());

            return response()->json([
                'exito' => false,
                'error' => 'Error en el proceso de evaluación'
            ], 500);
        }
    }

    private function obtenerTendenciasRiesgo(): array
    {
        return [
            'riesgo_alto_ultima_semana' => Estudiante::where('nivel_riesgo', 'alto')
                ->whereDate('fecha_ultima_evaluacion', '>=', now()->subWeek())
                ->count(),
            'tendencia_semanal' => 'estable'
        ];
    }

    private function generarRecomendaciones(string $nivelRiesgo): array
    {
        if ($nivelRiesgo === 'alto') {
            return [
                'Intervención inmediata requerida',
                'Contactar al estudiante en máximo 24 horas',
                'Coordinar cita con bienestar institucional',
                'Evaluar apoyo económico si aplica'
            ];
        } elseif ($nivelRiesgo === 'medio') {
            return [
                'Seguimiento quincenal',
                'Monitorear asistencia académica',
                'Ofrecer tutorías académicas'
            ];
        } else {
            return [
                'Seguimiento mensual rutinario',
                'Mantener comunicación abierta'
            ];
        }
    }
}
