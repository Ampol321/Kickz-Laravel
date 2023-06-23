<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use App\Models\Shipment;
use App\Models\Payment;
use App\Models\Orderitem;
use App\Models\User;

class UserController extends Controller
{
    public function index()
    {
        $users = user::where('id', Auth::id())->get();
        $user = auth()->user()->id;
        $orders = DB::table('orders')
            ->join('users', 'users.id', '=', 'orders.user_id')
            ->join('shipments', 'shipments.id', '=', 'orders.shipment_id')
            ->join('payments', 'payments.id', '=', 'orders.payment_id')
            ->select(
                'orders.*',
                'shipments.shipment_name',
                'shipments.shipment_cost',
                'payments.payment_name'
            )
            ->where('user_id', $user)
            ->orderBy('orders.id', 'DESC')->get();

        // dd($orders);

        $items = DB::table('orderitems')
            ->join('products', 'products.id', '=', 'orderitems.product_id')->get();
        // dd($items);
        // dd($processorder);

        $orderitems = orderitem::where('user_id', $user)->get();
        $processorders = DB::table('orders')
            ->join('users', 'users.id', '=', 'orders.user_id')
            ->join('shipments', 'shipments.id', '=', 'orders.shipment_id')
            ->join('payments', 'payments.id', '=', 'orders.payment_id')
            ->select(
                'orders.*',
                'shipments.shipment_name',
                'shipments.shipment_cost',
                'payments.payment_name'
            )
            ->where('user_id', $user)
            ->where('status', 'Processing')
            ->orderBy('orders.id', 'DESC')->get();
        // dd($processorders);

        $ondeliveryorders = DB::table('orders')
            ->join('users', 'users.id', '=', 'orders.user_id')
            ->join('shipments', 'shipments.id', '=', 'orders.shipment_id')
            ->join('payments', 'payments.id', '=', 'orders.payment_id')
            ->select(
                'orders.*',
                'shipments.shipment_name',
                'shipments.shipment_cost',
                'payments.payment_name'
            )
            ->where('user_id', $user)
            ->where('status', 'On Delivery')
            ->orderBy('orders.id', 'DESC')->get();

        $deliveredorders = DB::table('orders')
            ->join('users', 'users.id', '=', 'orders.user_id')
            ->join('shipments', 'shipments.id', '=', 'orders.shipment_id')
            ->join('payments', 'payments.id', '=', 'orders.payment_id')
            ->select(
                'orders.*',
                'shipments.shipment_name',
                'shipments.shipment_cost',
                'payments.payment_name'
            )
            ->where('user_id', $user)
            ->where('status', 'Delivered')
            ->orderBy('orders.id', 'DESC')->get();

        $cancelledorders = DB::table('orders')
            ->join('users', 'users.id', '=', 'orders.user_id')
            ->join('shipments', 'shipments.id', '=', 'orders.shipment_id')
            ->join('payments', 'payments.id', '=', 'orders.payment_id')
            ->select(
                'orders.*',
                'shipments.shipment_name',
                'shipments.shipment_cost',
                'payments.payment_name'
            )
            ->where('user_id', $user)
            ->where('status', 'Cancelled')
            ->orderBy('orders.id', 'DESC')->get();

        $totalprice = DB::table('orders')
            ->join('orderitems', 'orderitems.order_id', '=', 'orders.id')
            ->join('products', 'products.id', '=', 'orderitems.product_id')
            ->select('orders.id', DB::raw('SUM(orderitems.price)  as totalprice'))
            ->where('orders.user_id', $user)
            ->groupBy('orders.id')
            ->get();
        // dd($totalprice);

        $shipments = shipment::all();
        $payments = payment::all();

        return View('users.profile', compact(
            'users',
            'orders',
            'processorders',
            'ondeliveryorders',
            'deliveredorders',
            'cancelledorders',
            'items',
            'totalprice',
            'shipments',
            'payments'
        ));
    }

    public function edit($id)
    {
        $users = user::find($id);
        return View('users.edit', compact('users'));
    }

    public function update(Request $request, $id)
    {
        $rules = [
            'user_img' => 'image|mimes:jpeg,jpg,png',
            'name' => 'required|max:20',
            'email' => 'required',
            'phone' => 'required|min:11|max:11'
        ];

        $messages = [
            'image' => 'Image format not supported',
            'name.required' => 'Please Input your name',
            'name.max' => 'Name is too long',
            'email.required' => 'Please Input your email address',
            'phone.required' => 'Please Input your phone number',
            'phone.min' => 'Please Input a proper phone no. format',
            'phone.max' => 'Please Input a proper phone no. format',
        ];

        Validator::make($request->all(), $rules, $messages)->validate();

        $users = user::find($id);
        if ($request->file()) {
            $fileName = time() . '_' . $request->file('user_img')->getClientOriginalName();
            $filePath = $request->file('user_img')->storeAs('uploads', $fileName, 'public');
            $path = Storage::putFileAs(
                'public/images',
                $request->file('user_img'),
                $fileName
            );
            $users->user_img = '/storage/images/' . $fileName;
        }
        $users->name = $request->name;
        $users->email = $request->email;
        $users->phone = $request->phone;
        $users->save();

        return redirect('/profile');      
    }

    public function destroy(string $id)
    {
        user::destroy($id);
        return back()->with('message', 'User Deleted');;
    }

    public function receipt($id)
    {
        $user = auth()->user()->id;
        $name = auth()->user()->name;
        $totalprice = 0;

        $cart = DB::table('orders')
            ->join('orderitems', 'orders.id', 'orderitems.order_id')
            ->join('products', 'products.id', 'orderitems.product_id')
            ->select('orders.*', 'products.product_img', 'products.product_name', 'orderitems.price', 'orderitems.quantity')
            ->where('orders.id', $id)->where('orders.user_id', $user)
            ->get();
            
        $processorders = DB::table('orders')
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
            ->where('status', 'On Delivery')
            ->first();

        foreach ($cart as $carts) {
            $totalprice += $carts->price;
        }

        $data = [
            'name' => $name,
            'cart' => $cart,
            'processorders' => $processorders,
            'totalprice' => $totalprice
        ];

        $pdf = Pdf::loadView('Receipt', $data);
        return $pdf->download('receipt' . $id . '.pdf');
    }
}
