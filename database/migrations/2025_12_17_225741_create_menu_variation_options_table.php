<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('menu_variation_options', function (Blueprint $table) {
            $table->id('id_option');
            $table->unsignedBigInteger('id_variation');
            $table->string('name', 191);
            $table->decimal('price_modifier', 10, 2)->default(0);
            $table->timestamps();

            // index
            $table->index('id_variation', 'idx_menu_variation_options_variation');

            // foreign key
            $table->foreign('id_variation', 'fk_variationoptions_variation')
                  ->references('id_variation')
                  ->on('menu_variations')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('menu_variation_options');
    }
};
