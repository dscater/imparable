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
        Schema::create('salida_detalles', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("salida_producto_id");
            $table->unsignedBigInteger("ingreso_prod_detalle_id");
            $table->double("cantidad", 8, 2);
            $table->timestamps();

            $table->foreign("salida_producto_id")->on("salida_productos")->references("id");
            $table->foreign("ingreso_prod_detalle_id")->on("ingreso_prod_detalles")->references("id");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('salida_detalles');
    }
};
