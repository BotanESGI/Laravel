<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;

    protected $fillable = [
        'fk_user',
        'fk_product',
        'quantity',
        'paid'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'fk_product');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'fk_product');
    }
}
