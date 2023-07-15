<?php

namespace App\Http\Controllers;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Charts\SalesChart;
use App\Models\Shipment;
use App\Models\Payment;
use App\Models\Orderitem;
use App\Models\Order;
use App\Models\User;
use Carbon\Carbon;

class AdminController extends Controller
{
    public function approveorder($id)
    {
        $user = auth()->user()->id;
        $email = auth()->user()->email;
        $name = auth()->user()->name;

        $mail = new PHPMailer(true);
        $mail->isSMTP();
        $mail->Host = 'sandbox.smtp.mailtrap.io';
        $mail->SMTPAuth = true;
        $mail->Username = 'ffc3a042a740e5';
        $mail->Password = '77e32cc75e902a';
        $mail->SMTPSecure = 'tls';
        $mail->Port = 2525;

        $mail->setFrom('kickz6873@gmail.com', 'Kickz');
        $mail->addAddress($email, $name);
        $mail->Subject = 'Hello ' . $name;
        $mail->isHTML(true);
        $mail->Body = '<div style="background-color: #F7F7F7; padding: 20px;">
        <h1 style="font-size: 36px; color: #0C2340; margin: 0; text-align: center;">Thank you for your purchase!</h1>
        <hr style="border: 2px solid #0072C6; margin: 20px 0;">
        <p style="font-size: 18px; color: #333333; margin: 0; text-align: center;">Please go to your profile to print your receipt.</p>
        </div>';

        $processorders = order::where('id', $id)
            ->where('user_id', $user)
            ->where('status', 'Processing')->first();

        if (!$processorders) {
            abort(404);
        } else {
            $processorders->status = 'On Delivery';
            $processorders->save();
        }

        try {
            $mail->send();
            return response()->json(['status' => 'success', 'message' => 'Email sent successfully']);
        } catch (Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Error: ' . $mail->ErrorInfo]);
        }
    }

    public function sales()
    {
        $orders = DB::table('orders')
            ->join('users', 'users.id', '=', 'orders.user_id')
            ->join('shipments', 'shipments.id', '=', 'orders.shipment_id')
            ->join('payments', 'payments.id', '=', 'orders.payment_id')
            ->select(
                'orders.*',
                'shipments.shipment_name',
                'users.name',
                'shipments.shipment_cost',
                'payments.payment_name'
            )
            ->where('status', 'Delivered')->get();

        $sales = DB::table('orders')
            ->join('orderitems', 'orderitems.order_id', '=', 'orders.id')
            ->join('products', 'products.id', '=', 'orderitems.product_id')
            ->select('orders.id', DB::raw('SUM(orderitems.price)  as totalprice'))
            ->groupBy('orders.id')->get();

        $chartsales = DB::table('orders')
            ->join('orderitems', 'orderitems.order_id', '=', 'orders.id')
            ->orderBy(DB::raw('month(orders.date_shipped)'), 'ASC')
            ->groupBy('month')
            ->pluck(DB::raw('sum(orderitems.price) as total'),
                    DB::raw('monthname(orders.date_shipped) as month'))
            ->all();
        // dd($chartsales);
        
            $salesChart = new SalesChart();
            $dataset = $salesChart->labels(array_keys($chartsales));
            $dataset = $salesChart->dataset(
                'Sales',
                'line',
                array_values($chartsales)
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
    
            $salesChart->title("", 40, '#212121', true,
             "'Helvetica Neue', 'Helvetica', 'Arial', sans-serif");
    
            $salesChart->options([
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
                            'gridLines' => ['display' => true],
                        ],
                    ],
                    'xAxes' => [
                        [
                            'categoryPercentage' => 0.8,
                            // 'barThickness' => 100,
                            'barPercentage' => 1,
                            'ticks' => ['beginAtZero' => true],
                            'gridLines' => ['display' => true],
                            'display' => true,
                        ],
                    ],
                ],
                "plugins" => '{datalabels: { font: { weight: \'bold\',
                    size: 36 },
                    color: \'white\',
                }}',
            ]);

        // $shipcosts = DB::table('orders')
        // ->join('shipments', 'shipements.id', '=', 'orders.shipment_id')
        // ->sum('shipments.shipment_cost')->get();

        return View('admin.sales', compact('orders', 'sales','salesChart'));
    }

    public function daterange(Request $request)
    {
        $startDate = $request->input('date1');
        $endDate = $request->input('date2');

        $dateorders = DB::table('orders')
            ->join('users', 'users.id', '=', 'orders.user_id')
            ->join('shipments', 'shipments.id', '=', 'orders.shipment_id')
            ->join('payments', 'payments.id', '=', 'orders.payment_id')
            ->select(
                'orders.*',
                'shipments.shipment_name',
                'users.name',
                'shipments.shipment_cost',
                'payments.payment_name'
            )
            ->where('status', 'Delivered')
            ->whereBetween('date_shipped', [$startDate, $endDate])
            ->get();

        $sales = DB::table('orders')
            ->join('orderitems', 'orderitems.order_id', '=', 'orders.id')
            ->join('products', 'products.id', '=', 'orderitems.product_id')
            ->select('orders.id', DB::raw('SUM(orderitems.price)  as totalprice'))
            ->groupBy('orders.id')->get();

        return view('admin.datesales', compact('dateorders', 'sales'));
    }
}
