<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'store_id', 'order_paid', 'notes', 'customer_id', 'delivery_date', 'order_amount',
    ];

    public function customer(){
        return $this->belongsTo(Customer::class);
    }

    public function items(){
        return $this->hasMany(Item::class);
    }

    public function store(){
        return $this->belongsTo(Store::class);
    }
}
