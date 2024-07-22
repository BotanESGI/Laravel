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

@extends('index')

@section("content")
<main class="mt-6" style="max-width: 1080px; margin:auto ">
    <h1 style="text-align: center; font-size: 4rem">Créer votre article</h1>
    <form action="{{ route('product.createProduct') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="">
            <div class="flex flex-col gap-10">
                @if ($errors->any())
                    <div class="mb-4 text-red-600">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="sm:col-span-3">
                    <label for="name" style="color: white" class="block text-sm font-medium leading-6 text-gray-900">Product name</label>
                    <div class="mt-2">
                        <input type="text" name="name" id="name" autocomplete="given-name" required
                            class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                             value="{{ old('name') }}">
                    </div>
                </div>

                <div class="sm:col-span-3">
                    <label for="description" style="color: white" class="block text-sm font-medium leading-6 text-gray-900">Product description</label>
                    <div class="mt-2">
                        <input type="text" name="description" id="description" autocomplete="family-name" required
                            class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                             value="{{ old('description') }}">
                    </div>
                </div>

                <div class="sm:col-span-3">
                    <label for="category" style="color: white" class="block mb-2 text-sm font-medium text-gray-900">Select an option</label>
                    <select id="category" name="category" required
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                        <option value="" disabled {{ old('category') ? '' : 'selected' }}>Choose a category</option>
                        <option value="electronics" {{ old('category') == 'electronics' ? 'selected' : '' }}>Electronics</option>
                        <option value="fashion" {{ old('category') == 'fashion' ? 'selected' : '' }}>Fashion</option>
                        <option value="home" {{ old('category') == 'home' ? 'selected' : '' }}>Home & Garden</option>
                        <option value="sports" {{ old('category') == 'sports' ? 'selected' : '' }}>Sports & Outdoors</option>
                        <option value="toys" {{ old('category') == 'toys' ? 'selected' : '' }}>Toys & Games</option>
                        <option value="beauty" {{ old('category') == 'beauty' ? 'selected' : '' }}>Beauty & Personal Care</option>
                        <option value="health" {{ old('category') == 'health' ? 'selected' : '' }}>Health & Wellness</option>
                        <option value="automotive" {{ old('category') == 'automotive' ? 'selected' : '' }}>Automotive</option>
                        <option value="books" {{ old('category') == 'books' ? 'selected' : '' }}>Books</option>
                        <option value="music" {{ old('category') == 'music' ? 'selected' : '' }}>Music & Entertainment</option>
                        <option value="office" {{ old('category') == 'office' ? 'selected' : '' }}>Office Supplies</option>
                        <option value="pet" {{ old('category') == 'pet' ? 'selected' : '' }}>Pet Supplies</option>
                        <option value="groceries" {{ old('category') == 'groceries' ? 'selected' : '' }}>Groceries</option>
                        <option value="baby" {{ old('category') == 'baby' ? 'selected' : '' }}>Baby Products</option>
                        <option value="jewelry" {{ old('category') == 'jewelry' ? 'selected' : '' }}>Jewelry</option>
                        <option value="tools" {{ old('category') == 'tools' ? 'selected' : '' }}>Tools & Home Improvement</option>
                        <option value="travel" {{ old('category') == 'travel' ? 'selected' : '' }}>Travel & Luggage</option>
                        <option value="software" {{ old('category') == 'software' ? 'selected' : '' }}>Software</option>
                        <option value="furniture" {{ old('category') == 'furniture' ? 'selected' : '' }}>Furniture</option>
                        <option value="appliances" {{ old('category') == 'appliances' ? 'selected' : '' }}>Appliances</option>
                        <option value="garden" {{ old('category') == 'garden' ? 'selected' : '' }}>Garden & Outdoor</option>
                        <option value="other" {{ old('category') == 'other' ? 'selected' : '' }}>Other</option>
                    </select>
                </div>

                <div class="sm:col-span-3">
                    <label for="prix" style="color: white" class="block text-sm font-medium leading-6 text-gray-900">Product price</label>
                    <div class="mt-2">
                        <input type="number" name="prix" step="any" id="prix" autocomplete="family-name" required
                            class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                             value="{{ old('prix') }}">
                    </div>
                </div>

                <div class="sm:col-span-3">
                    <label for="stock" style="color: white" class="block text-sm font-medium leading-6 text-gray-900">Product stock</label>
                    <div class="mt-2">
                        <input type="number" name="stock" id="stock" autocomplete="family-name" required
                            class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                             value="{{ old('stock') }}">
                    </div>
                </div>
            </div>
            <div class="col-span-full mt-20">
                <div class="mt-2 flex justify-center rounded-lg border border-dashed border-gray-300 px-6 py-10">
                    <div class="text-center">
                        <svg class="mx-auto h-12 w-12 text-gray-300" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd"
                                d="M1.5 6a2.25 2.25 0 012.25-2.25h16.5A2.25 2.25 0 0122.5 6v12a2.25 2.25 0 01-2.25 2.25H3.75A2.25 2.25 0 011.5 18V6zM3 16.06V18c0 .414.336.75.75.75h16.5A.75.75 0 0021 18v-1.94l-2.69-2.689a1.5 1.5 0 00-2.12 0l-.88.879.97.97a.75.75 0 11-1.06 1.06l-5.16-5.159a1.5 1.5 0 00-2.12 0L3 16.061zm10.125-7.81a1.125 1.125 0 112.25 0 1.125 1.125 0 01-2.25 0z"
                                clip-rule="evenodd" />
                        </svg>
                        <p>Choisir une image </p>
                        <div class="mt-4 flex text-sm leading-6 text-gray-600">
                            <label for="image"
                                class="relative cursor-pointer rounded-md bg-white font-semibold text-indigo-600 focus-within:outline-none focus-within:ring-2 focus-within:ring-indigo-600 focus-within:ring-offset-2 hover:text-indigo-500">
                                <span>Upload a file or drag and drop it</span>
                                <input id="image" name="image" type="file" required class="sr-only">
                            </label>
                        </div>
                        <p class="text-xs leading-5 text-gray-600">PNG, JPG, GIF up to 10MB</p>
                    </div>
                </div>
            </div>
            <div class="mt-6 flex items-center justify-end gap-x-6">
                <button type="submit"
                    class="rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">Save</button>
            </div>
        </div>
    </form>
</main>
@endsection
