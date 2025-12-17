<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('menu_variations', function (Blueprint $table) {
            $table->id('id_variation');
            $table->unsignedBigInteger('id_menu');
            $table->string('name', 191);
            $table->boolean('multiple')->default(false);
            $table->timestamps();

            // index
            $table->index('id_menu', 'idx_menu_variations_menu');

            // foreign key
            $table->foreign('id_menu', 'fk_menuvariations_menu')
                  ->references('id_menu')
                  ->on('menus')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('menu_variations');
    }
};
