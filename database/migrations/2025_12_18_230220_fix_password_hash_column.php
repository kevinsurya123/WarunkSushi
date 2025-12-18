<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            // drop kolom lama
            $table->dropColumn('password_hash');
        });

        Schema::table('users', function (Blueprint $table) {
            // buat ulang dengan ukuran aman
            $table->string('password_hash', 255)->after('username');
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('password_hash');
            $table->string('password_hash', 50)->after('username');
        });
    }
};
