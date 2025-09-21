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
        Schema::create('inscripcions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("user_id");
            $table->unsignedBigInteger("cliente_id");
            $table->unsignedBigInteger("disciplina_id");
            $table->date("fecha_inicio");
            $table->date("fecha_final"); // automatico | modificable
            $table->integer("cancelado"); // SI:1 | NO:0
            $table->string("estado"); // VIGENTE | PAUSA
            $table->date("fecha_pausa")->nullable();
            $table->integer("dias_pausa")->integer();
            $table->text("justificacion")->nullable();
            $table->text("descripcion")->nullable();
            $table->timestamps();

            $table->foreign("user_id")->on("users")->references("id");
            $table->foreign("cliente_id")->on("clientes")->references("id");
            $table->foreign("disciplina_id")->on("disciplinas")->references("id");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inscripcions');
    }
};
