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
        Schema::create('juals', function (Blueprint $table) {
            $table->string('no_transaksi')->primary();
            $table->string('stand_id');
            $table->foreign('stand_id')->references('kd_stand')->on('stands')->cascadeOnDelete();
            $table->decimal('total_biaya_produksi', 12, 2);
            $table->decimal('total_omzet', 12, 2);
            $table->decimal('selisih', 12, 2);
            $table->dateTime('tanggal')->default(now());
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('juals');
    }
};
