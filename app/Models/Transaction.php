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
        'transaction_date',
        'product_id',
        'payment_method_id'
    ];


    public function customers()
    {
        return CustomerAddress::with('customers')->get();
    }

    public function customerAddress()
    {
        return $this->belongsTo(CustomerAddress::class);
    }
    
    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function payment_methods()
    {
        return $this->hasMany(PaymentMethod::class);
    }
}
