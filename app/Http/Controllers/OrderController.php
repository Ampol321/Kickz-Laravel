<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\support\facades\DB;
use Illuminate\support\facades\Mail;
use App\DataTables\OrdersDataTable;
use App\Models\Shipment;
use App\Models\Payment;
use App\Models\Order;
use App\Mail\UpdateOrder;

class OrderController extends Controller
{
    public function index(OrdersDataTable $dataTable)
    {
        return $dataTable->render('admin.orders');
    }

    public function editform($id){
        $user = auth()->user()->id;
        $shipments = shipment::all();
        $payments = payment::all();
        $processing = DB::table('orders')
        ->join('shipments', 'shipments.id', '=', 'orders.shipment_id')
        ->join('payments', 'payments.id', '=', 'orders.payment_id')
        ->select('orders.*', 'shipments.shipment_name', 'payments.payment_name')
        ->where('orders.id', $id)->first();
        return View::make('users.editform', compact('processing','shipments','payments'));
    }

    public function editorder(Request $request, $id)
    {
        $user = auth()->user()->id;

        $processorders = order::where('id', $id)
            ->where('user_id', $user)
            ->where('status', 'Processing')->first();
        // dd($processorders);

        $processorders->shipping_address = $request->shipping_address;
        $processorders->shipment_id = $request->shipment_id;
        $processorders->payment_id = $request->payment_id;
        $processorders->credit_card = $request->credit_card;
        $processorders->save();

        if ($processorders->save()) {
            $name = auth()->user()->name;
            $updatedtotal = 0;
            $items = DB::table('orderitems')
                ->join('users', 'users.id', "=", 'orderitems.user_id')
                ->join('products', 'products.id', "=", 'orderitems.product_id')
                ->join('orders', 'orders.id', "=", 'orderitems.order_id')
                ->select('orderitems.*', 'products.product_img', 'products.product_name')
                ->where('order_id', $id)->get();
            // dd($items);
            
            $updatedorders = DB::table('orders')
                ->join('users', 'users.id', '=', 'orders.user_id')
                ->join('shipments', 'shipments.id', '=', 'orders.shipment_id')
                ->join('payments', 'payments.id', '=', 'orders.payment_id')
                ->select(
                    'orders.*',
                    'shipments.shipment_name',
                    'shipments.shipment_cost',
                    'payments.payment_name'
                )
                ->where('orders.id', $id)
                ->where('user_id', $user)
                ->where('status', 'Processing')
                ->first();
                // dd($updatedorders);

            foreach ($items as $item) {
                $updatedtotal += $item->price;
            }
            
            Mail::to('kicks6873@gmail.com')->send(new UpdateOrder($name,$updatedtotal,$items,$updatedorders));
        }

        return redirect('/profile')->with('message', 'Order Information successfully edited!');
    }

    public function cancelorder($id)
    {
        $user = auth()->user()->id;

        $processorders = order::where('id', $id)
            ->where('user_id', $user)
            ->where('status', 'Processing')->first();
        $processorders->status = 'Cancelled';
        $processorders->save();

        return back()->with('message', 'Order successfully cancelled!');
    }

    public function form($id){
        $user = auth()->user()->id;
        $delivered = order::find($id);
        return View::make('users.feedback', compact('delivered'));
    }

    public function rateorder(Request $request, $id)
    {
        $orders = order::find($id);
        $orders->ratings = $request->ratings;
        $orders->comments = $request->comments;
        $orders->save();

        return redirect('/profile')->with('message', 'Thank you for your feedback!');
    }

    public function update($id)
    {
        $processorders = order::where('id', $id)
            ->where('status', 'On Delivery')->first();

        $processorders->status = 'Delivered';
        $processorders->date_shipped = now();

        $processorders->save();

        return back()->with('message', 'Order status has been updated!');
    }
}
