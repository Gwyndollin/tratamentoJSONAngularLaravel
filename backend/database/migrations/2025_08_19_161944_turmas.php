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
        Schema::create('turmas', function(Blueprint $table){
            $table->id();
            $table->string('nome');
            $table->unsignedBigInteger('id_externo')->nullable()->index();
            $table->string('codigo_externo')->nullable();
            $table->unsignedInteger('pendencia')->default(0);
            $table->foreignId('escola_id')->constrained('escolas')->onDelete('cascade');
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
