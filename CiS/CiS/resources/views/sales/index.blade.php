@extends('layouts.conquer')

@section('content')
    <div class="page-content">
        <div class="row">
            <div class="col-md-12">
                <h2 class="text-center mb-4">Transactions Sales</h2>
                <div class="d-flex justify-content-end mb-3">
                    <a href="{{ route('sales.create') }}" class="btn btn-info"
                        style="background-color: #040404; color: white; border: none; transition: background-color 0.3s;">
                        + New Transactions
                    </a>
                </div>
                <div class="row">
                    @foreach ($datas as $d)
                        <div class="col-md-4 mb-4">
                            <div class="card shadow-sm h-100">
                                <!-- Sales Details -->
                                <div class="card-body">
                                    <h5 class="card-title text-center">{{ $d->noNota }}</h5>
                                    <p class="card-text text-center">
                                        <strong>Total Price:</strong> Rp {{ number_format($d->total_price, 0, ',', '.') }}
                                    </p>
                                    <p class="card-text text-center">
                                        <strong>Payment Method:</strong> {{ $d->paymentMethod->name ?? 'N/A' }}
                                    </p>
                                    <p class="card-text text-muted text-center">{{ $d->shipped_date }}</p>
                                    <p class="text-center text-muted">
                                        <small>Created: {{ $d->created_at }}</small><br>
                                        <small>Updated: {{ $d->updated_at }}</small>
                                    </p>
                                    <!-- Action Buttons -->
                                    <div class="text-center">
                                        <a class="btn btn-warning btn-sm"
                                            style="background-color: rgb(9, 9, 9); color: white; margin-right: 5px;"
                                            href="{{ route('sales.detail', $d->id) }}">Detail</a>
                                        <form method="POST" action="{{ route('sales.destroy', $d->id) }}"
                                            style="display: inline;">
                                            @csrf
                                            @method('DELETE')
                                            <input type="submit" value="Delete" class="btn btn-danger btn-sm"
                                                onclick="return confirm('Are you sure to delete {{ $d->id }} - {{ $d->noNota }}?');">
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endsection
