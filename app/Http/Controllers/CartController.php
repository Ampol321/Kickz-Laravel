<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\View;
use Illuminate\support\facades\Mail;
use Illuminate\Support\Facades\Auth;
use Illuminate\support\facades\DB;
use Illuminate\Http\Request;
use App\Models\Orderitem;
use App\Models\Shipment;
use App\Models\Payment;
use App\Models\Product;
use App\Models\Stock;
use App\Models\Order;
use App\Models\Cart;
use App\Mail\Notification;

class CartController extends Controller
{
    public function addcart(Request $request, $id)
    {
        if (Auth::id()) {
            // dd($id);
            $user = auth()->user()->id;
            $cart = cart::where('user_id', $user)
                ->where('product_id', $id)->first();
            // dd($cart);

            if ($cart) {
                $products = product::find($id);
                cart::where('user_id', $user)
                    ->where('product_id', $id)
                    ->update([
                        "quantity" => $cart->quantity + 1,
                        "price" => $cart->price + $products->price
                    ]);

                // return redirect()->back()->with('message', 'Product Added to Cart!');
                return response()->json(['product_in_cart' => true]);
            } else {
                $user = auth()->user();
                $products = product::find($id);
                $cart = new cart;

                $cart->user_id = $user->id;
                $cart->product_id = $products->id;
                $cart->quantity = 1;
                $cart->price = $products->price;
                $cart->save();

                // return redirect()->back()->with('message', 'Product Added to Cart!');
                return response()->json(['product_in_cart' => false]);
            }
        } else {
            return redirect('login');
        }
    }

    public function cartTable($id)
    {
        // $user = auth()->user()->id;
        $cart = DB::table('carts')
            ->join('users', 'users.id', "=", 'carts.user_id')
            ->join('products', 'products.id', "=", 'carts.product_id')
            ->select('carts.*', 'products.product_img', 'products.product_name')
            ->where('user_id', $id)
            ->get();

        return response()->json($cart);
    }

    public function shoppingcart($id)
    {
        $totalprice = 0;
        $shipments = shipment::all();
        $payments = payment::all();
        $cart = DB::table('carts')
            ->join('users', 'users.id', "=", 'carts.user_id')
            ->join('products', 'products.id', "=", 'carts.product_id')
            ->select('carts.*', 'products.product_img', 'products.product_name', 'products.price AS product_price')
            ->where('user_id', $id)
            ->get();
        // $cart = Cart::with(['user','product'])
        // ->where('user_id', $id)
        // ->get();

        foreach ($cart as $carts) {
            $totalprice += $carts->price;
        }

        // $data = [
        //     'cart' => $cart,
        //     'totalprice' => $totalprice,
        //     'shipments' => $shipments,
        //     'payments' => $payments,
        // ];

        return View::make('users.shoppingcart', compact('cart', 'totalprice', 'shipments', 'payments'));
        // return response()->json($cart);
    }

    public function increment($id)
    {
        $user = auth()->user()->id;
        $cart = cart::where('user_id', $user)
            ->where('product_id', $id)->first();
        $products = product::find($id);
        cart::where('user_id', $user)
            ->where('product_id', $id)
            ->update([
                "quantity" => $cart->quantity + 1,
                "price" => $cart->price + $products->price
            ]);

        // return back();
        return response()->json([
            'quantity' => $cart->quantity,
            'price' => $cart->price
        ]);
    }

    public function decrement($id)
    {
        $user = auth()->user()->id;
        $cart = cart::where('user_id', $user)
            ->where('product_id', $id)->first();
        $products = product::find($id);

        if ($cart->quantity > 1) {
            cart::where('user_id', $user)
                ->where('product_id', $id)
                ->update([
                    "quantity" => $cart->quantity - 1,
                    "price" => $cart->price - $products->price
                ]);
        }
        // return back();
        return response()->json([
            'quantity' => $cart->quantity,
            'price' => $cart->price
        ]);
    }

    public function checkout(Request $request)
    {
        $rules = [
            'shipping_address' => 'required',
            'shipment_id' => 'required',
            'payment_id' => 'required',
            'credit_card' => 'min:16|max:16'
        ];

        $messages = [
            'shipping_address.required' => 'Please input your shipping address',
            'shipment_id.required' => 'Please select a shipment',
            'payment_id.required' => 'Please select a payment',
            'credit_card' => 'Please input a proper credit card info format'
        ];

        Validator::make($request->all(), $rules, $messages)->validate();

        $user = auth()->user()->id;

        $cart = DB::table('carts')
            ->join('users', 'users.id', "=", 'carts.user_id')
            ->join('products', 'products.id', "=", 'carts.product_id')
            ->select('carts.*', 'products.product_img', 'products.product_name')
            ->where('user_id', $user)->get();

        $order = new Order;
        $order = Order::create([
            'user_id' => $user,
            'shipment_id' => $request->shipment_id,
            'payment_id' => $request->payment_id,
            'credit_card' => $request->credit_card,
            'shipping_address' => $request->shipping_address,
            'status' => 'Processing',
            'date_ordered' => now(),
            'date_shipped' => null,
        ]);
        $order->save();

        foreach ($cart as $carts) {
            // $orderitem = new Orderitem;
            // $orderitem = Orderitem::insert([
            //     'order_id' => $order->id,
            //     'user_id' => $order->user_id,
            //     'product_id' => $carts->product_id,
            //     'quantity' => $carts->quantity,
            //     'price' => $carts->price
            // ]);
            // $orderitem->orders()->products()->attach($carts->product_id, [
            //     'user_id' => $user,
            //     'quantity' => $carts->quantity,
            //     'price' => $carts->price
            // ]);
            $order->products()->attach($carts->product_id, [
                'user_id' => $user,
                'quantity' => $carts->quantity,
                'price' => $carts->price
            ]);

            $stocks = stock::where('product_id', $carts->product_id)->first();
            stock::where('product_id', $carts->product_id)->update([
                "stock" => $stocks->stock - $carts->quantity
            ]);
            $stocks->save();
        }

        if ($stocks->save()) {
            $name = auth()->user()->name;
            $totalprice = 0;
            $cart = DB::table('carts')
                ->join('users', 'users.id', "=", 'carts.user_id')
                ->join('products', 'products.id', "=", 'carts.product_id')
                ->select('carts.*', 'products.product_img', 'products.product_name')
                ->where('user_id', $user)->get();

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
                ->where('orders.id', $order->id)
                ->where('user_id', $user)
                ->where('status', 'Processing')
                ->first();

            foreach ($cart as $carts) {
                $totalprice += $carts->price;
            }

            Mail::to('kicks6873@gmail.com')->send(new Notification($name, $cart, $processorders, $totalprice));
        }

        cart::where('user_id', $user)->delete();
        return redirect()->back()->with('message', 'An order request has been sent!');
    }

    public function deletecart($id)
    {
        $user = auth()->user()->id;
        cart::where('user_id', $user)
            ->where('product_id', $id)->delete();
        // return redirect()->back()->with('message', 'Cart Item Deleted');
        return response()->json([]);
    }
}
