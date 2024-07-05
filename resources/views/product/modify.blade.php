 <!-- Erreur si les champs ne sont pas rempli  -->
@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif 

@extends('dashboard')

@section("content")
<main class="mt-6" style="max-width: 1080px; margin:auto ">
    <h1 style="text-align: center; font-size: 4rem">Modifier votre article</h1>
    <form action="{{ route('product.update', $product->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div>
            <div>
            <div class="sm:col-span-3">
                <label for="name" class="block text-sm font-medium leading-6 text-white-900">Nom du produit :</label>
                <div class="mt-2">
                    <input type="text" name="name" id="name" autocomplete="given-name" value='{{ $product->name }}' class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 value:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" style="color: black">
                </div>
                </div>

                <div class="sm:col-span-3">
                <label for="description" class="block text-sm font-medium leading-6 text-white-900">Description : </label>
                <div class="mt-2">
                    <input type="text" name="description" id="description" autocomplete="family-name" value='{{ $product->description }}' class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 value:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" style="color: black">
                </div>
                </div>
                <div class="sm:col-span-3">
                <label for="prix" class="block text-sm font-medium leading-6 text-white-900">Prix :</label>
                <div class="mt-2">
                    <input type="number" name="prix" id="prix" autocomplete="family-name" value='{{ $product->price }}' class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 value:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" style="color: black">
                </div>
                </div>
            </div>
            <div class="col-span-full mt-20">
                <div class="mt-2 flex justify-center rounded-lg border border-dashed border-white-900/25 px-6 py-10">
                    <div class="text-center">
                        <svg class="mx-auto h-12 w-12 text-gray-300" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd" d="M1.5 6a2.25 2.25 0 012.25-2.25h16.5A2.25 2.25 0 0122.5 6v12a2.25 2.25 0 01-2.25 2.25H3.75A2.25 2.25 0 011.5 18V6zM3 16.06V18c0 .414.336.75.75.75h16.5A.75.75 0 0021 18v-1.94l-2.69-2.689a1.5 1.5 0 00-2.12 0l-.88.879.97.97a.75.75 0 11-1.06 1.06l-5.16-5.159a1.5 1.5 0 00-2.12 0L3 16.061zm10.125-7.81a1.125 1.125 0 112.25 0 1.125 1.125 0 01-2.25 0z" clip-rule="evenodd" />
                        </svg>
                        <p>Image: </p>
                    <div class="mt-4 flex text-sm leading-6 text-gray-600">
                        <label for="image" class="relative cursor-pointer rounded-md bg-white font-semibold text-indigo-600 focus-within:outline-none focus-within:ring-2 focus-within:ring-indigo-600 focus-within:ring-offset-2 hover:text-indigo-500">
                        <span>Upload a new file or drag and drop it</span>
  
                        <input id="image" name="image" type="file" class="sr-only">
                        </label>
                    </div>
                        @if ($product->image_path)
                            <p>{{ str_replace('/images/', '', $product->image_path) }} </p>
                            <img src="{{ asset($product->image_path) }}" alt="Image du produit" style="max-width: 200px; margin-top: 10px;">
                        @endif
                    </div>
                </div>
            </div>
            <div class="mt-6 flex items-center justify-end gap-x-6">
            <button type="submit" class="rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">Update</button>
            </div>
        </div>
    </form>
</main>
@endsection