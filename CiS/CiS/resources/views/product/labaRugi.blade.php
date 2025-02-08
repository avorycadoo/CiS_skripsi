@extends('layouts/conquer')

@section('content')
<div class="container mx-auto px-6 py-8">
   <div class="mb-6">
       <h1 class="text-4xl font-light text-gray-600">Product Profit/Loss Analysis</h1>
   </div>

   <div class="bg-white">
       <div class="overflow-x-auto">
           <table class="w-full">
               <thead>
                   <tr class="border-y border-gray-200">
                       <th class="py-4 px-4 text-left text-sm font-medium text-gray-500">Product Name</th>
                       <th class="py-4 px-4 text-right text-sm font-medium text-gray-500">Cost (IDR)</th>
                       <th class="py-4 px-4 text-right text-sm font-medium text-gray-500">Price (IDR)</th>
                       <th class="py-4 px-4 text-right text-sm font-medium text-gray-500">Stock</th>
                       <th class="py-4 px-4 text-right text-sm font-medium text-gray-500">Profit per Unit</th>
                       <th class="py-4 px-4 text-right text-sm font-medium text-gray-500">Profit Margin (%)</th>
                       <th class="py-4 px-4 text-right text-sm font-medium text-gray-500">Total Potential Profit</th>
                   </tr>
               </thead>
               <tbody class="divide-y divide-gray-100">
                   @foreach($products as $product)
                   <tr class="hover:bg-gray-50/50">
                       <td class="py-4 px-4">
                           <div class="text-gray-800 font-medium mb-1">{{ $product->name }}</div>
                           <div class="text-sm text-gray-400">Category: {{ optional($product->categories)->name ?? 'N/A' }}</div>
                       </td>
                       <td class="py-4 px-4 text-right font-medium text-gray-600">
                           {{ number_format($product->cost, 0, ',', '.') }}
                       </td>
                       <td class="py-4 px-4 text-right font-medium text-gray-600">
                           {{ number_format($product->price, 0, ',', '.') }}
                       </td>
                       <td class="py-4 px-4 text-right font-medium text-gray-600">
                           {{ number_format($product->stock, 0, ',', '.') }}
                       </td>
                       <td class="py-4 px-4 text-right">
                           <span class="font-medium {{ $product->profit_per_unit < 0 ? 'text-red-500' : 'text-emerald-500' }}">
                               {{ number_format($product->profit_per_unit, 0, ',', '.') }}
                           </span>
                       </td>
                       <td class="py-4 px-4 text-right">
                           <span class="font-medium {{ $product->profit_margin_percentage < 0 ? 'text-red-500' : 'text-emerald-500' }}">
                               {{ number_format($product->profit_margin_percentage, 2, ',', '.') }}%
                           </span>
                       </td>
                       <td class="py-4 px-4 text-right">
                           <span class="font-medium {{ $product->total_profit_potential < 0 ? 'text-red-500' : 'text-emerald-500' }}">
                               {{ number_format($product->total_profit_potential, 0, ',', '.') }}
                           </span>
                       </td>
                   </tr>
                   @endforeach
               </tbody>
               <tfoot>
                   <tr class="border-t border-gray-200">
                       <td colspan="6" class="py-4 px-4 text-right font-medium text-gray-600">Total Potential Profit:</td>
                       <td class="py-4 px-4 text-right">
                           <span class="text-lg font-semibold {{ $products->sum('total_profit_potential') < 0 ? 'text-red-500' : 'text-emerald-500' }}">
                               IDR {{ number_format($products->sum('total_profit_potential'), 0, ',', '.') }}
                           </span>
                       </td>
                   </tr>
               </tfoot>
           </table>
       </div>
   </div>
</div>
@endsection