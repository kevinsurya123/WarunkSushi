<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id('id_transaksi');
            $table->unsignedBigInteger('id_user');
            $table->decimal('total_amount', 12, 2)->default(0);
            $table->decimal('payment_amount', 12, 2)->default(0);
            $table->decimal('change_amount', 12, 2)->default(0);
            $table->string('payment_method', 20);
            $table->unsignedBigInteger('id_shift')->nullable();
            $table->unsignedBigInteger('id_customer')->nullable();
            $table->dateTime('tanggal_transaksi')->useCurrent();
            $table->enum('metode_pembayaran', ['qris', 'tunai', 'kartu'])->default('tunai');
            $table->decimal('total_transaksi', 14, 2)->default(0);
            $table->timestamps();

            // index
            $table->index('id_user', 'idx_transactions_user');
            $table->index('id_shift', 'idx_transactions_shift');
            $table->index('id_customer', 'idx_transactions_customer');
            $table->index('tanggal_transaksi', 'idx_transactions_tanggal');

            // foreign keys
            $table->foreign('id_user', 'fk_transactions_user')
                  ->references('id_user')
                  ->on('users')
                  ->onUpdate('cascade');

            $table->foreign('id_shift', 'fk_transactions_shift')
                  ->references('id_shift')
                  ->on('shifts')
                  ->onDelete('set null')
                  ->onUpdate('cascade');

            $table->foreign('id_customer', 'fk_transactions_customer')
                  ->references('id_customer')
                  ->on('customers')
                  ->onDelete('set null')
                  ->onUpdate('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
