<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\DB;
use App\DataTables\PaymentsDataTable;
use App\Charts\PaymentChart;
use Illuminate\Http\Request;
use App\Models\Payment;

class PaymentController extends Controller
{
    public function indexChart(){
        $payments = DB::table('orders')
            ->join('payments', 'payments.id', "=", 'orders.payment_id')
            ->groupBy('orders.payment_id', 'payments.payment_name')
            ->pluck(DB::raw('count(orders.payment_id) as total'), 'payments.payment_name')
            ->all();
        
        return response()->json($payments);
    }

    public function index(PaymentsDataTable $dataTable){
        $payments = DB::table('orders')
            ->join('payments', 'payments.id', "=", 'orders.payment_id')
            ->groupBy('orders.payment_id', 'payments.payment_name')
            ->pluck(DB::raw('count(orders.payment_id) as total'), 'payments.payment_name')
            ->all();

        $paymentChart = new PaymentChart();
        $dataset = $paymentChart->labels(array_keys($payments));
        $dataset = $paymentChart->dataset(
            'Times used',
            'bar',
            array_values($payments)
        );

        $dataset = $dataset->backgroundColor([
            '#4F7942',
            '#1260CC',
            '#29C5F6',
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

        $paymentChart->title("Most Used Payment Options", 20, '#666', true,
         "'Helvetica Neue', 'Helvetica', 'Arial', sans-serif");

        $paymentChart->options([
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

        // $payments = payment::all();
        // return $dataTable->render('payments.index', compact('payments'));
        return $dataTable->render('payments.index',compact('paymentChart'));
    }

    public function paymentIndex(){
        $payments=Payment::all();
        return response()->json($payments);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // return View::make('payments.create');
        return response()->json([]);
    }

    /**
     * Store a newly created resource in storage.
     */

    public function store(Request $request)
    {
        $rules = [
            'payment_img' => 'required|array',
            'payment_img.*' => 'required|image|mimes:jpeg,jpg,png',
            'payment_name' => 'required',
        ];

        $messages = [
            'payment_img.required' => 'Please Input a payment Image',
            'payment_img.array' => 'Please Input at least one payment Image',
            'payment_img.*.required' => 'Please Input a payment Image',
            'image' => 'Image format not supported',
            'payment_name.required' => 'Please Input a payment Name',
        ];

        Validator::make($request->all(), $rules, $messages)->validate();

        $payments = new Payment;

        $payment_img = array();
        if ($request->hasFile('payment_img')) {
            foreach ($request->file('payment_img') as $file) {
                $fileName = time() . '_' . $file->getClientOriginalName();
                $filePath = $file->storeAs('uploads', $fileName, 'public');
                $path = Storage::putFileAs(
                    'public/images',
                    $file,
                    $fileName
                );
                $payment_img[] = '/storage/images/' . $fileName;
            }
            $payments->payment_img = implode(',', $payment_img);
        }

        $payments->payment_name = $request->payment_name;
        $payments->save();

        // return redirect()->route('payment.index')->with('message', 'Payment Created!');
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
        $payments = payment::find($id);
        // return View::make('payments.edit', compact('payments'))->with('message', 'Payment Edited');
        return response()->json(['payment'=>$payments]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $rules = [
            'payment_img' => 'array',
            'payment_img.*' => 'image|mimes:jpeg,jpg,png',
            'payment_name' => 'required',
        ];

        $messages = [
            'image' => 'Image format not supported',
            'payment_name.required' => 'Please Input a payment Name',
        ];

        Validator::make($request->all(), $rules, $messages)->validate();

        $payments = payment::find($id);

        $payment_img = array();
        if ($request->hasFile('payment_img')) {
            foreach ($request->file('payment_img') as $file) {
                $fileName = time() . '_' . $file->getClientOriginalName();
                $filePath = $file->storeAs('uploads', $fileName, 'public');
                $path = Storage::putFileAs(
                    'public/images',
                    $file,
                    $fileName
                );
                $payment_img[] = '/storage/images/' . $fileName;
            }
            $payments->payment_img = implode(',', $payment_img);
        }

        $payments->payment_name = $request->payment_name;
        $payments->save();
        // if($request->file()) {
        //     $fileName = time().'_'.$request->file('payment_img')->getClientOriginalName();
        //     $filePath = $request->file('payment_img')->storeAs('uploads', $fileName,'public');
        //     $path = Storage::putFileAs(
        //         'public/images', $request->file('payment_img'), $fileName
        //     );
        //     $payments->payment_img = '/storage/images/' . $fileName;
        // }
        // $payments->payment_name = $request->payment_name;
        // $payments->save();
        // return redirect()->route('payment.index')->with('message', 'Payment Updated!');
        return response()->json([]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        payment::destroy($id);
        // return back()->with('message', 'Payment Deleted');
        return response()->json([]);
    }
}
