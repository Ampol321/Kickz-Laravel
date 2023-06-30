<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Searchable\Searchable;
use Spatie\Searchable\SearchResult;

class Type extends Model implements Searchable
// class Type extends Model
{
    use HasFactory;

    protected $table = 'types';
    protected $primaryKey = 'type_id';
    protected $fillable = ['type_name'];

    public function products(){
        return $this->hasMany(Product::class,'type_id');
    }

    public function getSearchResult(): SearchResult
    {
        $url = '#';
        return new SearchResult(
            $this,
            $this->type_name,
            $url,
        );
    }
}
