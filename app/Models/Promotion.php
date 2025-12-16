<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Promotion extends Model
{
    protected $primaryKey = 'id_promo';
    protected $fillable = ['id_menu','type','value','start_at','end_at','active'];

    public function menu()
    {
        return $this->belongsTo(Menu::class, 'id_menu', 'id_menu');
    }

    public function scopeActive($q)
    {
        return $q->where('active', true)
                 ->where(function($s){ $s->whereNull('start_at')->orWhere('start_at','<=', now()); })
                 ->where(function($s){ $s->whereNull('end_at')->orWhere('end_at','>=', now()); });
    }
}
