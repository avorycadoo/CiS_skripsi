@extends('layouts/conquer')

@section('content')
    <div class="page-content">
        <div class="row">
            <div class="col-md-12">
                <h2 class="text-center mb-4">Product Catalog</h2>
                <a class="btn btn-warning btn-sm" style="background-color: #000000; color: white;"
                    href="{{ route('product.create') }}"> + Product</a><br>
                <div class="row">
                    @foreach ($datas as $d)
                        <div class="col-md-4 mb-4">
                            <div class="card shadow-sm h-100">
                                <!-- Product Image -->
                                <div class="card-img-top text-center">
                                    @if ($d->productImage)
                                        <img src="{{ asset('/images/' . $d->productImage->name) }}"
                                            class="img-fluid img-thumbnail" alt="{{ $d->name }}"
                                            style="max-height: 200px; object-fit: cover;">
                                    @else
                                        <img src="{{ asset('/images/no-image.png') }}" class="img-fluid img-thumbnail"
                                            alt="No image available" style="max-height: 200px; object-fit: cover;">
                                    @endif
                                </div>
                                <!-- Product Details -->
                                <div class="card-body">
                                    <h5 class="card-title text-center">{{ $d->name }}</h5>
                                    <p class="card-text text-muted text-center">{{ $d->desc }}</p>
                                    <p class="card-text text-center">
                                        <strong>Price:</strong> Rp {{ number_format($d->price, 0, ',', '.') }}
                                    </p>
                                    <p class="card-text text-center">
                                        <strong>Cost:</strong> Rp {{ number_format($d->cost, 0, ',', '.') }}
                                    </p>
                                    <p class="text-center text-muted">
                                        <small>Created: {{ $d->created_at }}</small><br>
                                        <small>Updated: {{ $d->updated_at }}</small>
                                    </p>
                                    <!-- Action Buttons -->
                                    <div class="text-center">
                                        <a class="btn btn-warning btn-sm" style="background-color: #000000; color: white;"
                                            href="{{ route('product.edit', $d->id) }}">Edit</a>
                                        <a class="btn btn-info btn-sm" style="background-color: #000000; color: white;"
                                            href="{{ url('product/uploadPhoto/' . $d->id) }}">Upload Photo</a>
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
