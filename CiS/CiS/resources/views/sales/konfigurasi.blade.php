@extends('layouts.conquer')

@section('content')
    <div class="container mt-5">
        <form action="{{ route('sales.updateConfiguration') }}" method="POST">
            @csrf

            {{-- Discount Configuration --}}
            <div class="card mb-4">
                <div class="card-body">
                    <h2>Discount Configuration</h2>
                    <label for="discounts">Select Discounts:</label><br>
                    @foreach ($discounts as $discount)
                        <div class="d-flex align-items-center mb-2"> {{-- Use d-flex and align-items-center --}}
                            <input class="form-check-input me-2" type="checkbox" name="discounts[]" value="{{ $discount->id }}"
                                id="discount_{{ $discount->id }}" {{ $discount->statusActive ? 'checked' : '' }}>
                            <label class="form-check-label me-2" for="discount_{{ $discount->id }}">
                                {{ $discount->name }}
                            </label>
                            <input type="number" name="discount_values[{{ $discount->id }}]" class="form-control w-25"
                                value="{{ $discount->value }}">
                        </div>
                        <div>
                            <small class="text-muted">{{ $discount->desc }}</small>
                        </div>
                    @endforeach
                </div>
            </div>


            {{-- Shipping Configuration --}}
            <div class="card mb-4">
                <div class="card-body">
                    <h2>Shipping Configuration</h2>
                    <label for="shippings">Select Shippings:</label><br>
                    @foreach ($shippings as $shipping)
                        <div class="d-flex align-items-center mb-2">
                            <input class="form-check-input me-2" type="checkbox" name="shippings[]"
                                value="{{ $shipping->id }}" id="shipping_{{ $shipping->id }}"
                                {{ $shipping->statusActive ? 'checked' : '' }}>
                            <label class="form-check-label me-2" for="shipping_{{ $shipping->id }}">
                                {{ $shipping->name }}
                            </label>
                            <input type="number" name="shipping_values[{{ $shipping->id }}]" class="form-control w-25"
                                value="{{ $shipping->value }}">
                        </div>
                        <div>
                            <small class="text-muted">{{ $shipping->desc }}</small>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Payment Methods Configuration --}}
            <div class="card mb-4">
                <div class="card-body">
                    <h2>Payment Methods Configuration</h2>
                    <label for="payments">Select Payment Methods:</label><br>
                    @foreach ($payments as $payment)
                        <div class="d-flex align-items-center mb-2">
                            <input class="form-check-input me-2" type="checkbox" name="payments[]"
                                value="{{ $payment->id }}" id="payment_{{ $payment->id }}"
                                {{ $payment->statusActive ? 'checked' : '' }}>
                            <label class="form-check-label me-2" for="payment_{{ $payment->id }}">
                                {{ $payment->name }}
                            </label>
                        </div>
                        <div>
                            <small class="text-muted">{{ $payment->desc }}</small>
                        </div>
                    @endforeach
                </div>
            </div>


            {{-- COGS Sales Configuration --}}
            <div class="card mb-4">
                <div class="card-body">
                    <h2>COGS Sales Configuration</h2>
                    <label for="cogs">Select COGS:</label><br>
                    @foreach ($cogs as $cogs_method)
                        <div class="d-flex align-items-center mb-2">
                            <input class="form-check-input me-2" type="checkbox" name="cogs[]"
                                value="{{ $cogs_method->id }}" id="cogs_{{ $cogs_method->id }}"
                                {{ $cogs_method->statusActive ? 'checked' : '' }}>
                            <label class="form-check-label me-2" for="cogs_{{ $cogs_method->id }}">
                                {{ $cogs_method->name }}
                            </label>
                        </div>
                        <div>
                            <small class="text-muted">{{ $cogs_method->desc }}</small>
                        </div>
                    @endforeach
                </div>
            </div>
            <button type="submit" class="btn btn-primary mt-4">Save Configurations</button>
        </form>
        <br><br>
    </div>
@endsection
