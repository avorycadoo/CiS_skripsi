@extends('layouts.conquer')

@section('content')
    <div class="container mt-5">
        <!-- Header Section -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Sales List</h2>
            <a href="{{ route('sales.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Create New Sale
            </a>
        </div>

        <!-- Sales List Table -->
        <div class="card shadow-sm">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Invoice</th>
                                <th>Customer</th>
                                <th>Sales Date</th>
                                <th>Total Price</th>
                                <th>Payment Method</th>
                                <th>Shipped Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($datas as $sale)
                                <tr>
                                    <td>{{ $sale->noNota }}</td>
                                    <td>{{ $sale->customer->name }}</td>
                                    <td>{{ $sale->sales_date }}</td>
                                    <td>Rp {{ number_format($sale->total_price, 0, ',', '.') }}</td>
                                    <td>{{ $sale->paymentMethod->name ?? 'N/A' }}</td>
                                    <td>{{ $sale->shipped_date }}</td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('sales.detail', $sale->id) }}" class="btn btn-info btn-sm"
                                                title="View Details">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            {{-- <form method="POST" action="{{ route('sales.destroy', $sale->id) }}"
                                                style="display: inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm" title="Delete"
                                                    onclick="return confirm('Are you sure to delete {{ $sale->id }} - {{ $sale->noNota }}?');">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
                                            </form> --}}
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center">No sales records found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    </div>
@endsection
