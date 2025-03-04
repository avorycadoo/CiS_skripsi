@extends('layouts.conquer')

@section('content')
    <div class="container mt-5">
        <h2 class="text-center mb-4">Create Purchase</h2>

        <form method="POST" action="{{ route('purchase.store') }}">
            @csrf
            <div class="card shadow-sm">
                <div class="card-body">
                    <!-- Invoice Section -->
                    <div class="form-group">
                        <label for="no_nota">Invoice</label>
                        <input type="text" class="form-control" name="no_nota"
                            value="PUR{{ str_pad($newNumber, 4, '0', STR_PAD_LEFT) }}" readonly>
                        <small class="form-text text-muted">This is your invoice number.</small>
                    </div>

                    <!-- Supplier Selection -->
                    <div class="form-group">
                        <label for="supplier_id">Supplier Name</label>
                        <select class="form-control" name="supplier_id" required>
                            <option value="">Select Supplier</option>
                            @foreach ($suppliers as $supplier)
                                <option value="{{ $supplier->id }}">{{ $supplier->company_name }}</option>
                            @endforeach
                        </select>
                        <small class="form-text text-muted">Please select a supplier.</small>
                    </div>

                    <!-- Date Section -->
                    <div class="form-group">
                        <label for="purchase_date">Purchase Date</label>
                        <input type="date" class="form-control" name="purchase_date" required>
                        <small class="form-text text-muted">Please select the date of the purchase.</small>
                    </div>

                    <!-- Receive Date Section -->
                    {{-- <div class="form-group">
                        <label for="receive_date">Receive Date</label>
                        <input type="date" class="form-control" name="receive_date" required>
                        <small class="form-text text-muted">Please select the date of receiving the products.</small>
                    </div> --}}

                    <div class="form-group">
                        <label for="warehouse_selection">Select Warehouse Option:</label><br>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="warehouse_option" id="multiWarehouse"
                                value="multi" checked>
                            <label class="form-check-label" for="multiWarehouse">Multi-warehouse</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="warehouse_option" id="directlyInStore"
                                value="direct">
                            <label class="form-check-label" for="directlyInStore">Directly in store</label>
                        </div>
                    </div>

                    <div id="warehouseDropdown" class="form-group mt-3">
                        <label for="warehouse_id">Select Warehouse:</label>
                        <select class="form-control" name="warehouse_id" id="warehouse_id">
                            <option value="" disabled selected>Select Your Warehouse</option>
                            @foreach ($warehouses as $warehouse)
                                <option value="{{ $warehouse->id }}">{{ $warehouse->name }}</option>
                            @endforeach
                        </select>
                        <small id="warehouseHelp" class="form-text text-muted">Please select a warehouse if
                            Multi-warehouse is selected.</small>
                    </div>

                    <!-- Product Selection -->
                    <h5 class="mt-4">Product Selection</h5>
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label for="product_id">Product Name</label>
                            <select class="form-control" name="product_id" id="product_id" onchange="getPrice(this)">
                                <option value="">Select a product</option>
                                @foreach ($products as $product)
                                    <option value="{{ $product->id }}" data-price="{{ $product->price }}">
                                        {{ $product->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label for="price">Price</label>
                            <input type="number" class="form-control" name="price" id="price">
                        </div>
                        <div class="col-md-2">
                            <label for="quantity">Quantity</label>
                            <input type="number" class="form-control" name="quantity" id="quantity" value="1"
                                min="1">
                        </div>
                        <div class="col-md-2 align-self-end">
                            <button type="button" class="btn btn-primary w-100" id="addProduct">Add</button>
                        </div>
                    </div>
                    <input type="hidden" name="products" id="productsInput">

                    <!-- List of Products -->
                    <h5 class="mt-4">List of Products</h5>
                    <table class="table table-striped" id="productTable">
                        <thead>
                            <tr>
                                <th>Product Name</th>
                                <th>Unit Price</th>
                                <th>Quantity</th>
                                <th>Subtotal</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>

                    <!-- Total Price Display -->
                    <div class="mt-3">
                        <h5>Total Price: <span id="totalPrice">Rp 0.00</span></h5>
                    </div>

                    <!-- Shipping Section -->
                    <div class="card mt-4 ">
                        <div class="card-header" data-bs-toggle="collapse" data-bs-target="#shippingCollapse">
                            <h5 class="mb-0">Shipping Options <i class="bi bi-chevron-down"></i></h5>
                        </div>
                        <div class="collapse" id="shippingCollapse">
                            <div class="card-body">
                                <label for="receive_date">Shipped Date</label>
                                <input type="date" class="form-control" name="receive_date" id="receive_date">
                            </div>

                            @if ($activeShippings->isNotEmpty())
                                @foreach ($activeShippings as $shipping)
                                    <div class="form-check mb-3">
                                        <input class="form-check-input shipping-radio" type="radio" name="shipping_id"
                                            value="{{ $shipping->value }}" id="shipping{{ $shipping->id }}"
                                            data-name="{{ $shipping->name }}">
                                        <label class="form-check-label" for="shipping{{ $shipping->id }}">
                                            {{ $shipping->name }}
                                        </label>
                                        <div class="ms-4 mt-2 shipping-value-input" style="display: none;">
                                            <div class="input-group" style="max-width: 200px;">
                                                <span class="input-group-text">Rp</span>
                                                <input type="number" class="form-control"
                                                    value="{{ $shipping->value }}" min="0" step="1000">
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                                <input type="hidden" name="shipping_cost" id="shipping_cost" value="0">
                            @else
                                <p>No active shipping options available.</p>
                            @endif
                        </div>
                    </div>

                    <!-- Final Price Section -->
                    <h5 class="mt-4">Final Price: <span id="finalPrice">Rp 0.00</span></h5>
                    <input type="hidden" name="final_price" id="final_price">

                    <!-- Payment Method Section -->
                    <div class="form-group">
                        <label for="payment_methods_id">Select Payment Method:</label>
                        <select class="form-control" name="payment_methods_id" required>
                            <option value="">Select Payment Method</option>
                            @foreach ($paymentMethods as $paymentMethod)
                                <option value="{{ $paymentMethod->id }}">{{ $paymentMethod->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- COGS Method Section -->
                    <!-- COGS Method Section -->
                    <div class="form-group">
                        <label for="cogs_method">Select Cogs Method:</label>
                        <select class="form-control" name="cogs_method" required>
                            <option value="">Select COGS Method</option>
                            @foreach ($activeCogs as $cogs_method)
                                <option value="{{ strtolower(str_replace('P-', '', $cogs_method->name)) }}">
                                    {{ $cogs_method->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- <div class="form-group">
                        <label for="warehouse_selection">Select Warehouse Option:</label><br>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="warehouse_option" id="multiWarehouse"
                                value="multi" checked>
                            <label class="form-check-label" for="multiWarehouse">Multi-warehouse</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="warehouse_option" id="directlyInStore"
                                value="direct">
                            <label class="form-check-label" for="directlyInStore">Directly in store</label>
                        </div>
                    </div>

                    <div id="warehouseDropdown" class="form-group mt-3">
                        <label for="warehouse_id">Select Warehouse:</label>
                        <select class="form-control" name="warehouse_id" id="warehouse_id">
                            <option value="" disabled selected>Select Your Warehouse</option>
                            @foreach ($warehouses as $warehouse)
                                <option value="{{ $warehouse->id }}">{{ $warehouse->name }}</option>
                            @endforeach
                        </select>
                        <small id="warehouseHelp" class="form-text text-muted">Please select a warehouse if
                            Multi-warehouse is selected.</small>
                    </div> --}}

                    {{-- @if ($activeWarehouses->isNotEmpty())
                        <div class="form-group">
                            <label for="warehouse_id">Warehouse</label>
                            <select class="form-control" name="warehouse_id" required>
                                <option value="">Select Warehouse</option>
                                @foreach ($activeWarehouses as $activeWarehouse)
                                    <option value="{{ $activeWarehouse->id }}">{{ $activeWarehouse->name }}</option>
                                @endforeach
                            </select>
                            <small id="paymentMethodHelp" class="form-text text-muted">Please select a warehouse</small>
                        </div>
                    @else
                        <p>No active Warehouse available.</p>
                    @endif --}}
                    {{-- <div class="form-group">
                        <label for="warehouse_id">Select Warehouse</label>
                        <select class="form-control" name="warehouse_id" required>
                            <option value="" disabled selected>Select Your Warehouse</option>
                            @foreach ($warehouses as $warehouse)
                                <option value="{{ $warehouse->id }}">{{ $warehouse->name }}</option>
                            @endforeach
                        </select>
                        <small class="form-text text-muted">Choose a warehouse to store your product purchases.</small>
                    </div> --}}

                    <div class="d-flex justify-content-end mt-4">
                        <a class="btn btn-info me-2" href="{{ url()->previous() }}">Cancel</a>
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection

@section('javascript')
    <script>
        let totalPrice = 0; // Initialize total price

        function getPrice(selectElement) {
            const priceInput = document.getElementById('price');
            const selectedOption = selectElement.options[selectElement.selectedIndex];
            const price = selectedOption.getAttribute('data-price') || 0;
            priceInput.value = price;
        }

        document.addEventListener('DOMContentLoaded', function() {
            const products = []; // Initialize an array to hold products
            const productsInput = document.getElementById('productsInput');
            const multiWarehouseRadio = document.getElementById('multiWarehouse');
            const directlyInStoreRadio = document.getElementById('directlyInStore');
            const warehouseDropdown = document.getElementById('warehouseDropdown');
            const warehouseSelect = document.getElementById('warehouse_id');

            // Show/hide warehouse dropdown based on selected option  
            multiWarehouseRadio.addEventListener('change', function() {
                warehouseDropdown.style.display = 'block';
            });

            directlyInStoreRadio.addEventListener('change', function() {
                warehouseDropdown.style.display = 'none';
            });

            // Initialize dropdown visibility  
            warehouseDropdown.style.display = multiWarehouseRadio.checked ? 'block' : 'none';

            function addProduct() {
                const productSelect = document.getElementById('product_id');
                const productName = productSelect.options[productSelect.selectedIndex].text;
                const price = parseFloat(document.getElementById('price').value) || 0;
                const quantity = parseInt(document.getElementById('quantity').value) || 1;
                const subtotal = price * quantity;

                if (productSelect.value) {
                    // Validate warehouse selection if multi-warehouse is selected
                    if (multiWarehouseRadio.checked && !warehouseSelect.value) {
                        alert('Please select a warehouse first!');
                        return;
                    }

                    const tableBody = document.querySelector('#productTable tbody');
                    const row = document.createElement('tr');

                    row.innerHTML = `
                    <td>${productName}</td>
                    <td>Rp ${price.toFixed(2)}</td>
                    <td>${quantity}</td>
                    <td>Rp ${(subtotal).toFixed(2)}</td>
                    <td><button type="button" class="btn btn-danger btn-sm remove-product">Remove</button></td>
                `;

                    tableBody.appendChild(row);
                    updateTotalPrice();

                    // Add the product to the products array
                    products.push({
                        product_id: productSelect.value,
                        quantity: quantity,
                        price: price
                    });

                    // Add event listener for the remove button
                    row.querySelector('.remove-product').addEventListener('click', function() {
                        row.remove();
                        updateTotalPrice();
                        // Remove the product from the products array
                        const index = products.findIndex(p => p.product_id === productSelect.value);
                        if (index > -1) {
                            products.splice(index, 1);
                        }
                        updateProductsInput();
                    });

                    // Reset the product selection and quantity
                    productSelect.selectedIndex = 0;
                    document.getElementById('price').value = '';
                    document.getElementById('quantity').value = 1;

                    // Update the products input
                    updateProductsInput();
                }
            }

            function updateProductsInput() {
                // Update the hidden input with the JSON string
                productsInput.value = JSON.stringify(products);
            }

            // Update total price when shipping is selected
            const shippingRadios = document.querySelectorAll('.shipping-radio');
            shippingRadios.forEach(radio => {
                radio.addEventListener('change', function() {
                    // Hide all input fields first
                    document.querySelectorAll('.shipping-value-input').forEach(input => {
                        input.style.display = 'none';
                    });

                    // Show input field for selected radio
                    if (this.checked) {
                        const inputDiv = this.closest('.form-check').querySelector(
                            '.shipping-value-input');
                        inputDiv.style.display = 'block';

                        // Update the shipping value when input changes
                        const valueInput = inputDiv.querySelector('input');
                        valueInput.addEventListener('input', function() {
                            radio.value = this.value;
                            updateTotalPrice();
                        });
                    }
                    updateTotalPrice();
                });
            });

            function updateTotalPrice() {
                const rows = document.querySelectorAll('#productTable tbody tr');
                let totalPrice = 0;

                rows.forEach(row => {
                    const amount = parseFloat(row.children[3].textContent.replace('Rp ', '').replace(/,/g,
                        '')) || 0;
                    totalPrice += amount;
                });

                document.getElementById('totalPrice').textContent = `Rp ${totalPrice.toFixed(2)}`;

                // Get selected shipping value
                const selectedShipping = document.querySelector('input[name="shipping_id"]:checked');
                const shippingValue = selectedShipping ? parseFloat(selectedShipping.value) : 0;
                document.getElementById('shipping_cost').value = shippingValue.toFixed(2);

                const finalPrice = totalPrice + shippingValue;

                // Ensure final price is not less than 0
                document.getElementById('finalPrice').innerText = 'Rp ' + Math.max(finalPrice, 0).toLocaleString();
                document.getElementById('final_price').value = Math.max(finalPrice, 0);
            }

            // Attach the addProduct function to the button click event
            document.getElementById('addProduct').addEventListener('click', addProduct);

            // Toggle shipping section visibility
            document.getElementById('addShippingBtn').addEventListener('click', function() {
                const shippingSection = document.getElementById('shippingSection');
                shippingSection.style.display = shippingSection.style.display === 'none' ? 'block' : 'none';
            });

            // Update total price when shipping input changes
            document.getElementById('shipping_cost').addEventListener('input', updateTotalPrice);
        });
    </script>
@endsection
