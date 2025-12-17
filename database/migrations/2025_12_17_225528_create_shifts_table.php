<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('shifts', function (Blueprint $table) {
            $table->id('id_shift');
            $table->unsignedBigInteger('id_user');
            $table->date('tanggal_shift');
            $table->time('waktu_buka')->nullable();
            $table->time('waktu_tutup')->nullable();
            $table->string('status_shift', 30)->default('aktif');
            $table->timestamps();

            // index
            $table->index('id_user', 'idx_shifts_user');
            $table->index('tanggal_shift', 'idx_shifts_tanggal');

            // foreign key
            $table->foreign('id_user', 'fk_shifts_user')
                  ->references('id_user')
                  ->on('users')
                  ->onUpdate('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('shifts');
    }
};
