<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransactionItem extends Model
{
    protected $table = 'transaction_items';
    protected $primaryKey = 'id_item';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
    'id_transaksi',
    'id_menu',
    'qty',
    'price',
    'harga_satuan',   // <— tambahkan baris ini
];


    public function transaction()
    {
        return $this->belongsTo(Transaction::class, 'id_transaksi', 'id_transaksi');
    }

    public function menu()
    {
        return $this->belongsTo(Menu::class, 'id_menu', 'id_menu');
    }
}
