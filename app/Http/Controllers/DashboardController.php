<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \App\Models\Product;
use \App\Models\Cart;
use \App\Models\Order;
use \App\Models\User;
use Illuminate\View\View;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Http\RedirectResponse;

class DashboardController extends Controller
{
    public function index(): View
    {
        $products = Product::all();

        $orders = Order::all();

        $users = User::all();

        $carts = Cart::all();

        return view('dashboard.index', compact('products', 'orders', 'users', 'carts'));
    }
}
