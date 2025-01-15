@extends('layouts.conquer')

@section('content')
    <form method="POST" action="{{ route('product.store') }}">
        @csrf

        <fieldset class="border p-3 mb-4">
            <legend class="w-auto">Product Information</legend>

            <div class="form-group">
                <label for="product_name">Product Name</label>
                <input type="text" class="form-control" name="product_name" placeholder="Enter Your Product" required>
                <small class="form-text text-muted">Please enter your product name</small>
            </div>

            <div class="form-group">
                <label for="product_desc">Description</label>
                <input type="text" class="form-control" name="product_desc" placeholder="Enter Product Description"
                    required>
                <small class="form-text text-muted">Please enter your product description</small>
            </div>

            <div class="form-group">
                <label for="product_price">Price</label>
                <input type="number" class="form-control" name="product_price" placeholder="Enter Product Price" required>
                <small class="form-text text-muted">Please enter your product price</small>
            </div>

            <div class="form-group">
                <label for="product_cost">Cost</label>
                <input type="number" class="form-control" name="product_cost" placeholder="Enter Product Cost" required>
                <small class="form-text text-muted">Please enter your product cost</small>
            </div>

            <div class="form-group">
                <label for="product_stock">Stock</label>
                <input type="number" class="form-control" name="product_stock" placeholder="Enter Product Stock" required>
                <small class="form-text text-muted">Please enter your product stock</small>
            </div>

            <div class="form-group">
                <label for="product_minstock">Minimum Stock</label>
                <input type="number" class="form-control" name="product_minstock" min="5"
                    placeholder="Enter Minimum Stock" required>
                <small class="form-text text-muted">Minimum stock cannot be less than 5.</small>
            </div>

            <div class="form-group">
                <label for="product_maksretur">Maximum Return</label>
                <input type="number" class="form-control" name="product_maksretur" max="5"
                    placeholder="Enter Maximum Return" required>
                <small class="form-text text-muted">Maximum return cannot be more than 5.</small>
            </div>

        </fieldset>

        <fieldset class="border p-3 mb-4">
            <legend class="w-auto">Additional Information</legend>

            <div class="form-group">
                <label for="product_image_id">Select Image</label>
                <select class="form-control" name="product_image_id">
                    <option value="" selected>Select Your Image</option>
                    <option value="">None</option> <!-- Option to unselect -->
                    @foreach ($product_image as $img)
                        <option value="{{ $img->id }}">{{ $img->name }}</option>
                    @endforeach
                </select>
                <small class="form-text text-muted">If there is no suitable product photo then select "None" or leave
                    blank!</small>
            </div>


            <div class="form-group">
                <label for="product_category_id">Select Category</label>
                <select class="form-control" name="product_category_id" required>
                    <option value="" disabled selected>Select Your Category</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
                <small class="form-text text-muted">Select a category for your product.</small>
            </div>

            <div class="form-group">
                <label for="product_supplier_id">Select Supplier</label>
                <select class="form-control" name="product_supplier_id" required>
                    <option value="" disabled selected>Select Your Supplier</option>
                    @foreach ($suppliers as $supplier)
                        <option value="{{ $supplier->id }}">{{ $supplier->company_name }}</option>
                    @endforeach
                </select>
                <small class="form-text text-muted">Select a supplier for your product.</small>
            </div>

            <div class="form-group">
                <label for="product_warehouse_id">Select Warehouse</label>
                <select class="form-control" name="product_warehouse_id" required>
                    <option value="" disabled selected>Select Your Warehouse</option>
                    @foreach ($warehouses as $warehouse)
                        <option value="{{ $warehouse->id }}">{{ $warehouse->name }}</option>
                    @endforeach
                </select>
                <small class="form-text text-muted">Select a warehouse for your product.</small>
            </div>
        </fieldset>

        <div class="d-flex justify-content-between">
            <a class="btn btn-info" href="{{ url()->previous() }}" >Cancel</a>
            <button type="submit" class="btn btn-primary">Submit</button>
        </div>
    </form>
@endsection
