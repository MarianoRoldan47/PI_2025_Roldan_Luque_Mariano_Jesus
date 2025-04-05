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
        Schema::create('estanterias', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('zona_id');
            $table->integer('capacidad_maxima');
            $table->integer('capacidad_libre');
            $table->timestamps();

            $table->foreign('zona_id')->references('id')->on('zonas')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('estanterias');
    }
};
