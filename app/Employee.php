<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    public function store(){
        return $this->belongsTo(Store::class);
    }

    public function items(){
        return $this->hasMany(Item::class);
    }
}
