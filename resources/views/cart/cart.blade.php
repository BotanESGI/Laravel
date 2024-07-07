@extends('index')

@section('content')

    @if (session('success'))
        <div class="alert alert-success" role="alert">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger" role="alert">
            {{ session('error') }}
        </div>
    @endif

    <h1>Panier :</h1>
    <div class="messages"></div> <!-- Conteneur pour les messages -->

    <ul id="cart-list">
        @if (!$carts->isEmpty())
            @foreach ($carts as $cart)
                <li id="cart-item-{{ $cart->id }}">
                    <div>
                        <img src="{{ $cart->product->image_path }}" alt="{{ $cart->product->name }}" style="max-width: 100px; max-height: 100px;">
                        <h3>Nom du produit : {{ $cart->product->name }}</h3>
                        <p>Description du produit : {{ $cart->product->description }}</p>
                        <p>Prix du produit : {{ $cart->product->price }} €</p>
                        <p>Quantité : <input style="color:black" type="number" class="product-quantity" data-cart-id="{{ $cart->id }}" value="{{ $cart->quantity }}" min="1">
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
        $(document).ready(function() {
            $(document).on('click', '.btn-delete', function(e) {
                e.preventDefault();
                var cartId = $(this).data('cart-id');
                $.ajax({
                    url: '/cart/' + cartId,
                    type: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(data) {
                        $('#cart-item-' + cartId).remove();
                        console.log('Produit supprimé avec succès');
                        showSuccessMessage('Produit supprimé du panier.');
                        if ($('#cart-list').children().length === 0) {
                            location.reload();
                        }
                    },
                    error: function(err) {
                        console.error('Erreur lors de la suppression du produit : ', err);
                        showErrorMessage('Erreur lors de la suppression du produit.');
                    }
                });
            });

            $(document).on('click', '.btn-update-quantity', function(e) {
                e.preventDefault();
                var cartId = $(this).data('cart-id');
                var quantity = $('.product-quantity[data-cart-id="' + cartId + '"]').val();
                $.ajax({
                    url: '/cart/' + cartId,
                    type: 'PUT',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        quantity: quantity
                    },
                    success: function(data) {
                        console.log('Quantité mise à jour avec succès');
                        showSuccessMessage('Quantité mise à jour.');
                    },
                    error: function(err) {
                        console.error('Erreur lors de la mise à jour de la quantité : ', err);
                        showErrorMessage('Erreur lors de la mise à jour de la quantité.');
                    }
                });
            });

            function showSuccessMessage(message) {
                $('.messages').html('<div class="alert alert-success" role="alert">' + message + '</div>');
            }

            function showErrorMessage(message) {
                $('.messages').html('<div class="alert alert-danger" role="alert">' + message + '</div>');
            }
        });
    </script>
@endsection
