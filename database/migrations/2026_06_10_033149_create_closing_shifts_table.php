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
    Schema::create('closing_shifts', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // ID Kasir yang bertugas
        $table->date('tanggal'); // Tanggal closing
        $table->integer('total_system'); // Angka dari sistem
        $table->integer('uang_fisik'); // Angka yang diketik kasir
        $table->integer('selisih'); // Hasil pengurangan (Fisik - System)
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
        Schema::dropIfExists('closing_shifts');
    }
};
