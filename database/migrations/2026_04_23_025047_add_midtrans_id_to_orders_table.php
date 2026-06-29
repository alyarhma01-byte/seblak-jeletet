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
        Schema::table('orders', function (Blueprint $table) {
            // Ini akan membuat kolom baru bernama 'order_id_midtrans'
            // Posisinya diselipkan setelah kolom 'snap_token'
            //$table->string('order_id_midtrans')->nullable()->after('snap_token');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            // Ini untuk jaga-jaga kalau kita mau membatalkan migration
            $table->dropColumn('order_id_midtrans');
        });
    }
};
