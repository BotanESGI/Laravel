<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \App\Models\Product;
use \App\Models\Cart;
use \App\Models\User;
use Illuminate\View\View;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;


class UsersController extends Controller
{
    public function create()
    {
        return view("dashboard.users.create-user-form");
    }

    public function createUser(Request $request)
    {

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:'.User::class],
            'role' => ['required', 'string', 'max:5'],
            'password' => ['required', 'confirmed'],
        ]);

        $user = new User;
        $user->name = $request->name;
        $user->email = $request->email;
        $user->role = ($request->role === "Admin") ? 1 : 0;
        $user->password = Hash::make($request->password);
        
        $user->save();

        return redirect()->route('dashboard.index')->with('success', 'Utilisateur créé avec succès.');

    }

    public function modify($userId)
    {
        $user = User::find($userId);

        if (!$user){
            return redirect()->back()->withErrors("Erreur utilisateur non trouvé.");
        }

        return view("dashboard.users.modify", compact("user"));
    }

    public function modifyUser(Request $request, $userId)
    {
        // Valider les champs présents dans la requête
        $validatedData = $request->validate([
            'name' => ['sometimes', 'required', 'string', 'max:255'],
            'email' => ['sometimes', 'required', 'string', 'email', 'max:255', 'unique:users,email,'.$userId],
            'role' => ['sometimes', 'required', 'string', 'max:5'],
            'password' => ['sometimes', 'required', 'confirmed'],
        ]);

        // Récupérer l'utilisateur à modifier
        $user = User::findOrFail($userId);

        // Mettre à jour uniquement les champs modifiés
        if ($request->has('name') && $user->name !== $request->name) {
            $user->name = $request->name;
        }
        if ($request->has('email') && $user->email !== $request->email) {
            $user->email = $request->email;
        }
        if ($request->has('role')) {
            $user->role = ($request->role === "Admin") ? 1 : 0;
        }
        if ($request->has('password')) {
            $user->password = Hash::make($request->password);
        }

        // Enregistrer les modifications
        $user->save();

        return redirect()->route('dashboard.index')->with('success', 'Utilisateur modifié avec succès.');
    }

    
    public function update()
    {
        return view("dashboard.users.create-user-form");
    }

    public function delete($id)
    {
        $user = User::find($id);

        if(!$user){
            return redirect()->back()->withErrors("Erreur utilisateur non trouvé.");
        }
        else{
            $user->delete();
            return redirect()->route('dashboard.index')->with('success', 'Produit supprimé avec succès.');
        }
    }

}
