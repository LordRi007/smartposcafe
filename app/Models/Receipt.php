<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Receipt extends Model
{
     protected $fillable = ['total', 'received', 'change'];

    public function items()
    {
        return $this->hasMany(ReceiptItem::class);
    }
}
