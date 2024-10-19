<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBarangTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('barang', function (Blueprint $table) {
            $table->id('id_barang'); // Primary key, auto-increment
            $table->string('nama_barang', 100); // Nama barang
            $table->string('barcode', 50)->unique(); // Barcode barang
            $table->integer('satuan_per_dos'); // Jumlah pcs dalam satu dos
            $table->integer('stok_dos')->default(0); // Stok dalam satuan dos
            $table->integer('stok_pcs')->default(0); // Stok dalam satuan pcs
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
        Schema::dropIfExists('barang');
    }
}
