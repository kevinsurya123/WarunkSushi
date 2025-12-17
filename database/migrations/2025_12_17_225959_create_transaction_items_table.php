<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transaction_items', function (Blueprint $table) {
            $table->id('id_detail');
            $table->unsignedBigInteger('id_transaksi');
            $table->unsignedBigInteger('id_menu');

            // field lama + kompatibel SQL kamu
            $table->integer('qty')->default(1);
            $table->decimal('price', 12, 2)->default(0);

            // field utama yang dipakai
            $table->integer('jumlah')->default(1);
            $table->decimal('harga_satuan', 14, 2);

            // generated column (MySQL)
            $table->decimal('subtotal', 14, 2)
                  ->storedAs('jumlah * harga_satuan');

            $table->timestamps();

            // index
            $table->index('id_transaksi', 'idx_titems_trans');
            $table->index('id_menu', 'idx_titems_menu');

            // foreign keys
            $table->foreign('id_transaksi', 'fk_titems_trans')
                  ->references('id_transaksi')
                  ->on('transactions')
                  ->onDelete('cascade')
                  ->onUpdate('cascade');

            $table->foreign('id_menu', 'fk_titems_menu')
                  ->references('id_menu')
                  ->on('menus')
                  ->onUpdate('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transaction_items');
    }
};
