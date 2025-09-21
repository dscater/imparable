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
        Schema::create('ingreso_prod_detalles', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("ingreso_producto_id");
            $table->unsignedBigInteger("producto_servicio_id");
            $table->double("cantidad", 8, 2);
            $table->decimal("precio_compra");
            $table->decimal("subtotal");
            $table->date("fecha_vencimiento")->nullable();
            $table->timestamps();

            $table->foreign("ingreso_producto_id")->on("ingreso_productos")->references("id");
            $table->foreign("producto_servicio_id")->on("producto_servicios")->references("id");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ingreso_prod_detalles');
    }
};
