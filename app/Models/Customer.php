<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $guarded = ['id'];

    public function transOrders()
    {
        return $this->hasMany(TransOrder::class, 'id_customer');
    }
}
