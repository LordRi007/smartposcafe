<?php

namespace App\Models;

use App\Models\ReceiptItem;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
   public function items()
{
    return $this->hasMany(InvoiceItem::class);
}

}
