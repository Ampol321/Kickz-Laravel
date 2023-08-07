<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Searchable\Searchable;
use Spatie\Searchable\SearchResult;
use \Spatie\Searchable\SearchableTrait;

use App\Models\Brand;
use App\Models\Type;
use App\Models\Stock;
use App\Models\Cart;
use App\Models\Order;

class Product extends Model implements Searchable
// class Product extends Model
{
    use HasFactory;
    
    protected $table = 'products';
    protected $primaryKey = 'id';
    protected $fillable = ['product_img','product_name','colorway',
                            'size','price','brand_id','type_id'];

    public function orders(){
        return $this->belongsToMany(Order::class,'ordeitems','product_id','order_id')->withPivot('quantity','price');
    }
    
    public function brand(){
        return $this->belongsTo(Brand::class, 'brand_id', 'id');
    }

    public function type(){
        return $this->belongsTo(Type::class, 'type_id', 'id');
    }

    public function stock(){
        return $this->hasOne(Stock::class, 'product_id', 'id');
    }

    public function getSearchResult(): SearchResult
    {
        $url = url('/product-detail', $this->id);
        return new SearchResult(
            $this,
            $this->product_name,
            $url,
        );
    }
}
