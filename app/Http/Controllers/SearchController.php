<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\Brand;
use App\Models\Product;
use App\Models\Type;
use Spatie\Searchable\Search;

class SearchController extends Controller
{
    public function search(Request $request)
    {
        // $result = $request->input('result');

        // $products = DB::table('products')
        //     ->join('brands', 'brands.id', "=", 'products.brand_id')
        //     ->join('types', 'types.id', "=", 'products.type_id')
        //     ->select('products.*', 'brands.brand_name', 'types.type_name')
        //     ->where('products.product_name', 'like', '%' . $result  . '%')
            // ->orWhere('brands.brand_name', 'like', '%' . $result  . '%')
            // ->orWhere('products.colorway', 'like', '%' . $result  . '%')
            // ->orWhere('products.size', 'like', '%' . $result  . '%')
            // ->get();

        $searchResults = (new Search())
            ->registerModel(Product::class, 'product_name')
            ->search(trim($request->term));
        // dd($searchResults);

        // return view('products.search', compact('searchResults'));
        return response()->json(['searchResults' => $searchResults]);
    }
}
