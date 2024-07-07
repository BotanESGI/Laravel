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

    <h1>Historique des transactions :</h1><br>
    <hr>
    <ul id="cart-list">
        @if (!$groupedTransactions->isEmpty())
            @php $order = 1; @endphp
            @foreach ($groupedTransactions as $date => $group)
                <hr>
                <li>
                    <div>
                        <br>
                        <h3>Commande n° : {{ $order }}</h3>
                        <h3>Date de la commande : {{ $group['formattedDate'] }}</h3>
                        <h3>Nombre de produits au total : {{ $group['totalQuantity'] }}</h3>
                        <h3>Prix total de la commande : {{ $group['totalPrice'] }} €</h3><br>
                        @foreach ($group['transactions'] as $transaction)
                            <div id="cart-item-{{ $transaction->id }}">
                                <h4>Produit {{ $loop->iteration }} :</h4>
                                <img src="{{ $transaction->product->image_path }}" alt="{{ $transaction->product->name }}" style="max-width: 100px; max-height: 100px;">
                                <p>Nom du produit : {{ $transaction->product->name }}</p>
                                <p>Description du produit : {{ $transaction->product->description }}</p>
                                <p>Quantité : {{ $transaction->quantity }}</p>
                                <p>Prix : {{ $transaction->product->price }} €</p>
                                <br>
                            </div>
                        @endforeach
                        <br>
                    </div>
                </li>
                @php $order++; @endphp
            @endforeach
    </ul>
    @else
        <p>Votre historique de transaction est vide :(</p>
    @endif
@endsection
