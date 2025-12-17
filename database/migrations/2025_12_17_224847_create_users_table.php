<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id('id_user');
            $table->string('nama_user', 255);
            $table->string('username', 100)->unique();
            $table->string('password_hash', 255);
            $table->enum('role', ['owner', 'manager', 'pegawai'])->default('pegawai');
            $table->timestamps();
            $table->softDeletes();

            $table->index('username', 'idx_users_username');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
