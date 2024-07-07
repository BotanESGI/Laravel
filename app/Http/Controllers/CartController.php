<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class CartController extends Controller
{
    public function AddProductCart(Request $request, $productId)
    {
        $product = Product::find($productId);
        $quantity = $request->input('quantity');

        $validator = Validator::make($request->all(), [
            'quantity' => 'required|integer|min:1',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors("Erreur sur la quantité")
                ->withInput();
        }

        if(!$product)
        {
            return redirect()->back()
                ->withErrors("Erreur Produit non trouvé.");
        }

        $user = Auth::user();

        $cart = Cart::where('paid', 0)
            ->where('fk_user', $user->id)
            ->where('fk_product', $productId)
            ->first();

        if ($cart) {
            $cart->update([
                'quantity' => $cart->quantity + $quantity,
            ]);
        } else {
            Cart::create([
                'fk_user' => $user->id,
                'fk_product' => $productId,
                'quantity' => $quantity,
                'paid' => 0,
            ]);
        }


        return redirect()->route('cart.get')->with('success', 'Produit ajouté au panier avec succès.');
    }


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
        $cart = Cart::find($id);
        if (!$cart) {
            return response()->json(['error' => 'Produit non trouvé dans le panier.'], 404);
        }
        else {
            $cart->delete();
            return response()->json(['message' => 'Produit supprimé du panier avec succès']);
        }
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
