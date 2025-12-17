<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->id('id_customer');
            $table->string('nama_customer', 255);
            $table->string('no_hp', 50)->nullable();
            $table->string('email', 150)->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('email', 'idx_customers_email');
            $table->index('no_hp', 'idx_customers_nohp');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
