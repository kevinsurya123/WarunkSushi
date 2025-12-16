<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Menu extends Model
{
    use SoftDeletes;

    protected $primaryKey = 'id_menu';
    protected $fillable = [
        'nama_menu','kategori_menu','harga','stok_harian','detail_menu','gambar','is_promoted'
    ];

    public function promotions()
    {
        return $this->hasMany(Promotion::class, 'id_menu', 'id_menu');
    }

    public function activePromotion()
    {
        return $this->hasOne(Promotion::class, 'id_menu', 'id_menu')
            ->where('active', true)
            ->where(function($q){ $q->whereNull('start_at')->orWhere('start_at','<=', now()); })
            ->where(function($q){ $q->whereNull('end_at')->orWhere('end_at','>=', now()); });
    }

    public function variations()
    {
        return $this->hasMany(MenuVariation::class, 'id_menu', 'id_menu');
    }
}
