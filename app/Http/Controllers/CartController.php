<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function getCart(): View
    {
        $user = Auth::user();
        $carts = Cart::where('paid', 0)
            ->where('fk_user', $user->id)
            ->with('product')
            ->paginate(20);

        $totalPrice = $carts->sum(function($cart){
            return $cart->product->price;
        });

        return view('cart.cart', ['carts' => $carts, 'totalPrice' => $totalPrice]);
    }

    public function paidCart(): View
    {
        $user = Auth::user();

        // Fetch unpaid carts for the current user
        $carts_unpaid = Cart::where('paid', 0)
            ->where('fk_user', $user->id)
            ->with('product')
            ->get(); // Execute the query to get results

        // Update all fetched carts to mark them as paid
        foreach ($carts_unpaid as $cart_unpaid) {
            $cart_unpaid->paid = 1;
            $cart_unpaid->save();
        }

        return view('cart.paid');
    }

    public function removeProductCart($id)
    {
        Cart::find($id)->delete();
        return response()->json(['message' => 'Produit supprimé du panier avec succès']);
    }

    public function updateProductQuantity(Request $request, Cart $cart)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1'
        ]);

        $cart->quantity = $request->quantity;
        $cart->save();

        return response()->json(['success' => true]);
    }

}
