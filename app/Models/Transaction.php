<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_address_id',
        'code_transaction',
        'transaction_date'
    ];

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function payment_methods()
    {
        return $this->hasMany(Payment_methods::class);
    }
}
