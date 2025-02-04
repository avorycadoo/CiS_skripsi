@extends('layouts.conquer')

@section('content')
    <div class="container">
        <form action="{{ route('purchase.updateConfiguration') }}" method="POST">
            @csrf
            {{-- SHIPPING KONFIGURATION --}}
            <h2 class="text-center mb-4">Shipping Configuration</h2>
            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="form-group">
                        <label for="shippings">Select Shippings:</label><br>
                        @foreach ($shippings as $shipping)
                            <div class="d-flex align-items-center mb-2">
                                <input class="form-check-input me-2" type="checkbox" name="shippings[]"
                                    value="{{ $shipping->id }}" id="shipping{{ $shipping->id }}"
                                    {{ $shipping->statusActive === 1 ? 'checked' : '' }}
                                    {{ $shipping->types === 'mandatory' ? 'disabled' : '' }}>
                                <label class="form-check-label me-2" for="shipping{{ $shipping->id }}">
                                    {{ $shipping->name }}
                                </label>
                                <input type="number" name="shipping_values[{{ $shipping->id }}]" 
                                    class="form-control w-25" value="{{ $shipping->value }}">
                            </div>
                            <div>
                                <small class="text-muted">{{ $shipping->desc }}</small>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- PAYMENT METHODS KONFIGURATION --}}
            <h2 class="text-center mb-4">Payment Methods Configuration</h2>
            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="form-group">
                        <label for="payments">Select Payment Methods:</label><br>
                        @foreach ($payments as $payment)
                            <div class="d-flex align-items-center mb-2">
                                <input class="form-check-input me-2" type="checkbox" name="payments[]"
                                    value="{{ $payment->id }}" id="payment{{ $payment->id }}"
                                    {{ $payment->statusActive === 1 ? 'checked' : '' }}
                                    {{ $payment->types === 'mandatory' ? 'disabled' : '' }}>
                                <label class="form-check-label me-2" for="payment{{ $payment->id }}">
                                    {{ $payment->name }}
                                </label>
                            </div>
                            <div>
                                <small class="text-muted">{{ $payment->desc }}</small>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- COGS KONFIGURATION --}}
            <h2 class="text-center mb-4">COGS Method Configuration</h2>
            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="form-group">
                        <label for="cogs">Select COGS Method:</label><br>
                        @foreach ($cogs as $cogs_method)
                            <div class="d-flex align-items-center mb-2">
                                <input class="form-check-input me-2" type="checkbox" name="cogs[]"
                                    value="{{ $cogs_method->id }}" id="cogs_method{{ $cogs_method->id }}"
                                    {{ $cogs_method->statusActive === 1 ? 'checked' : '' }}
                                    {{ $cogs_method->types === 'mandatory' ? 'disabled' : '' }}>
                                <label class="form-check-label me-2" for="cogs_method{{ $cogs_method->id }}">
                                    {{ $cogs_method->name }}
                                </label>
                            </div>
                            <div>
                                <small class="text-muted">{{ $cogs_method->desc }}</small>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
            <button type="submit" class="btn btn-primary">Save Configurations</button>
        </form>
        <br><br>
    </div>
@endsection