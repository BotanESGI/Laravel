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

    <div class="mb-24">
        <div class="my-5 mb-10 flex justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('My Orders') }}
            </h2>
        </div>
        <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
            <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th scope="col" class="px-6 py-3">
                            Username
                        </th>
                        <th scope="col" class="px-6 py-3">
                            status
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Total
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Action
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @if (!$orders->isEmpty())
                        @foreach($orders as $order)
                            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                    {{ $order->user->name }}
                                </th>
                                <td class="px-6 py-4">
                                    {{ $order->status }}
                                </td>
                                <td class="px-6 py-4">
                                    {{ $order->total }}
                                </td>
                                <td class="px-6 py-4">
                                    <x-primary-button data-order-id="{{ $order->id }}" x-data="" x-on:click.prevent="openOrderModal({{ $order->id }})">{{ __('Détails') }}</x-primary-button>
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                            <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                No Orders
                            </th>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
    <x-modal name="detail-order" :show="$errors->userDeletion->isNotEmpty()" focusable>
        <form method="post" action="{{ route('profile.destroy') }}" class="p-6">
            <input type="hidden" name="order_id" id="modal-order-id">
            <div id="order-details" class="relative overflow-x-auto shadow-md sm:rounded-lg">
                <!-- Les détails de la commande seront chargés ici via JavaScript -->
            </div>
            <div class="mt-6 flex justify-end">
                <x-secondary-button x-on:click="$dispatch('close')">
                    {{ __('Back') }}
                </x-secondary-button>
            </div>
        </form>
    </x-modal>
@endsection

<script>
    function openOrderModal(orderId) {
        // Définir l'ID de la commande dans le champ caché
        document.getElementById('modal-order-id').value = orderId;

        // Faire une requête AJAX pour récupérer les détails de la commande
        fetch('/order-details/' + orderId)
            .then(response => response.json())
            .then(data => {
                // Remplir le conteneur des détails de la commande avec les données reçues
                document.getElementById('order-details').innerHTML = `
                    <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                            <tr>
                                <th scope="col" class="px-6 py-3">Product name</th>
                                <th scope="col" class="px-6 py-3">Category</th>
                                <th scope="col" class="px-6 py-3">Quantity</th>
                                <th scope="col" class="px-6 py-3">Price</th>
                            </tr>
                        </thead>
                        <tbody>
                            ${data.products.map(product => `
                                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                    <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                        ${product.name}
                                    </th>
                                    <td class="px-6 py-4">${product.category}</td>
                                    <td class="px-6 py-4">${product.quantity}</td>
                                    <td class="px-6 py-4">${product.price * product.quantity} €</td>
                                </tr>
                            `).join('')}
                        </tbody>
                    </table>
                `;
            })
            .catch(error => {
                console.error('Erreur lors de la récupération des détails de la commande:', error);
            });

        // Ouvrir le modal
        const event = new CustomEvent('open-modal', { detail: 'detail-order' });
        window.dispatchEvent(event);
    }
</script>

