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
        Schema::create('ventas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("user_id");
            $table->unsignedBigInteger("cliente_id");
            $table->decimal("total", 24, 2);
            $table->decimal("recibido", 24, 2)->nullable();
            $table->decimal("cambio_entregado", 24, 2)->default(0);
            $table->date("fecha");
            $table->text("descripcion")->nullable();
            $table->timestamps();

            $table->foreign("user_id")->on("users")->references("id");
            $table->foreign("cliente_id")->on("clientes")->references("id");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ventas');
    }
};
