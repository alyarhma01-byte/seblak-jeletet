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
        Schema::create('menus', function (Blueprint $table) {
        $table->id();
        $table->foreignId('category_id')->constrained();
        $table->string('nama_menu');
        $table->integer('harga');
        $table->string('foto')->nullable();
        // Logika Pop-up Kustomisasi
        $table->boolean('has_level')->default(false); // Untuk Seblak & Mie Jebew
        $table->boolean('has_kencur')->default(false); // Hanya Seblak
        $table->boolean('has_kuah')->default(false);  // Hanya Seblak
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
        Schema::dropIfExists('menus');
    }
};
