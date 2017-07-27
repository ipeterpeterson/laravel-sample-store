<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;

class Store extends Model
{

    use Notifiable;

    protected $fillable = [
        'name', 'address', 'zip', 'phone', 'activation', 'api_token',
    ];

    protected $hidden = [
    ];

    public function user(){
        return $this->hasOne(User::class);
    }

    public function employees(){
        return $this->hasMany(Employee::class);
    }

    public function orders(){
        return $this->hasMany(Order::class);
    }

    public function customers(){
        return $this->belongsToMany(Customer::class)
            ->withPivot('customer_store', 'customer_id')
            ->withTimestamps();
    }
}
