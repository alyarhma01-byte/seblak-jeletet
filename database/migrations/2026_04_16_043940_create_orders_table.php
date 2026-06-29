<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
{
    Schema::create('orders', function (Blueprint $table) {
        $table->id();
        $table->string('no_meja')->nullable();
        $table->string('nama_pelanggan');
        $table->string('tipe_pesanan');
        $table->string('metode_pembayaran')->default('Tunai'); // INI KOLOM BARUNYA
        $table->text('catatan')->nullable();
        $table->integer('total_harga');
        $table->enum('status_pembayaran', ['Belum Bayar', 'Lunas'])->default('Belum Bayar');
        $table->enum('status_pesanan', ['Menunggu', 'Diproses', 'Selesai'])->default('Menunggu');
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders');
    }
};
