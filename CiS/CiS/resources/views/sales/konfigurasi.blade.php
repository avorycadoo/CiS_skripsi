@extends('layouts.conquer')

@section('content')
    <div class="container">
        {{-- DISCOUNT KONFIGURATION --}}
        <form action="{{ route('sales.updateConfiguration') }}" method="POST"> <!-- Updated route -->
            @csrf
            <h2 class="text-center mb-4">Discount Configuration</h2>
            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="form-group">
                        <label for="discounts">Select Discounts:</label><br>
                        @foreach ($discounts as $discount)
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="discounts[]"
                                    value="{{ $discount->id }}" id="discount{{ $discount->id }}"
                                    {{ in_array($discount->id, old('discounts', [])) || $discount->statusActive == 1 ? 'checked' : '' }}>
                                <label class="form-check-label" for="discount{{ $discount->id }}">
                                    {{ $discount->name }}
                                </label>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
            <br>
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
                                    {{ $shipping->statusActive ? 'checked' : '' }}
                                    {{ $shipping->types === 'mandatory' ? 'checked' : '' }}
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
                                    {{ $payment->statusActive ? 'checked' : '' }}
                                    {{ $payment->types === 'mandatory' ? 'checked' : '' }}
                                    {{ $payment->types === 'mandatory' ? 'disabled' : '' }}>
                                <label class="form-check-label" for="payment{{ $payment->id }}">
                                    {{ $payment->name }}
                                </label>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
            <br>
            {{-- COGS KONFIGURATION --}}
            <h2 class="text-center mb-4">COGS Method Configuration</h2>
            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="form-group">
                        <label for="shippings">Select COGS Method:</label><br>
                        @foreach ($cogs as $cogs_method)
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="cogs[]"
                                    value="{{ $cogs_method->id }}" id="cogs_method{{ $cogs_method->id }}"
                                    {{ $cogs_method->statusActive === 1 ? 'checked' : '' }}
                                    {{ $cogs_method->types === 'mandatory' ? 'disabled' : '' }}>
                                <label class="form-check-label" for="cogs_method{{ $cogs_method->id }}">
                                    {{ $cogs_method->name }}
                                </label>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
            <br>
            <button type="submit" class="btn btn-primary">Submit</button>
            <br>
        </form>
        <br>
    </div>
@endsection
