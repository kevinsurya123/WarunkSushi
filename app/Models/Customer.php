<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends Model
{
    use SoftDeletes;

    protected $table = 'customers';
    protected $primaryKey = 'id_customer';
    public $incrementing = true;
    protected $keyType = 'int';

    // otomatis pakai created_at & updated_at
    public $timestamps = true;

    protected $dates = ['deleted_at'];

    protected $fillable = [
        'nama_customer',
        'no_hp',
        'email',
    ];
}
