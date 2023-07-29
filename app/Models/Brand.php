<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Searchable\Searchable;
use Spatie\Searchable\SearchResult;

class Brand extends Model implements Searchable
// class Brand extends Model
{
    use HasFactory;

    protected $table = 'brands';
    protected $primaryKey = 'id';
    protected $fillable = ['img_path','brand_name'];

    public function products(){
        return $this->hasMany(Product::class,'id');
    }

    public function getSearchResult(): SearchResult
    {
        $url = route('brand.show', $this->id);
        return new SearchResult(
            $this,
            $this->brand_name,
            $url
        );
    }

}
