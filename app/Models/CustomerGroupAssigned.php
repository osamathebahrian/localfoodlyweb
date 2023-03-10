<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerGroupAssigned extends Model
{
    protected $fillable = [
        'id',
        'customer_id',
        'group_id'
    ];

    public function customers()
    {
        return $this->hasMany(Customer::class);
    }
}
