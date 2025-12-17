<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('promotions', function (Blueprint $table) {
            $table->id('id_promo');
            $table->unsignedBigInteger('id_menu')->nullable();
            $table->enum('type', ['percent', 'fixed'])->default('percent');
            $table->decimal('value', 10, 2)->default(0);
            $table->dateTime('start_at')->nullable();
            $table->dateTime('end_at')->nullable();
            $table->boolean('active')->default(true);
            $table->timestamps();

            // index
            $table->index('id_menu', 'idx_promotions_menu');

            // foreign key
            $table->foreign('id_menu', 'fk_promotions_menu')
                  ->references('id_menu')
                  ->on('menus')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('promotions');
    }
};
