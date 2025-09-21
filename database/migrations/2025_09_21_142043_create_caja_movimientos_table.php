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
        Schema::create('caja_movimientos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("user_id");
            $table->unsignedBigInteger("caja_id");
            $table->decimal("monto", 24, 2);
            $table->string("tipo"); // DEBE |HABER
            $table->date("fecha");
            $table->string("modulo"); // NOMBRE MODULOS
            $table->unsignedBigInteger("modulo_id")->nullable(); // MODULO ID
            $table->timestamps();

            $table->foreign("user_id")->on("users")->references("id");
            $table->foreign("caja_id")->on("cajas")->references("id");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('caja_movimientos');
    }
};
