<?php

namespace App\Models;
use DB;
use Illuminate\Database\Eloquent\Model;

class Users extends Model
{
   protected $fillable = [
    'name',
    'email',
    'password',
    'role',
    'status'
];

}
