<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    public $timestamps = false;

    protected $fillable = ['id','user_id','shipment_id',
                            'payment_id','credit_card','shipping_address','status',
                            'date_ordered','date_shipped'];
}
