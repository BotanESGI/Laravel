@extends('index')

@section("content")
<div class="py-12">
    <ul class="grid grid-cols-3 gap-5">
        <a href="{{ route('product.create') }}" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Créer un produit</a>
        {{ __('Ajouter des articles') }}
    @foreach($products as $product)
        <div class="w-full max-w-sm bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700">
            <a href="#">
                <img class="p-8 rounded-t-lg" src="{{ $product->image_path }}" alt="product image" />
            </a>
            <div class="px-5 pb-5 ">
                <a href="#">
                    <h5 class="text-xl font-semibold tracking-tight text-gray-900 dark:text-white">{{ $product->name }}</h5>
                </a>
                <div class="flex items-center justify-between pb-10 pt-5">
                    <span class="text-3xl font-bold text-gray-900 dark:text-white">{{ $product->price }}</span>
                </div>
                <form action="{{ route('product.delete', $product->id) }}" method="POST" style="display: inline; ">
                    @csrf
                    @method('DELETE')
                    <button type='submit' class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Supprimer le produit</button>
                </form>
                <a href="{{ route('product.modify', $product->id) }}" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Modifier le produit</a>
            </div>
        </div>
    @endforeach
    </ul>
</div>
@endsection
