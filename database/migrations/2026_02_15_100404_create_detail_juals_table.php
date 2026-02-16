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
        Schema::create('detail_juals', function (Blueprint $table) {
            $table->id();
            $table->string("jual_id");
            $table->string("menu_id");
            $table->foreign('jual_id')->references('no_transaksi')->on('juals')->cascadeOnDelete();
            $table->foreign('menu_id')->references('kd_menu')->on('menus')->cascadeOnDelete();
            $table->integer('jumlah');
            $table->integer('sisa');
            $table->integer('laku');
            $table->decimal('harga_satuan', 12, 2);
            $table->decimal('subtotal_biaya_produksi', 12, 2);
            $table->decimal('omzet', 12, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_juals');
    }
};
