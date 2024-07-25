<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;


    protected $fillable = [
        "user_id",
        'cart_id',
        "status",
        'total',
        'payment_date',
    ];

    /**
     * Obtenir l'utilisateur qui a passé la commande.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Obtenir le panier associé à cette commande.
     */
    public function cart()
    {
        return $this->belongsTo(Cart::class);
    }

    /**
     * Obtenir les articles de cette commande.
     */
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * Obtenir les produits de cette commande via les items de commande.
     */
    public function products()
    {
        return $this->hasManyThrough(Product::class, OrderItem::class, 'order_id', 'id', 'id', 'product_id');
    }
}
