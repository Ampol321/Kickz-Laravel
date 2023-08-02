<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\DB;
use App\DataTables\ShipmentsDataTable;
use App\Charts\ShipmentChart;
use Illuminate\Http\Request;
use App\Models\Shipment;


class ShipmentController extends Controller
{
    public function indexChart(){
        $shipments = DB::table('orders')
            ->join('shipments', 'shipments.id', "=", 'orders.shipment_id')
            ->groupBy('orders.shipment_id', 'shipments.shipment_name')
            ->pluck(DB::raw('count(orders.shipment_id) as total'), 'shipments.shipment_name')
            ->all();

        return response()->json($shipments);
    }

    public function index(ShipmentsDataTable $dataTable)
    {
        $shipments = DB::table('orders')
            ->join('shipments', 'shipments.id', "=", 'orders.shipment_id')
            ->groupBy('orders.shipment_id', 'shipments.shipment_name')
            ->pluck(DB::raw('count(orders.shipment_id) as total'), 'shipments.shipment_name')
            ->all();
        // dd($shipments);

        $shipmentChart = new ShipmentChart();
        $dataset = $shipmentChart->labels(array_keys($shipments));
        $dataset = $shipmentChart->dataset(
            'Times used',
            'bar',
            array_values($shipments)
        );

        $dataset = $dataset->backgroundColor([
            '#007BB8',
            '#D30000',
            '#FFDF00',
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

        $shipmentChart->title("Most Used Shipping Options", 20, '#666', true,
         "'Helvetica Neue', 'Helvetica', 'Arial', sans-serif");

        $shipmentChart->options([
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
                        'display' => true,
                    ],
                ],
            ],
            "plugins" => '{datalabels: { font: { weight: \'bold\',
                size: 36 },
                color: \'white\',
            }}',
        ]);
        return $dataTable->render('shipments.index',compact('shipmentChart'));
        // return View::make('shipments.index',compact('shipments'));
    }

    public function shipmentIndex(){
        $shipments=Shipment::all();
        return response()->json($shipments);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // return View::make('shipments.create');
        return response()->json([]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $rules = [
            'shipment_img' => 'required|array',
            'shipment_img.*' => 'required|image|mimes:jpeg,jpg,png',
            'shipment_name' => 'required',
            'shipment_cost' => 'required',
        ];

        $messages = [
            'shipment_img.required' => 'Please Input a Shipment Image',
            'image' => 'Image format not supported',
            'shipment_name.required' => 'Please Input a Shipment Name',
            'shipment_cost.required' => 'Please Input a Shipment Cost',
        ];

        Validator::make($request->all(), $rules, $messages)->validate();

        $shipments = new shipment;

        $shipment_img = array();
        if ($request->hasFile('shipment_img')) {
            foreach ($request->file('shipment_img') as $file) {
                $fileName = time() . '_' . $file->getClientOriginalName();
                $filePath = $file->storeAs('uploads', $fileName, 'public');
                $path = Storage::putFileAs(
                    'public/images',
                    $file,
                    $fileName
                );
                $shipment_img[] = '/storage/images/' . $fileName;
            }
            $shipments->shipment_img = implode(',', $shipment_img);
        }
        // if($request->file()) {
        //     $fileName = time().'_'.$request->file('shipment_img')->getClientOriginalName();
        //     $filePath = $request->file('shipment_img')->storeAs('uploads', $fileName,'public');
        //     $path = Storage::putFileAs(
        //         'public/images', $request->file('shipment_img'), $fileName
        //     );
        //     $shipments->shipment_img = '/storage/images/' . $fileName;
        // }
        $shipments->shipment_name = $request->shipment_name;
        $shipments->shipment_cost = $request->shipment_cost;
        $shipments->save();
        // return redirect()->route('shipment.index')->with('message', 'Shipment Created!');
        return response()->json([]);
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
        $shipments = shipment::find($id);
        // return View::make('shipments.edit', compact('shipments'))->with('message', 'Shipment Edited');
        return response()->json(['shipment'=>$shipments]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $rules = [
            'shipment_img' => 'array',
            'shipment_img.*' => 'image|mimes:jpeg,jpg,png',
            'shipment_name' => 'required',
            'shipment_cost' => 'required',
        ];

        $messages = [
            'image' => 'Image format not supported',
            'shipment_name.required' => 'Please Input a Shipment Name',
            'shipment_cost.required' => 'Please Input a Shipment Cost',
        ];

        Validator::make($request->all(), $rules, $messages)->validate();

        $shipments = shipment::find($id);
        $shipment_img = array();
        if ($request->hasFile('shipment_img')) {
            foreach ($request->file('shipment_img') as $file) {
                $fileName = time() . '_' . $file->getClientOriginalName();
                $filePath = $file->storeAs('uploads', $fileName, 'public');
                $path = Storage::putFileAs(
                    'public/images',
                    $file,
                    $fileName
                );
                $shipment_img[] = '/storage/images/' . $fileName;
            }
            $shipments->shipment_img = implode(',', $shipment_img);
        }
        $shipments->shipment_name = $request->shipment_name;
        $shipments->shipment_cost = $request->shipment_cost;
        $shipments->save();
        // return redirect()->route('shipment.index')->with('message', 'Shipment Updated!');
        return response()->json([]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        shipment::destroy($id);
        // return back()->with('message', 'Shipment Deleted');
        return response()->json([]);
    }
}
