<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \App\Models\Product;
use Illuminate\View\View;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Http\RedirectResponse;

class ProductController extends Controller
{
    public function index(): View
    {
        $products = Product::get();

        return view('product.products', ['products' => $products]);
    }

    public function create(): View
    {
        return view ("product.create");
    }

    public function createProduct(Request $request): RedirectResponse
    {
        $product = new Product;
        
        $product->name = $request->name;
        
        $product->description = $request->description;
        
        $product->price = $request->prix;
        
        $product->image_path = $request->image;

        // $request->validate([
        //     'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg',
        // ]);


        if ($request->file('image')) {
            $imagePath = $request->file('image')->store('images', 'public');
            return back()
                ->with('success','Image Uploaded successfully.')
                ->with('image', $imagePath);
        }

        $product->save();

        return Redirect::route('product.index');
    }

    public function modifyProduct($id)
    {
        $product = Product::find($id);

        dd($product);
    }

    public function deleteProduct($id)
        {
            // Trouver le produit par ID
            $product = Product::find($id);

            // Vérifier si le produit existe
            if ($product) {
                // Supprimer le produit
                $product->delete();

                // Rediriger avec un message de succès
                return redirect()->route('product.index')->with('success', 'Produit supprimé avec succès.');
            } else {
                // Rediriger avec un message d'erreur
                return redirect()->route('product.index')->with('error', 'Produit non trouvé.');
            }
        }

}
