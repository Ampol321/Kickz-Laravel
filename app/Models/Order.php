<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Shipment;
use App\Models\Payment;


class Order extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $primaryKey = 'id';
    protected $fillable = ['user_id','shipment_id',
                            'payment_id','credit_card','shipping_address','status',
                            'date_ordered','date_shipped'];

    public function user(){
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function shipment(){
        return $this->belongsTo(Shipment::class, 'shipment_id', 'id');
    }

    public function payment(){
        return $this->belongsTo(Payment::class, 'payment_id', 'id');
    }

    public function products(){
        return $this->belongsToMany(Product::class, 'orderitems')
            ->withPivot('user_id', 'quantity', 'price');
    }
}
