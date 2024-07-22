@extends('index')

@section("content")
 <div class="mb-24">
        <div class="my-5 mb-10 flex justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Details') }}
            </h2>
        </div>
        <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
            <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">

                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th scope="col" class="px-6 py-3">
                            Product name
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Category
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Quantity
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Price
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @if (!$products->isEmpty())
                        @foreach($products as $product)
                            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                    {{ $product["name"] }}
                                </th>
                                <td class="px-6 py-4">
                                    {{ $product['category'] }}
                                </td>
                                <td class="px-6 py-4">
                                    {{ $product['quantity'] }}
                                </td>
                                <td class="px-6 py-4">
                                    {{ $product['price'] }} €
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                            <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                No product
                            </th>
                        </tr>
                    @endif
                </tbody>
                <tfoot class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th scope="row" colspan="3" style="text-align: center; font-size: 1rem; font-family: sans-serif">
                            Change the status of the Order 
                        </th>
                        <td>
                            <form action="{{ route('dashboard.order.update', $order->id) }}" method="POST" class="flex" >
                                @csrf
                                @method('PUT')
                                <select name="status" id="status" style="background-color: #1E2937" class="block py-2.5 px-0 w-full text-sm text-white-500 bg-transparent border-0 border-b-2 border-gray-200 appearance-none dark:text-white-400 dark:border-white-700 focus:outline-none focus:ring-0 focus:border-gray-200 peer">
                                    <option selected style="color:white">{{$status}}</option>
                                        <option style="color:white">Pending</option>
                                        <option style="color:white">Paid</option>
                                        <option style="color:white">Shipped</option>
                                    </select>
                                <button type="submit" class="font-medium text-blue-600 dark:text-blue-500 hover:underline ms-3">Update</button>
                            </form>
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

@endsection