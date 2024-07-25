<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'comment' => 'required|string',
            'product_id' => 'required|exists:products,id',
            'rating' => 'required|integer|min:1|max:5',
        ]);

        if (!auth()->check()) {
            return redirect()->back()->with('error', 'Vous devez être connecté pour ajouter un commentaire.');
        }

        $comment = new Comment();
        $comment->user_id = auth()->id();
        $comment->product_id = $request->product_id;
        $comment->comment = $request->comment;
        $comment->rating = $request->rating;
        $comment->save();

        return redirect()->back()->with('success', 'Commentaire ajouté avec succès !');
    }
}
