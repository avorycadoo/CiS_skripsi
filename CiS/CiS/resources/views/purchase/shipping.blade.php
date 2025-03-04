@extends('layouts.conquer')

@section('content')
    <div class="container mt-4">
        <h1>Receiving Management</h1>

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Pending Product Receive</h5>
            </div>
            <div class="card-body">
                
                @if (isset($pendingShipments) && $pendingShipments->count() > 0)
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Invoice</th>
                                <th>Date</th>
                                <th>Supplier</th>
                                <th>Total Price</th>
                                <th>Payment Method</th>
                                <th>Warehouse</th>
                                {{-- <th>COGS Method</th> --}}
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($pendingShipments as $purchase)
                                <tr>
                                    <td>{{ $purchase->noNota }}</td>
                                    <td>{{ date('d M Y', strtotime($purchase->purchase_date)) }}</td>
                                    <td>{{ $purchase->supplier->company_name }}</td>
                                    <td>Rp {{ number_format($purchase->total_price, 2) }}</td>
                                    <td>{{ $purchase->paymentMethod->name }}</td>
                                    <td>{{ $purchase->warehouse ? $purchase->warehouse->name : 'Directly In Store' }}</td>
                                    <td>
                                        <span class="badge bg-warning text-dark">Pending Product Receive</span>
                                    </td>
                                    <td>
                                        <a href="{{ route('purchase.ship-detail', $purchase->id) }}"
                                            class="btn btn-primary btn-sm">Process Receive</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <div class="alert alert-info">
                        No pending Receives found.
                    </div>
                @endif

            </div>
        </div>

        <div class="card mt-4">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0">Received Orders</h5>
            </div>
            <div class="card-body">
                @if (isset($shippedOrders) && $shippedOrders->count() > 0)
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Invoice</th>
                                <th>Date</th>
                                <th>Supplier</th>
                                <th>Warehouse</th>
                                <th>Total Price</th>
                                <th>Receive Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($shippedOrders as $purchase)
                                <tr>
                                    <td>{{ $purchase->noNota }}</td>
                                    <td>{{ date('d M Y', strtotime($purchase->purchase_date)) }}</td>
                                    <td>{{ $purchase->supplier->company_name }}</td>
                                    <td>{{ $purchase->warehouse ? $purchase->warehouse->name : 'Directly In Store' }}</td>
                                    {{-- @php
                                        dd($purchase->warehouse->address);
                                    @endphp --}}
                                    <td>Rp {{ number_format($purchase->total_price, 2) }}</td>
                                    <td>{{ date('d M Y', strtotime($purchase->receive_date)) }}</td>
                                    <td>
                                        <a href="{{ route('purchase.detail', $purchase->id) }}"
                                            class="btn btn-info btn-sm">View Details</a>
                                    </td>
                                </tr>
                            @endforeach

                        </tbody>
                    </table>
                @else
                    <div class="alert alert-info">
                        No received orders found.
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
