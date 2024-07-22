<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \App\Models\OrderItem;
use \App\Models\Order;
use Illuminate\View\View;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function show($orderId): View
    {
        // Utilisation de findOrFail pour assurer que l'ordre existe
        $order = Order::with('orderItems.product')->findOrFail($orderId);

        // Récupérer les produits associés à l'ordre
        $products = $order->orderItems->map(function($item) {
            return [
                'name' => $item->product->name,
                'category' => $item->product->category,
                'quantity' => $item->quantity,
                'price' => $item->product->price,
            ];
        });

        $status = $order->status;

        return view('dashboard.orders.show', ['products' => $products, 'order' => $order, "status" => $status]);
    }


    public function update(Request $request, $orderId): RedirectResponse
    {   
        $order = Order::findOrFail($orderId);   

        $order->status = $request->status;
        $order->save();

        return redirect()->route('dashboard.order.show', $orderId)
            ->with('success', 'Order status updated successfully.');
    }   

    public function getOrders(): View
    {
        $user = Auth::user();

        $orders = Order::where('user_id', $user->id)->get();

        return view('cart.orders', compact('orders'));
    }


    public function getOrderDetails($orderId)
    {
    $order = Order::with('orderItems.product')->findOrFail($orderId);

    $products = $order->orderItems->map(function($item) {
        return [
            'name' => $item->product->name,
            'category' => $item->product->category,
            'quantity' => $item->quantity,
            'price' => $item->product->price,
        ];
    });

    return response()->json(['products' => $products]);
    }   

}
