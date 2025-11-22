<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;

Route::get('/dashboard/tiempo-real', [DashboardController::class, 'obtenerDatosTiempoReal']);
Route::post('/estudiantes/{id}/evaluar-riesgo', [DashboardController::class, 'evaluarRiesgoEstudiante']);

// Endpoint placeholder for intervenciones
Route::post('/intervenciones', function (Request $request) {
    // Implement logic to register interventions, link to alertas or registros.
    return response()->json(['exito' => true, 'mensaje' => 'Intervención registrada (placeholder)']);
});
