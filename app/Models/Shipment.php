<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shipment extends Model
{
    use HasFactory;
    protected $table = 'shipments';
    protected $guarded = 'id';
    protected $fillable = ['shipment_img','shipment_name','shipment_cost'];
    public $timestamps = false;
}
