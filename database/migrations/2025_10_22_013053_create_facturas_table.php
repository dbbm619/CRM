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
        Schema::create('facturas', function (Blueprint $table) {
            $table->id();

            $table->foreignId('cliente_id')
                ->constrained('clientes')
                ->onDelete('cascade');

            $table->foreignId('venta_id')
                ->constrained('ventas')
                ->onDelete('cascade');

            $table->string('numero_factura')->unique();

            $table->date('fecha_emision');

            $table->enum('estado', ['emitida', 'pagada', 'anulada'])
                ->default('emitida');

            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('facturas');
    }
};
