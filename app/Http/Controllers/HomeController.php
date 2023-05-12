<?php

namespace App\Http\Controllers;

use Illuminate\support\facades\auth;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Cart;
use App\Models\Product;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home');
    }

    public function redirect()
    {
        $role = Auth::user()->role;
        if ($role == 'administrator') {
            return view('admin.dashboard');
        } else {
            $user = auth()->user();
            $count = cart::where('user_id', $user->id)->count();
            return view('products.index', compact('count', 'cart'));
        }
    }
}
