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
        Schema::create('detalle_ventas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("venta_id");
            $table->unsignedBigInteger("producto_servicio_id");
            $table->double("cantidad", 8, 2);
            $table->decimal("precio", 24, 2);
            $table->decimal("descuento", 8, 2)->default(0);
            $table->decimal("subtotal", 24, 2);
            $table->integer("usa_lote"); // SI:1 | NO:0
            $table->timestamps();

            $table->foreign("venta_id")->on("ventas")->references("id");
            $table->foreign("producto_servicio_id")->on("producto_servicios")->references("id");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detalle_ventas');
    }
};
