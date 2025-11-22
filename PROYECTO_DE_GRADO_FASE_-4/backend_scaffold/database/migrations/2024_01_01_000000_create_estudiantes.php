<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::create('estudiantes', function (Blueprint $table) {
            $table->id();
            $table->string('codigo')->nullable();
            $table->string('nombre');
            $table->string('correo_electronico')->unique();
            $table->string('programa_academico')->nullable();
            $table->integer('semestre_actual')->nullable();
            $table->decimal('promedio_academico',4,2)->nullable();
            $table->decimal('asistencia_porcentaje',5,2)->nullable();
            $table->integer('nivel_estres')->nullable();
            $table->integer('situacion_economica')->nullable();
            $table->decimal('puntaje_riesgo_total',4,2)->nullable();
            $table->string('nivel_riesgo')->nullable();
            $table->timestamp('fecha_ultima_evaluacion')->nullable();
            $table->timestamps();
        });
    }
    public function down() {
        Schema::dropIfExists('estudiantes');
    }
};
