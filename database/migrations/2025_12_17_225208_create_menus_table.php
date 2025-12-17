<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('menus', function (Blueprint $table) {
            $table->id('id_menu');
            $table->string('nama_menu', 255);
            $table->string('kategori_menu', 100)->nullable();
            $table->decimal('harga', 14, 2)->default(0);
            $table->integer('stok_harian')->default(0);
            $table->text('detail_menu')->nullable();
            $table->string('gambar', 255)->nullable();
            $table->boolean('is_promoted')->default(false);
            $table->timestamps();
            $table->softDeletes();

            $table->index('nama_menu', 'idx_menus_nama');
            $table->index('kategori_menu', 'idx_menus_kategori');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('menus');
    }
};
