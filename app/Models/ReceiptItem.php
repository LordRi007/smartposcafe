<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReceiptItem extends Model
{
    protected $fillable = ['product_name', 'quantity', 'price'];

    public function receipt()
    {
        return $this->belongsTo(Receipt::class);
    }
    
}
