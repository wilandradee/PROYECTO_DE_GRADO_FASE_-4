<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::create('evaluaciones_riesgo', function (Blueprint $table) {
            $table->id();
            $table->foreignId('estudiante_id')->constrained('estudiantes')->onDelete('cascade');
            $table->json('datos')->nullable();
            $table->decimal('puntaje',4,2)->nullable();
            $table->timestamps();
        });
    }
    public function down() {
        Schema::dropIfExists('evaluaciones_riesgo');
    }
};
