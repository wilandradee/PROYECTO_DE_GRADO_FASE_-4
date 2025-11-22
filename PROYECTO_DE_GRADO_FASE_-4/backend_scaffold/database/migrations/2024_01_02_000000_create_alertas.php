<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::create('alertas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('estudiante_id')->constrained('estudiantes')->onDelete('cascade');
            $table->string('tipo')->nullable();
            $table->text('mensaje');
            $table->string('prioridad')->default('media');
            $table->string('estado')->default('activa');
            $table->timestamps();
        });
    }
    public function down() {
        Schema::dropIfExists('alertas');
    }
};
