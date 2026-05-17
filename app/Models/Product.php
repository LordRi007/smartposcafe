<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;
class Product extends Model
{
   
use HasFactory;

   protected $fillable = ['name', 'cat_id', 'price', 'img', 'status'];


    // Convert price to float when accessing
    public function getPriceAttribute($value)
    {
        return (float)$value;
    }

    public function receiptItems()
    {
        return $this->hasMany(ReceiptItem::class);
    }
}
