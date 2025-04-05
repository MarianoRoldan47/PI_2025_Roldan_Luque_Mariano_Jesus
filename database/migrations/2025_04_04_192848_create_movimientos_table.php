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
        Schema::create('movimientos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('producto_id');
            $table->unsignedBigInteger('user_id');
            $table->enum('tipo', ['entrada', 'salida', 'traslado']);
            $table->integer('cantidad');

            $table->enum('origen_tipo', ['estanteria', 'proveedor']);
            $table->unsignedBigInteger('ubicacion_origen_id')->nullable();

            $table->enum('destino_tipo', ['estanteria', 'cliente']);
            $table->unsignedBigInteger('ubicacion_destino_id')->nullable();

            $table->enum('estado', ['pendiente', 'confirmado', 'cancelado'])->default('pendiente');
            $table->date('fecha_movimiento');
            $table->timestamps();

            $table->foreign('producto_id')->references('id')->on('productos')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

            $table->foreign('ubicacion_origen_id')->references('id')->on('estanterias')->onDelete('set null');
            $table->foreign('ubicacion_destino_id')->references('id')->on('estanterias')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('movimientos');
    }
};
