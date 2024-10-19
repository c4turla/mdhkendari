<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHargaBarangTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('harga_barang', function (Blueprint $table) {
            $table->id('id_harga'); // Primary key, auto-increment
            $table->foreignId('id_barang')->constrained('barang')->onDelete('cascade'); // Relasi ke tabel barang
            $table->foreignId('id_zona')->constrained('zona')->onDelete('cascade'); // Relasi ke tabel zona
            $table->decimal('harga_per_dos', 10, 2)->nullable(); // Harga per dos
            $table->decimal('harga_per_pcs', 10, 2)->nullable(); // Harga per pcs
            $table->timestamps(); // Created at & updated at
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('harga_barang');
    }
}
