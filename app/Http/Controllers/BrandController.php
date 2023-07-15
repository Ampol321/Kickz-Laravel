<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\DB;
use App\DataTables\BrandsDataTable;
use App\Charts\BrandChart;
use Illuminate\Http\Request;
use App\Models\Brand;

class BrandController extends Controller
{

    public function index(BrandsDataTable $dataTable)
    {
        $brands = DB::table('products')
            ->join('brands', 'brands.id', "=", 'products.brand_id')
            ->groupBy('products.brand_id', 'brands.brand_name')
            ->pluck(DB::raw('count(products.brand_id) as total'), 'brands.brand_name')
            ->all();
        // dd($shipments);

        $brandChart = new BrandChart();
        $dataset = $brandChart->labels(array_keys($brands));
        $dataset = $brandChart->dataset(
            'Times used',
            'doughnut',
            array_values($brands)
        );

        $dataset = $dataset->backgroundColor([
            '#000000',
            '#CF8D2E',
            '#F8121A',
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

        $brandChart->title("Shoe Count Based on Brand", 20, '#666', true,
         "'Helvetica Neue', 'Helvetica', 'Arial', sans-serif");

        $brandChart->options([
            'responsive' => true,
            'legend' => ['display' => false],
            'tooltips' => ['enabled' => true],
            // 'maintainAspectRatio' =>true,

            // 'title' => ["Best Seller Shoe Products" => true],
            'aspectRatio' => 1,
            'scales' => [
                'yAxes' => [
                    [
                        'display' => false,
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

        return $dataTable->render('brands.index', compact('brandChart'));
        // return View::make('brands.index',compact('brands'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('brands.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $rules = [
            'img_path' => 'required|array',
            'img_path.*' => 'required|image|mimes:jpeg,jpg,png',
            'brand_name' => 'required',
        ];

        $messages = [
            'img_path.required' => 'Please Input a brand Image',
            'image' => 'Image format not supported',
            'brand_name.required' => 'Please Input a brand Name',
        ];

        Validator::make($request->all(), $rules, $messages)->validate();

        $brands = new brand;
        $img_path = array();
        if ($request->hasFile('img_path')) {
            foreach ($request->file('img_path') as $file) {
                $fileName = time() . '_' . $file->getClientOriginalName();
                $filePath = $file->storeAs('uploads', $fileName, 'public');
                $path = Storage::putFileAs(
                    'public/images',
                    $file,
                    $fileName
                );
                $img_path[] = '/storage/images/' . $fileName;
            }
            $brands->img_path = implode(',', $img_path);
        }
        // if($request->file()) {
        //     $fileName = time().'_'.$request->file('img_path')->getClientOriginalName();
        //     $filePath = $request->file('img_path')->storeAs('uploads', $fileName,'public');
        //     $path = Storage::putFileAs(
        //         'public/images', $request->file('img_path'), $fileName
        //     );
        //     $brands->img_path = '/storage/images/' . $fileName;
        // }
        $brands->brand_name = $request->brand_name;
        $brands->save();
        return redirect()->route('brand.index')->with('message', 'Brand Created!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $brands = brand::find($id);
        return View('brands.edit', compact('brands'))->with('message', 'Brand Edited');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $rules = [
            'img_path' => 'array',
            'img_path.*' => 'image|mimes:jpeg,jpg,png',
            'brand_name' => 'required',
        ];

        $messages = [
            'image' => 'Image format not supported',
            'brand_name.required' => 'Please Input a brand Name',
        ];

        Validator::make($request->all(), $rules, $messages)->validate();

        $brands = brand::find($id);
        $img_path = array();
        if ($request->hasFile('img_path')) {
            foreach ($request->file('img_path') as $file) {
                $fileName = time() . '_' . $file->getClientOriginalName();
                $filePath = $file->storeAs('uploads', $fileName, 'public');
                $path = Storage::putFileAs(
                    'public/images',
                    $file,
                    $fileName
                );
                $img_path[] = '/storage/images/' . $fileName;
            }
            $brands->img_path = implode(',', $img_path);
        }
        $brands->brand_name = $request->brand_name;
        $brands->save();
        return redirect()->route('brand.index')->with('message', 'Brand Updated');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        brand::destroy($id);
        return back()->with('message', 'Brand Deleted');;
    }
}
