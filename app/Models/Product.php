<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;
class Product extends Model
{
      protected $fillable = ['name', 'cat_id', 'price', 'qty', 'des', 'img', 'status'];
public function category()
{
    return $this->belongsTo(Category::class, 'cat_id');
}

}
