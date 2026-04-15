<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TypeOfService extends Model
{
    protected $table = 'type_of_service';
    protected $guarded = ['id'];

    public function transOrderDetails()
    {
        return $this->hasMany(TransOrderDetail::class, 'id_service');
    }
}
