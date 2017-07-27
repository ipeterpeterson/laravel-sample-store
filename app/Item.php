<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    protected $fillable = [
        'shoulder', 'arm_hole', 'chest_1', 'chest_2', 'waist', 'hip', 'slit', 'top_length', 'f_neck',
        'b_neck', 'sleeve_length','sleeve_breadth', 'sleeve_type', 'hip_size', 'ankle', 'bottom_length', 'bottom_breadth', 'knee',
        'thigh', 'bottom_type', 'description', 'amount',
    ];

    public function order(){
        return $this->belongsTo(Order::class);
    }

    public function employee(){
        return $this->belongsTo(Employee::class);
    }

    /**
     * @todo check if data integrity is being maintained. Leaving out column names in this function
     */
    public function customers() {
        return $this->belongsToMany(Customer::class)
            ->withPivot('customer_item', 'customer_id')
            ->withTimestamps();
    }
}
