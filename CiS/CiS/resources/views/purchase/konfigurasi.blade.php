@extends('layouts.conquer')

@section('content')
    <div class="container">
        <form action="{{ route('purchase.updateConfiguration') }}" method="POST"> <!-- Updated route -->
            @csrf
            {{-- SHIPPING KONFIGURATION --}}
            <h2 class="text-center mb-4">Shipping Configuration</h2>
            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="form-group">
                        <label for="shippings">Select Shippings:</label><br>
                        @foreach ($shippings as $shipping)
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="shippings[]"
                                    value="{{ $shipping->id }}" id="shipping{{ $shipping->id }}"
                                    {{ $shipping->statusActive === 1 ? 'checked' : '' }}
                                    {{ $shipping->types === 'mandatory' ? 'disabled' : '' }}>
                                <label class="form-check-label" for="shipping{{ $shipping->id }}">
                                    {{ $shipping->name }}
                                </label>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
<br>
            {{-- PAYMENT METHODS KONFIGURATION --}}
            <h2 class="text-center mb-4">Payment Methods Configuration</h2>
            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="form-group">
                        <label for="payments">Select Payment Methods:</label><br>
                        @foreach ($payments as $payment)
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="payments[]"
                                    value="{{ $payment->id }}" id="payment{{ $payment->id }}"
                                    {{ $payment->statusActive === 1 ? 'checked' : '' }}
                                    {{ $payment->types === 'mandatory' ? 'disabled' : '' }}>
                                <label class="form-check-label" for="payment{{ $payment->id }}">
                                    {{ $payment->name }}
                                </label>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
    </div>
@endsection
