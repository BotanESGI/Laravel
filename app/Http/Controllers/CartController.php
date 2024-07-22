<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class CartController extends Controller
{
    public function AddProductCart(Request $request, $productId)
    {
        // Récupérer l'utilisateur authentifié
        $user = Auth::user();
        $product = Product::find($productId);

        if (!$product) {
            return redirect()->back()->withErrors("Erreur Produit non trouvé.");
        }

        // pour que sa fonctionne sur toute les pages si dans $request y a pas de quantité alors c'est 1 sinon on met $request->quantity
        $quantity = $request->quantity === null ? 1 : $request->quantity;

        // Vérifier si le panier existe pour l'utilisateur
        $cart = Cart::where('paid', 0)
            ->where('user_id', $user->id)
            ->first();
        
        // Si le panier n'existe pas, le créer
        if (!$cart) {
            $cart = Cart::create([
                'user_id' => $user->id,
                'status' => 'pending',
                'paid' => 0
            ]);
        }

        // Vérifier si l'article existe déjà dans le panier
        $cartItem = CartItem::where('cart_id', $cart->id)
            ->where('product_id', $productId)
            ->first();

        if ($cartItem) {
            // Si l'article existe, mettre à jour la quantité
            $cartItem->quantity += $quantity;
            $cartItem->save();
        } else {
            // Sinon, créer un nouvel article dans le panier
            CartItem::create([
                'cart_id' => $cart->id,
                'product_id' => $productId,
                'quantity' => $quantity,
            ]);
        }

        return redirect()->route('cart.get')->with('success', 'Produit ajouté au panier avec succès.');
    }


    public function getCart(): View
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login');
        }

        // Initialisation des variables
        $cart = null;
        $totalPrice = 0;
        $items = collect();

        $cart = Cart::where('paid', 0)
            ->where('user_id', $user->id)
            ->with('items.product')
            ->first();

        if ($cart){
            // Calculer le prix total du panier
            $totalPrice = $cart->items->sum(function($item) {
                return $item->product->price * $item->quantity;
            });
            
            $items = $cart->items;
        }

        // Récupérer tous les items du panier
        return view('cart.cart', compact('cart', 'totalPrice', 'items'));
    }

    public function paidCart(Request $request): View
    {
        $user = Auth::user();
        $totalPrice = $request->input('totalPrice');

        $cart = Cart::where('paid', 0)
            ->where('user_id', $user->id)
            ->with('items.product')
            ->first();

        // Créer une nouvelle commande
        $order = new Order();
        $order->user_id = $user->id;
        $order->status = 'Pre-order'; // ou tout autre statut approprié
        $order->total = $totalPrice;
        $order->save();

        // Parcourir les paniers non payés et créer des éléments de commande
        foreach ($cart->items as $item) {
            $orderItem = new OrderItem();
            $orderItem->order_id = $order->id;
            $orderItem->product_id = $item->product->id;
            $orderItem->quantity = $item->quantity;
            $orderItem->price = $item->product->price;
            $orderItem->save();
        }

        // Supprimer les items du panier
        foreach ($cart->items as $item) {
        }

        // Supprimer le panier
        $cart->delete();

        return view('cart.paid');
    }

    public function GetTransaction(): View
    {
        $user = Auth::user();
        $carts_paid = Cart::where('paid', 1)
            ->where('fk_user', $user->id)
            ->with('product')
            ->get();

        $TransactionsGrouped = $carts_paid->groupBy(function ($transaction) {
            return $transaction->updated_at->format('Y-m-d H:i:s');
        });

        $TransactionsGroupedFinal = $TransactionsGrouped->map(function ($transactionsGroup) {
            $totalPrice = $transactionsGroup->sum(function ($transaction) {
                return $transaction->product->price * $transaction->quantity;
            });

            $totalQuantity = $transactionsGroup->sum('quantity');

            return [
                'transactions' => $transactionsGroup,
                'totalPrice' => $totalPrice,
                'totalQuantity' => $totalQuantity,
                'formattedDate' => Carbon::parse($transactionsGroup->first()->updated_at)->format('F j, Y H:i:s')
            ];
        });

        return view('cart.history', [
            'groupedTransactions' => $TransactionsGroupedFinal
        ]);
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


    public function updateProductQuantity(Request $request, $cartId)
    {

        $request->validate([
            'quantity' => 'required|integer|min:1'
        ]);

        $quantity = $request->input('quantity');
        $productId = $request->input('productId');

        $cartItem = CartItem::where('cart_id', $cartId)
            ->where('product_id', $productId)
            ->first();


        if ($cartItem) {
            $cartItem->quantity = $quantity;
            $cartItem->save();
            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false], 404);
    }

}
