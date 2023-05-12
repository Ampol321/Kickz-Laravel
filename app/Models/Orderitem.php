<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Orderitem extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $fillable = ['order_id','product_id','quantity','price'];
}
