<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $table = 'products';
    protected $guarded = 'id';
    protected $fillable = ['img_path','product_name','colorway',
                            'size','price','brand_id','type_id'];
}
