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

<section class=" relative">
    <div class="w-full max-w-7xl px-4 md:px-5 lg-6 mx-auto">
        <h2 class="title font-manrope font-bold text-4xl leading-10 mb-8 text-center my-10 text-white" style="margin-bottom: 5rem">Shopping Cart</h2>
        @if (!empty($cart))
            @foreach ($items as $item)
                <div class="rounded-3xl border-2 border-gray-200 p-4 lg:p-8 grid mt-5 grid-cols-12 mb-8 max-lg:max-w-lg max-lg:mx-auto gap-y-4">
                    <div class="col-span-12 lg:col-span-2 img box">
                        <img src="{{ $item->product->image_path }}" alt="speaker image" class="max-lg:w-full lg:w-[180px] rounded-2xl">
                    </div>
                    <div class="col-span-12 lg:col-span-10 detail w-full lg:pl-3">
                        <div class="flex items-center justify-between w-full mb-4">
                            <h5 class="font-manrope font-bold text-2xl leading-9 text-white-900">{{ $item->product->name }}</h5>
                            <button class="rounded-full group flex items-center justify-center focus-within:outline-red-500 btn-delete" data-cart-id="{{ $cart->id }}">
                                <svg width="34" height="34" viewBox="0 0 34 34" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <circle class="fill-red-50 transition-all duration-500 group-hover:fill-red-400" cx="17" cy="17" r="17" fill="" />
                                    <path class="stroke-red-500 transition-all duration-500 group-hover:stroke-white" d="M14.1673 13.5997V12.5923C14.1673 11.8968 14.7311 11.333 15.4266 11.333H18.5747C19.2702 11.333 19.834 11.8968 19.834 12.5923V13.5997M19.834 13.5997C19.834 13.5997 14.6534 13.5997 11.334 13.5997C6.90804 13.5998 27.0933 13.5998 22.6673 13.5997C21.5608 13.5997 19.834 13.5997 19.834 13.5997ZM12.4673 13.5997H21.534V18.8886C21.534 20.6695 21.534 21.5599 20.9807 22.1131C20.4275 22.6664 19.5371 22.6664 17.7562 22.6664H16.2451C14.4642 22.6664 13.5738 22.6664 13.0206 22.1131C12.4673 21.5599 12.4673 20.6695 12.4673 18.8886V13.5997Z" stroke="#EF4444" stroke-width="1.6" stroke-linecap="round" />
                                </svg>
                            </button>
                        </div>
                        <p class="font-normal text-base leading-7 text-gray-500 mb-6 short-description">
                            {{ implode(' ', array_slice(explode(' ', $item->product->description), 0, 40)) }}<a href="{{ route('product.show', ['name' => $item->product->name, 'id' => $item->product->id]) }}" class="text-indigo-600"> More....</a>
                        </p>
                        <div class="flex justify-between items-center">
                            <div class="flex items-center gap-4">
                                <input type="number" class="product-quantity text-black" data-cart-id="{{ $cart->id }}" data-product-id="{{ $item->product->id }}" value="{{ $item->quantity }}" min="1">
                                <button class="btn-update-quantity" data-cart-id="{{ $cart->id }}" data-product-id="{{ $item->product->id }}">Changer la quantité</button>
                            </div>
                            <h6 class="text-indigo-600 font-manrope font-bold text-2xl leading-9 text-right">{{ $item->product->price * $item->quantity }} €</h6>
                        </div>
                    </div>
                </div>
            @endforeach
            <div class="flex flex-col md:flex-row items-center md:items-center justify-between pb-6 border-b border-gray-200">
                <h5 class="text-white-900 font-manrope font-semibold text-2xl leading-9 py-5 max-md:text-center max-md:mb-4">Subtotal</h5>
                <h6 class="font-manrope font-bold text-3xl lead-10 text-indigo-600">{{ $totalPrice }} €</h6>
            </div>
            <div class="max-lg:max-w-lg max-lg:mx-auto mb-10">
                <p class="font-normal text-base leading-7 text-gray-500 text-center mb-5 mt-6">Shipping taxes, and discounts calculated at checkout</p>
                <form action="{{ route('cart.paid') }}" method="POST">
                    @csrf
                    <input type="hidden" name="totalPrice" value="{{ $totalPrice }}">
                    <button type="submit" class="rounded-full py-4 px-6 bg-indigo-600 text-white font-semibold text-lg w-full text-center transition-all duration-500 hover:bg-indigo-700">Checkout</button>
                </form>
            </div>
        @else
            <h2>Shopping cart empty</h2>
        @endif
    </div>
</section>

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
            var cartId = $(this).data('cart-id');
            var productId = $(this).data('product-id');
            var quantity = $('.product-quantity[data-cart-id="' + cartId + '"]').val();

            console.log('Cart ID:', cartId);
            console.log('Product ID:', productId);
            console.log('Quantity:', quantity);

            $.ajax({
                url: '/cart/' + cartId,
                type: 'PUT',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    quantity: quantity,
                    productId: productId
                },
                success: function(data) {
                    console.log('Quantité mise à jour avec succès');
                    showSuccessMessage('Quantité mise à jour.');
                    location.reload();
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