<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \App\Models\Product;
use \App\Models\Cart;
use Illuminate\View\View;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Http\RedirectResponse;

class ProductController extends Controller
{
    public function index(): View
    {
        $products = Product::all();

        return view('product.index', ['products' => $products]);
    }

    public function create(): View
    {
        return view("product.create");
    }

    public function show($name, $id): View
    {
        $product = Product::findOrFail($id);

        if ($product->name !== $name){
            abord(404);
        }

        return view("product.show", compact('product'));
    }


    public function createProduct(Request $request) : RedirectResponse
    {
        // Validation des entrées
        $request->validate([
            'name' => 'required|string',
            'description' => 'required|string',
            'category' => 'required|string',
            'prix' => 'required|numeric',
            'stock' => 'required|numeric',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $product = new Product;

        $product->name = $request->name;
        $product->description = $request->description;
        $product->price = $request->prix;
        $product->stock = $request->stock;
        $product->category = $request->category;


        if ($request->hasFile('image')) {
            try {
                $image = $request->file('image');
                $name = time() . '.' . $image->getClientOriginalName();
                $destinationPath = public_path('/images');
                $image->move($destinationPath, $name);

                // Enregistrer le chemin de l'image dans le produit
                $product->image_path = '/images/' . $name;

            } catch (Exception $e) {
                return back()->with('error', 'Erreur lors du téléchargement de l\'image : ' . $e->getMessage());
            }
        } else {
            return back()->with('error', 'Aucun fichier image trouvé.');
        }

        $product->save();

        return redirect()->route('dashboard.index')->with('success', 'Produit créé avec succès.');
    }


    public function modifyProduct($id)
    {
        $product = Product::find($id);

        if(!$product)
        {
            return redirect()->back()
                ->withErrors("Erreur Produit non trouvé.");
        }
        else
        {
            return view("product.modify", ["product" => $product]);
        }
    }


    public function updateProduct(Request $request, $id)
    {
        // Validation des entrées
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'prix' => 'required|numeric',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $product = Product::findOrFail($id);

        $product->name = $request->name;
        $product->description = $request->description;
        $product->price = $request->prix;

        if ($request->hasFile('image')) {
            try {
                $image = $request->file('image');
                $name = time() . '.' . $image->getClientOriginalExtension();
                $destinationPath = public_path('/images');
                $image->move($destinationPath, $name);

                // Enregistrer le chemin de l'image dans le produit
                $product->image_path = '/images/' . $name;

            } catch (\Exception $e) {
                return back()->with('error', 'Erreur lors du téléchargement de l\'image : ' . $e->getMessage());
            }
        }

        $product->save();

        return redirect()->route('dashboard.index')->with('success', 'Produit mis à jour avec succès.');
    }



    public function deleteProduct($id) : RedirectResponse
    {
        $product = Product::find($id);


        if(!$product)
        {
            return redirect()->back()
                ->withErrors("Erreur Produit non trouvé.");
        }
        else
        {
            Cart::where('fk_product', $id)->delete();
            $product->delete();
            return redirect()->route('dashboard.index')->with('success', 'Produit supprimé avec succès.');
        }
    }

}
