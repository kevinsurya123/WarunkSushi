<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $table = 'transactions';
    protected $primaryKey = 'id_transaksi';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'id_customer',
        'id_user',
        'total_amount',
        'payment_amount',
        'change_amount',
        'payment_method',
    ];

    public function items()
    {
        return $this->hasMany(TransactionItem::class, 'id_transaksi', 'id_transaksi');
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'id_customer', 'id_customer');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'id', 'id_user');
    }
}
