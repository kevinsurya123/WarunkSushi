<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('report_items', function (Blueprint $table) {
            $table->id('id_detail_laporan');
            $table->unsignedBigInteger('id_laporan');
            $table->unsignedBigInteger('id_menu');
            $table->integer('jumlah')->default(0);
            $table->decimal('harga_satuan', 14, 2);

            // generated column
            $table->decimal('subtotal', 14, 2)
                  ->storedAs('jumlah * harga_satuan');

            $table->timestamps();

            // index
            $table->index('id_laporan', 'idx_ritems_report');
            $table->index('id_menu', 'idx_ritems_menu');

            // foreign keys
            $table->foreign('id_laporan', 'fk_ritems_report')
                  ->references('id_laporan')
                  ->on('reports')
                  ->onDelete('cascade')
                  ->onUpdate('cascade');

            $table->foreign('id_menu', 'fk_ritems_menu')
                  ->references('id_menu')
                  ->on('menus')
                  ->onUpdate('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('report_items');
    }
};
