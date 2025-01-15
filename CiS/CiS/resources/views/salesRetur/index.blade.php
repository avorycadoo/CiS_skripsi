@extends('layouts.conquer')

@section('content')
    <div class="page-content">
        <div class="row">
            <div class="col-md-12">
                <h2 class="text-center mb-4">Return Information</h2>
                <div class="d-flex justify-content-end mb-3">
                    <a href="{{ route('salesRetur.create') }}" class="btn btn-info"
                        style="background-color: #040404; color: white; border: none; transition: background-color 0.3s;">
                        + New Return
                    </a>
                </div>
                <div class="row">
                    @foreach ($returs as $retur)
                        <div class="col-md-4 mb-4">
                            <div class="card shadow-sm h-100">
                                <div class="card-body">
                                    <h3 class="card-title text-center">Sales Return</h3>
                                    <h5 class="card-title text-center">Invoice Number: {{ $retur->invoice_number }}</h5>
                                    <p class="card-text text-center">
                                        <strong>Customer Name:</strong> {{ $retur->customer->name }}
                                    </p>
                                    <p class="card-text text-center">
                                        <strong>Status:</strong> {{ $retur->status }}
                                    </p>
                                    <div class="text-center">
                                        <a class="btn btn-warning btn-sm"
                                            style="background-color: rgb(9, 9, 9); color: white; margin-right: 5px;"
                                            href="{{ route('salesRetur.detail', $retur->id) }}">Detail</a>
                                        <form method="POST" action="{{ route('salesRetur.destroy', $retur->id) }}"
                                            style="display: inline;">
                                            @csrf
                                            @method('DELETE')
                                            <input type="submit" value="Delete" class="btn btn-danger btn-sm"
                                                onclick="return confirm('Are you sure to delete return for {{ $retur->invoice_number }}?');">
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
