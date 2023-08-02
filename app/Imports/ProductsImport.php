<?php

namespace App\Imports;

use App\Models\Product;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ProductsImport implements ToModel,WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new Product(['product_img'  => $row['images'],'product_name' => $row['product_name'],
        'colorway' => $row['colorway'],'size' => $row['size'],'price' => $row['price'],
        'brand_id' => $row['brand_id'],'type_id' => $row['type_id']]);
    }
}
