<?php
namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    use Notifiable, SoftDeletes;

    // primary key sesuai database kamu
    protected $primaryKey = 'id_user';

    // jika id tidak auto-increment default tuliskan $incrementing = true; (default true)
    public $incrementing = true;
    protected $keyType = 'int';

    // kolom yang boleh diisi massal
    protected $fillable = [
        'nama_user',
        'username',
        'password_hash',
        'role'
    ];

    // sembunyikan kolom sensitif
    protected $hidden = [
        'password_hash',
        'remember_token',
    ];

    // Laravel akan memanggil ini untuk mendapatkan password saat auth
    public function getAuthPassword()
    {
        return $this->password_hash;
    }
}
