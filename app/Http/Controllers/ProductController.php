<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\DB;
use App\DataTables\ProductsDataTable;
use App\Charts\ProductChart;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Brand;
use App\Models\Stock;
use App\Models\Type;

class ProductController extends Controller
{
    public function index(ProductsDataTable $dataTable)
    {
        $products = DB::table('orderitems')
            ->orderBy('totalqty', 'DESC')
            ->join('products', 'products.id', "=", 'orderitems.product_id')
            ->groupBy('products.product_name')
            ->pluck(DB::raw('sum(orderitems.quantity) as totalqty'), 'products.product_name')
            ->all();

        $productChart = new ProductChart();
        $dataset = $productChart->labels(array_keys($products));
        $dataset = $productChart->dataset(
            'Sold Products',
            'horizontalBar',
            array_values($products)
        );

        $dataset = $dataset->backgroundColor([
            '#7158e2',
            '#3ae374',
            '#ff3838',
            "#FF851B",
            "#7FDBFF",
            "#B10DC9",
            "#FFDC00",
            "#001f3f",
            "#39CCCC",
            "#01FF70",
            "#85144b",
            "#F012BE",
            "#3D9970",
            "#111111",
            "#AAAAAA",
        ]);

        $productChart->title("Best Seller Shoe Products", 20, '#666', true,
         "'Helvetica Neue', 'Helvetica', 'Arial', sans-serif");

        $productChart->options([
            'responsive' => true,
            'legend' => ['display' => false],
            'tooltips' => ['enabled' => true],
            // 'maintainAspectRatio' =>true,

            'title' => ["Best Seller Shoe Products" => true],
            'aspectRatio' => 1,
            'scales' => [
                'yAxes' => [
                    [
                        'display' => true,
                        'ticks' => ['beginAtZero' => true],
                        'gridLines' => ['display' => false],
                    ],
                ],
                'xAxes' => [
                    [
                        'categoryPercentage' => 0.8,
                        //'barThickness' => 100,
                        'barPercentage' => 1,
                        'ticks' => ['beginAtZero' => false],
                        'gridLines' => ['display' => false],
                        'display' => false,
                    ],
                ],
            ],
            "plugins" => '{datalabels: { font: { weight: \'bold\',
                size: 36 },
                color: \'white\',
            }}',
        ]);

        return $dataTable->render('products.index', compact('productChart'));
    }

    public function productIndex(){
        $products = Product::with(['brand', 'type', 'stock'])
        ->get();
        
        return response()->json($products);
    }

    public function home(){
        $products = DB::table('products')
            ->join('brands', 'brands.id', "=", 'products.brand_id')
            ->join('types', 'types.id', "=", 'products.type_id')
            ->select('products.*', 'brands.brand_name', 'types.type_name')
            // ->join('products', 'types.id', '=', 'products.type_id')
            ->orderBy('products.id', 'ASC')->paginate(6);

        return View::make('products.home', compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $brands = brand::all();
        $types = type::all();
        return View::make('products.create', compact('brands', 'types'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $rules = [
            'product_img' => 'required|array',
            'product_img.*' => 'required|image|mimes:jpeg,jpg,png',
            'product_name' => 'required',
            'colorway' => 'required',
            'size' => 'required',
            'price' => 'required',
            'brand_id' => 'required',
            'type_id' => 'required',
        ];

        $messages = [
            'product_img.required' => 'Please Input a product image',
            'image' => 'Image format not supported',
            'product_name.required' => 'Please Input a product name',
            'colorway.required' => 'Please Input a shoe colorway',
            'size.required' => 'Please Input a shoe size',
            'price.required' => 'Please Input a shoe price',
            'brand_id.required' => 'Please Input a shoe brand',
            'type_id.required' => 'Please specify the type of shoe',
        ];

        Validator::make($request->all(), $rules, $messages)->validate();

        $products = new product;
        $product_img = array();
        if ($request->hasFile('product_img')) {
            foreach ($request->file('product_img') as $file) {
                $fileName = time() . '_' . $file->getClientOriginalName();
                $filePath = $file->storeAs('uploads', $fileName, 'public');
                $path = Storage::putFileAs(
                    'public/images',
                    $file,
                    $fileName
                );
                $product_img[] = '/storage/images/' . $fileName;
            }
            $products->product_img = implode(',', $product_img);
        }
        // if($request->file()) {
        //     $fileName = time().'_'.$request->file('product_img')->getClientOriginalName();
        //     $filePath = $request->file('product_img')->storeAs('uploads', $fileName,'public');
        //     $path = Storage::putFileAs(
        //         'public/images', $request->file('product_img'), $fileName
        //     );
        //     $products->product_img = '/storage/images/' . $fileName;
        // }
        $products->brand_id = $request->brand_id;
        $products->product_name = $request->product_name;
        $products->colorway = $request->colorway;
        $products->type_id = $request->type_id;
        $products->size = $request->size;
        $products->price = $request->price;
        $products->save();

        stock::where('product_id', $products->id)->insert([
            "product_id" => $products->id,
            "stock" => 0
        ]);
        return redirect()->route('product.index')->with('message', 'Product Created!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $products = DB::table('products')
            ->join('brands', 'brands.id', "=", 'products.brand_id')
            ->join('types', 'types.id', "=", 'products.type_id')
            ->select('products.*', 'brands.brand_name', 'types.type_name')
            ->where('products.id', $id)
            ->first();

        return View::make('products.detail', compact('products'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $products = DB::table('products')
            ->select('products.*', 'brands.brand_name', 'types.type_name')
            ->join('brands', 'brands.id', '=', 'products.brand_id')
            ->join('types', 'types.id', '=', 'products.type_id')
            ->join('stocks', 'stocks.product_id', '=', 'products.id')
            //->select('products.*', 'brands.brand_name', 'types.type_name')
            ->where('products.id', $id)
            ->first();
        // $products = Product::with(['brand', 'type'])->findOrFail($id);
        $brands = Brand::where('id', '<>', $products->brand_id)->get(['brand_name', 'id']);
        $types = Type::where('id', '<>', $products->type_id)->get(['type_name', 'id']);
        $stocks = stock::where('product_id', $id)->first();
        // $products = product::find($id);
        return View('products.edit', compact('products', 'brands', 'types', 'stocks'))->with('message', 'Products Edited');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $rules = [
            'product_img' => 'array',
            'product_img.*' => 'image|mimes:jpeg,jpg,png',
            'product_name' => 'required',
            'colorway' => 'required',
            'size' => 'required',
            'price' => 'required',
            'brand_id' => 'required',
            'type_id' => 'required',
        ];

        $messages = [
            'image' => 'Image format not supported',
            'product_name.required' => 'Please Input a product name',
            'colorway.required' => 'Please Input a shoe colorway',
            'size.required' => 'Please Input a shoe size',
            'price.required' => 'Please Input a shoe price',
            'brand_id.required' => 'Please Input a shoe brand',
            'type_id.required' => 'Please specify the type of shoe',
        ];

        Validator::make($request->all(), $rules, $messages)->validate();

        $products = product::find($id);
        $product_img = array();
        if ($request->hasFile('product_img')) {
            foreach ($request->file('product_img') as $file) {
                $fileName = time() . '_' . $file->getClientOriginalName();
                $filePath = $file->storeAs('uploads', $fileName, 'public');
                $path = Storage::putFileAs(
                    'public/images',
                    $file,
                    $fileName
                );
                $product_img[] = '/storage/images/' . $fileName;
            }
            $products->product_img = implode(',', $product_img);
        }
        $products->brand_id = $request->brand_id;
        $products->product_name = $request->product_name;
        $products->colorway = $request->colorway;
        $products->type_id = $request->type_id;
        $products->size = $request->size;
        $products->price = $request->price;
        $products->save();

        stock::where('product_id', $products->id)->update([
            "stock" => $request->stock
        ]);

        return redirect()->route('product.index')->with('message', 'Product Updated!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        product::destroy($id);
        return back()->with('message', 'Product Deleted');;
    }
}
