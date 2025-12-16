<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsersPasswordSeeder extends Seeder
{
    public function run()
    {
        // Matikan cek foreign key sementara
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // Kosongkan tabel user (truncate lebih cepat)
        DB::table('users')->truncate();

        // Insert kembali data default
        DB::table('users')->insert([
            [
                'nama_user' => 'Owner Sushi',
                'username' => 'owner',
                'password_hash' => Hash::make('owner123'),
                'role' => 'owner',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_user' => 'Manager Sushi',
                'username' => 'manager',
                'password_hash' => Hash::make('manager123'),
                'role' => 'manager',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_user' => 'Joko Kasir',
                'username' => 'joko',
                'password_hash' => Hash::make('kasir123'),
                'role' => 'pegawai',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // Aktifkan kembali cek foreign key
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
