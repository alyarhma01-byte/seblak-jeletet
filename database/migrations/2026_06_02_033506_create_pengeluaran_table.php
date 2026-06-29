<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('pengeluaran', function (Blueprint $table) {
            $table->id(); // Mengakomodasi + id (PK): int
            $table->string('nama_pengeluaran'); // Mengakomodasi + nama_pengeluaran: String
            $table->integer('total_biaya'); // Mengakomodasi + total_biaya: Integer
            $table->date('tanggal'); // Mengakomodasi + tanggal: Date
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('pengeluaran');
    }
};
