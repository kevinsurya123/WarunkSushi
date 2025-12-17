<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reports', function (Blueprint $table) {
            $table->id('id_laporan');
            $table->unsignedBigInteger('id_user');
            $table->date('tanggal_laporan');
            $table->string('periode', 50)->nullable();
            $table->decimal('total_laporan', 14, 2)->default(0);
            $table->string('menu_terlaris', 255)->nullable();
            $table->timestamps();

            // index
            $table->index('tanggal_laporan', 'idx_reports_tanggal');
            $table->index('id_user', 'idx_reports_user');

            // foreign key
            $table->foreign('id_user', 'fk_reports_user')
                  ->references('id_user')
                  ->on('users')
                  ->onUpdate('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reports');
    }
};
