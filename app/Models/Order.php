<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
   protected $fillable = ['total_amount', 'received_amount', 'change_amount'];

public function items()
{
    return $this->hasMany(OrderItem::class);
}

}
