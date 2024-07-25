@extends('index')

@section("content")
<div class="flex flex-col md:flex-row -mx-4">
    <div class="md:flex-1 px-4">
        <div class="mb-4">
            <img class="w-full h-full rounded-lg" src="{{ $product->image_path }}" alt="Product Image">
        </div>
    </div>
    <div class="md:flex-1 px-4">
        <h2 class="text-2xl font-bold text-gray-800 dark:text-white mb-2">{{ $product->name }}</h2>
        <p class="text-gray-600 dark:text-gray-300 text-sm mb-4">
            Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed sed
            ante justo. Integer euismod libero id mauris malesuada tincidunt.
        </p>
        <div class="flex mb-4">
            <div class="mr-4">
                <span class="font-bold text-gray-700 dark:text-gray-300">Price:</span>
                <span class="text-gray-600 dark:text-gray-300">${{ $product->price }}</span>
            </div>
            <div>
                <span class="font-bold text-gray-700 dark:text-gray-300">Availability:</span>
                <span class="text-gray-600 dark:text-gray-300">In Stock</span>
            </div>
        </div>
        <div class="mb-4">
            <span class="font-bold text-gray-700 dark:text-gray-300">Select Color:</span>
            <div class="flex items-center mt-2">
                <button class="w-6 h-6 rounded-full bg-gray-800 dark:bg-gray-200 mr-2"></button>
                <button class="w-6 h-6 rounded-full bg-red-500 dark:bg-red-700 mr-2"></button>
                <button class="w-6 h-6 rounded-full bg-blue-500 dark:bg-blue-700 mr-2"></button>
                <button class="w-6 h-6 rounded-full bg-yellow-500 dark:bg-yellow-700 mr-2"></button>
            </div>
        </div>
        <div class="mb-4">
            <span class="font-bold text-gray-700 dark:text-gray-300">Select Size:</span>
            <div class="flex items-center mt-2">
                <button class="bg-gray-300 dark:bg-gray-700 text-gray-700 dark:text-white py-2 px-4 rounded-full font-bold mr-2 hover:bg-gray-400 dark:hover:bg-gray-600">S</button>
                <button class="bg-gray-300 dark:bg-gray-700 text-gray-700 dark:text-white py-2 px-4 rounded-full font-bold mr-2 hover:bg-gray-400 dark:hover:bg-gray-600">M</button>
                <button class="bg-gray-300 dark:bg-gray-700 text-gray-700 dark:text-white py-2 px-4 rounded-full font-bold mr-2 hover:bg-gray-400 dark:hover:bg-gray-600">L</button>
                <button class="bg-gray-300 dark:bg-gray-700 text-gray-700 dark:text-white py-2 px-4 rounded-full font-bold mr-2 hover:bg-gray-400 dark:hover:bg-gray-600">XL</button>
                <button class="bg-gray-300 dark:bg-gray-700 text-gray-700 dark:text-white py-2 px-4 rounded-full font-bold mr-2 hover:bg-gray-400 dark:hover:bg-gray-600">XXL</button>
            </div>
        </div>
        <div>
            <span class="font-bold text-gray-700 dark:text-gray-300">Product Description:</span>
            <p class="text-gray-600 dark:text-gray-300 text-sm mt-2">
                {{ $product->description }}
            </p>
        </div>
        <! -- Il faut se connecté pour ajouter au panier, pas besoin de verifier car la route est dans le middlware d'auth -->
        <form action="{{ route('cart.product.add', $product->id) }}" method="POST" class="text-center"> 
            @csrf
            <div class="form-group mt-10 text-left flex justify-between w-3/4">
                <label for="quantity">Quantité :</label>
                <input style="color:black; width:10rem" type="number" id="quantity" name="quantity" class="form-control inline w-40" value="1" min="1">
            </div>
            <button type="submit" class="mt-8 inline mx-auto text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Ajouter au panier</button>
        </form>
    </div>
</div>

{{-- Section pour les commentaires --}}
<div class="container mt-10">
    {{-- Messages de succès et d'erreur --}}
    @if(session('success'))
        <div class="bg-green-500 text-white p-4 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-500 text-white p-4 rounded mb-4">
            {{ session('error') }}
        </div>
    @endif

    <h3 class="text-xl font-bold text-gray-800 dark:text-white mb-4">Commentaires</h3>
    @foreach ($product->comments as $comment)
        <div class="mb-4 p-4 bg-gray-100 dark:bg-gray-800 rounded-lg">
            <p class="text-gray-700 dark:text-gray-300">{{ $comment->comment }}</p>
            <small class="text-gray-500 dark:text-gray-400">Par {{ $comment->user->name }} le {{ $comment->created_at->format('d/m/Y') }}</small>
            <div class="flex items-center">
                @for ($i = 1; $i <= 5; $i++)
                    @if ($i <= $comment->rating)
                        <svg class="w-4 h-4 text-yellow-500 fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path d="M10 15l-5.878 3.09 1.12-6.54L1 7.545l6.561-.954L10 1l2.439 5.591L19 7.545l-4.242 3.005 1.12 6.54z"/>
                        </svg>
                    @else
                        <svg class="w-4 h-4 text-gray-400 fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path d="M10 15l-5.878 3.09 1.12-6.54L1 7.545l6.561-.954L10 1l2.439 5.591L19 7.545l-4.242 3.005 1.12 6.54z"/>
                        </svg>
                    @endif
                @endfor
            </div>
        </div>
    @endforeach

    @auth
        <div class="mt-6">
            <h4 class="text-lg font-semibold text-gray-800 dark:text-white mb-2">Ajouter un commentaire</h4>
            <form action="{{ route('comments.store') }}" method="POST" class="space-y-4">
                @csrf
                <input type="hidden" name="product_id" value="{{ $product->id }}">
                <textarea name="comment" rows="4" class="w-full p-2 border rounded-lg dark:bg-gray-800 dark:text-gray-300" required></textarea>
                <div class="flex items-center mt-2">
                    @for ($i = 1; $i <= 5; $i++)
                        <label>
                            <input type="radio" name="rating" value="{{ $i }}" class="sr-only">
                            <svg class="w-6 h-6 cursor-pointer text-gray-400 hover:text-yellow-500 fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M10 15l-5.878 3.09 1.12-6.54L1 7.545l6.561-.954L10 1l2.439 5.591L19 7.545l-4.242 3.005 1.12 6.54z"/>
                            </svg>
                        </label>
                    @endfor
                </div>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg">Ajouter</button>
            </form>
        </div>
    @else
        <div class="mt-6">
            <p class="text-red-500">Vous devez être connecté pour ajouter un commentaire.</p>
        </div>
    @endauth
</div>
@endsection
