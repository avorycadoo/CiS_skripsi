@extends('layouts.conquer')

@section('content')
    <div class="container mt-5">
        <!-- Flash Message Section -->
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- Header Section -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Purchase List</h2>
            <a href="{{ route('purchase.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Create New Purchase
            </a>
        </div>

        <!-- Purchase List Table -->
        <div class="card shadow-sm">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Invoice</th>
                                <th>Supplier</th>
                                <th>Purchase Date</th>
                                <th>Total Price</th>
                                <th>Payment Method</th>
                                <th>Warehouse</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($datas as $purchase)
                                <tr>
                                    <td>{{ $purchase->noNota }}</td>
                                    <td>{{ $purchase->supplier->company_name }}</td>
                                    <td>{{ $purchase->purchase_date }}</td>
                                    <td>Rp {{ number_format($purchase->total_price, 0, ',', '.') }}</td>
                                    <td>{{ $purchase->paymentMethod->name }}</td>
                                    <td>{{ $purchase->warehouse ? $purchase->warehouse->name : 'Directly in store' }}</td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('purchase.show', $purchase->id) }}"
                                                class="btn btn-info btn-sm" title="View Details">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            {{-- <a href="{{ route('purchase.edit', $purchase->id) }}"
                                                class="btn btn-warning btn-sm" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a> --}}
                                            <!-- Add delete button if needed -->
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center">No purchase records found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('javascript')
    <script>
        // Auto dismiss alert after 5 seconds
        document.addEventListener('DOMContentLoaded', function() {
            setTimeout(function() {
                const alerts = document.querySelectorAll('.alert');
                alerts.forEach(function(alert) {
                    const bsAlert = new bootstrap.Alert(alert);
                    bsAlert.close();
                });
            }, 5000);
        });
    </script>
@endsection
