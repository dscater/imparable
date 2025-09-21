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
        Schema::create('producto_servicios', function (Blueprint $table) {
            $table->id();
            $table->string("nombre", 255)->unique();
            $table->text("descripcion")->nullable();
            $table->decimal("precio", 24, 2);
            $table->double("stock_actual", 8, 2)->nullable();
            $table->double("stock_minimo", 8, 2)->nullable();
            $table->string("tipo"); // PRODUCTO, PLAN, SERVICIO
            $table->integer("status")->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('producto_servicios');
    }
};
