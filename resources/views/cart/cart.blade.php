@extends('dashboard')

@section('content')
    <h1>Panier :</h1>
    <ul id="cart-list">
        @if (!$carts->isEmpty())
            @foreach ($carts as $cart)
                <li id="cart-item-{{ $cart->id }}">
                    <div>
                        <img src="{{ $cart->product->image_path }}" alt="{{ $cart->product->name }}" style="max-width: 100px; max-height: 100px;">
                        <h3>Nom du produit : {{ $cart->product->name }}</h3>
                        <p>Description du produit : {{ $cart->product->description }}</p>
                        <p>Prix du produit : {{ $cart->product->price }} €</p>
                        <p>Quantité : <input type="number" class="product-quantity" data-cart-id="{{ $cart->id }}" value="{{ $cart->quantity }}" min="1">
                            <button class="btn-update-quantity" data-cart-id="{{ $cart->id }}">Changer la quantité</button></p>
                        <button class="btn-delete" data-cart-id="{{ $cart->id }}">Supprimer le produit du panier</button>
                        <br><br>
                    </div>
                </li>
            @endforeach
    </ul>
    <form action="{{ route('cart.paid') }}" method="POST">
        @csrf
        <button type="submit">Acheter (prix total : {{$totalPrice}} €)</button>
    </form>
    @else
        <p>Votre panier est vide :(</p>
    @endif

    {{ $carts->links() }}

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        // Your JavaScript for AJAX operations
    </script>
@endsection
