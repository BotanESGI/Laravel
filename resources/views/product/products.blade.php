@extends('index')

@section("content")
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif


    <div class="py-12">
        <div class="container">
            @if(auth()->check() && auth()->user()->role === 1)
                <div style="text-align:center;">
                    <a href="{{ route('product.create') }}" class="btn btn-primary">Créer un produit</a>
                </div>
            @endif
            <div style="display: flex; flex-wrap: wrap;">
                @foreach($products as $product)
                    <div style="flex: 1 1 calc(25% - 16px); max-width: 300px; margin: 8px; box-sizing: border-box;">
                        <div class="card h-100 w-100" style="max-width: 100%; display: flex; flex-direction: column;">
                            <img class="card-img-top p-4" src="{{ $product->image_path }}" alt="product image" style="max-width: 100%; height: 200px; object-fit: cover;" />
                            <div class="card-body d-flex flex-column" style="flex: 1 1 auto; display: flex; flex-direction: column;">
                                <h5 class="card-title" style="flex: 0 0 auto;">{{ $product->name }}</h5>
                                <p class="card-text" style="flex: 0 0 auto;">{{ $product->description }}</p>
                                <p class="card-text font-weight-bold" style="flex: 0 0 auto;">{{ $product->price }} €</p>
                                @if(auth()->check() && auth()->user()->role === 1)
                                <a href="{{ route('product.modify', $product->id) }}" class="btn btn-secondary">Modifier le produit</a>
                                <div style="margin-top: auto;">
                                    <form action="{{ route('product.delete', $product->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type='submit' class="btn btn-danger">Supprimer le produit</button>
                                    </form>
                                </div>
                                @endif
                                <! -- Il faut se connecté pour ajouter au panier, pas besoin de verifier car la route est dans le middlware d'auth -->
                                <form action="{{ route('cart.product.add', $product->id) }}" method="POST">
                                    @csrf
                                    <div class="form-group">
                                        <label for="quantity">Quantité :</label>
                                        <input style="color:black" type="number" id="quantity" name="quantity" class="form-control" value="1" min="1">
                                    </div>
                                    <button type="submit" class="btn btn-success">Ajouter au panier</button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection
