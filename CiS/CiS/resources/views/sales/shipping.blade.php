@extends('layouts.conquer')

@section('content')
    <div class="container">
        <h1>Product List</h1>
        <a href="salesCreateShipping" class="btn btn-primary mb-3">Create Shipping</a>
        <table class="table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Stock</th>
                    <th>In Order</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($products as $product)
                    <tr>
                        <td>{{ $product->name }}</td>
                        <td>{{ $product->stock }}</td>
                        <td>{{ $product->in_order_penjualan }}</td>
                        <td>
                            <form action="{{ route('products.create-shipping', $product->id) }}" method="POST">
                                @csrf
                                <input type="hidden" name="product_id" value="{{ $product->id }}">
                                <div class="input-group">
                                    <input type="number" name="quantity_shipped" class="form-control" min="1"
                                        required>
                                    <button type="submit" class="btn btn-primary">Ship</button>
                                </div>
                            </form>
                            <form action="{{ route('products.confirm-receipt', $product) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-success mt-2"
                                    {{ $product->in_order_penjualan == 0 ? 'disabled' : '' }}>Confirm Receipt</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
