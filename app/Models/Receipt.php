<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Receipt extends Model
{
   protected $fillable = [
        'total_amount',
        'received_amount',
        'change_amount',
        'cashier_id',
    ];

    public function items()
    {
        return $this->hasMany(ReceiptItem::class);
    }

    public function cashier()
    {
        return $this->belongsTo(User::class, 'cashier_id');
    }
}
