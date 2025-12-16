<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MenuVariation extends Model
{
    protected $primaryKey = 'id_variation';
    protected $fillable = ['id_menu','name','multiple'];

    public function menu()
    {
        return $this->belongsTo(Menu::class, 'id_menu', 'id_menu');
    }

    public function options()
    {
        return $this->hasMany(MenuVariationOption::class, 'id_variation', 'id_variation');
    }
}
