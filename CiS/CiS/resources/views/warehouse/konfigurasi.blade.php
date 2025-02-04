@extends('layouts.conquer')

@section('content')
    <div class="container mt-5">
        <h2 class="text-center mb-4">Warehouses Configuration</h2>
        <form action="{{ route('warehouse.updateConfiguration') }}" method="POST">
            @csrf
            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="form-group">
                        <label for="shippings" class="font-weight-bold">Select Warehouses:</label><br>
                        @foreach ($warehouses as $warehouse)
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="warehouses[]"
                                    value="{{ $warehouse->id }}" id="warehouse{{ $warehouse->id }}"
                                    {{ in_array($warehouse->id, old('warehouses', [])) || $warehouse->statusActive == 1 ? 'checked' : '' }}>
                                <label class="form-check-label" for="warehouse{{ $warehouse->id }}">
                                    {{ $warehouse->name }}
                                </label>
                                <div>
                                    <small class="text-muted">{{ $warehouse->desc }}</small>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="text-center mt-4">
                        <button type="submit" class="btn btn-primary">Save Configurations</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection
