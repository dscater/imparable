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
        Schema::create('cajas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("user_id")->nullable();
            $table->decimal("monto_apertura", 24, 2);
            $table->decimal("monto_actual", 24, 2);
            $table->decimal("monto_cierre", 24, 2);
            $table->date("fecha_apertura");
            $table->date("fecha_cierre");
            $table->string("estado"); //ABIERTO, CERRADO
            $table->timestamps();

            $table->foreign("user_id")->on("users")->references("id");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cajas');
    }
};
