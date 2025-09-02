<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('aulas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('escola_id')->constrained('escolas')->onDelete('cascade');
            $table->string('dia', 3)->index();
            $table->time('horario_inicio');
            $table->time('horario_termino');
            $table->string('descricao');
            $table->boolean('aula_fixa');
            $table->string('turma')->nullable();
            $table->string('sala')->nullable();
            $table->json('professores')->nullable();
            $table->json('disciplinas')->nullable();
            $table->json('recursos')->nullable();
            $table->string('local')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
