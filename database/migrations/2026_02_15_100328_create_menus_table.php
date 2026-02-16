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
        Schema::create('menus', function (Blueprint $table) {
            $table->primary('kd_menu');
            $table->string('nama_menu');
            $table->string('jenis_id')->unique();
            $table->foreign('jenis_id')->references('kd_jenis')->on('jenis')->cascadeOnDelete();
            $table->decimal('biaya_produksi', 12, 2);
            $table->decimal('harga_satuan', 12, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('menus');
    }
};
