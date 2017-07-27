<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $fillable = [
        'name', 'phone', 'email', 'location',
    ];

    public function orders(){
        return $this->hasMany(Order::class);
    }

    public function stores(){
        return $this->belongsToMany(Store::class)
            ->withPivot('customer_store', 'store_id')
            ->withTimestamps();
    }

    public function items() {
        return $this->belongsToMany(Item::class)
            ->withPivot('customer_item', 'item_id')
            ->withTimestamps();
    }
}
