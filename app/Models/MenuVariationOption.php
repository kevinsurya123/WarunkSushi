<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MenuVariationOption extends Model
{
    protected $primaryKey = 'id_option';
    protected $fillable = ['id_variation','name','price_modifier'];

    public function variation()
    {
        return $this->belongsTo(MenuVariation::class, 'id_variation', 'id_variation');
    }
}
