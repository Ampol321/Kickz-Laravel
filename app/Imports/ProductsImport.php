<?php

namespace App\Imports;

use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use App\Models\Product;
use App\Models\Stock;

class ProductsImport implements ToModel,WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        $product = new Product([
            'product_img' => $row['images'],
            'product_name' => $row['product_name'],
            'colorway' => $row['colorway'],
            'size' => $row['size'],
            'price' => $row['price'],
            'brand_id' => $row['brand_id'],
            'type_id' => $row['type_id']
        ]);
    
        $product->save();
    
        $stock = new Stock([
            'product_id' => $product->id,
            'quantity' => 0 
        ]);
        
        $stock->save();
    
        return $product;
    }
}
