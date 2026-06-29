<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('meja', function (Blueprint $table) {
            $table->id(); // Mengakomodasi + id (PK): int
            $table->string('nomor_meja'); // Mengakomodasi + nomor_meja: String
            $table->enum('status_meja', ['Tersedia', 'Terisi'])->default('Tersedia'); // Mengakomodasi + status_meja: Enum
            $table->string('link_qr_code')->nullable(); // Mengakomodasi + link_qr_code: String
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('meja');
    }
};
