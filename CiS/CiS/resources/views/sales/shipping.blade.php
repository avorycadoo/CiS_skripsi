@extends('layouts.conquer')

@section('content')
    <div class="container mt-4">
        <h1>Shipping Management</h1>

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
                <h5 class="mb-0">Pending Shipments</h5>
            </div>
            <div class="card-body">
                @if (isset($pendingShipments) && $pendingShipments->count() > 0)
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Invoice</th>
                                <th>Date</th>
                                <th>Customer</th>
                                <th>Total Price</th>
                                <th>Payment Method</th>
                                {{-- <th>COGS Method</th> --}}
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($pendingShipments as $sale)
                                <tr>
                                    <td>{{ $sale->noNota }}</td>
                                    <td>{{ date('d M Y', strtotime($sale->date)) }}</td>
                                    <td>{{ $sale->customer->name }}</td>
                                    <td>Rp {{ number_format($sale->total_price, 2) }}</td>
                                    <td>{{ $sale->paymentMethod->name }}</td>
                                    {{-- <td>
                                        @php
                                            $cogsMethod = DB::table('detailkonfigurasi')
                                                ->where('id', $sale->cogs_method_id)
                                                ->value('name');
                                        @endphp
                                        {{ $cogsMethod ?? 'FIFO (Default)' }}
                                    </td> --}}
                                    <td>
                                        <span class="badge bg-warning text-dark">Pending Shipment</span>
                                    </td>
                                    <td>
                                        <a href="{{ route('sales.ship-detail', $sale->id) }}"
                                            class="btn btn-primary btn-sm">Process Shipment</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <div class="alert alert-info">
                        No pending shipments found.
                    </div>
                @endif
            </div>
        </div>

        <!-- Ganti bagian "Shipped Orders" dengan kode berikut -->
        <div class="card mt-4">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0">Shipped Orders</h5>
            </div>
            <div class="card-body">
                @if (isset($shippedOrders) && $shippedOrders->count() > 0)
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Invoice</th>
                                <th>Date</th>
                                <th>Customer</th>
                                <th>Recipient</th>
                                <th>Shipping Address</th>
                                <th>Total Price</th>
                                <th>Latest Shipment</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($shippedOrders as $sale)
                                <tr>
                                    <td>{{ $sale->noNota }}</td>
                                    <td>{{ date('d M Y', strtotime($sale->date)) }}</td>
                                    <td>{{ $sale->customer->name }}</td>
                                    <td>{{ $sale->recipients_name ?? $sale->customer->name }}</td>
                                    <td>
                                        @if ($sale->shipping_address)
                                            <span class="text-truncate d-inline-block" style="max-width: 150px;"
                                                title="{{ $sale->shipping_address }}">
                                                {{ $sale->shipping_address }}
                                            </span>
                                        @else
                                            <span class="text-muted">Default Address</span>
                                        @endif
                                    </td>
                                    <td>Rp {{ number_format($sale->total_price, 2) }}</td>
                                    <td>
                                        @if (isset($sale->latest_shipped_at))
                                            {{ date('d M Y H:i', strtotime($sale->latest_shipped_at)) }}
                                        @else
                                            {{ date('d M Y', strtotime($sale->shipped_date)) }}
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('sales.detail', $sale->id) }}" class="btn btn-info btn-sm">View
                                            Details</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <div class="alert alert-info">
                        No shipped orders found.
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
