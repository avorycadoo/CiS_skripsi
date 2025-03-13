@extends('layouts.conquer')

@section('content')
    <div class="container mt-5">
        <h2 class="text-center mb-4">Create Transaction</h2>

        <form method="POST" action="{{ route('sales.store') }}">
            @csrf
            <div class="card shadow-sm">
                <div class="card-body">
                    <!-- Invoice Section -->
                    <div class="form-group">
                        <label for="no_nota">Invoice</label>
                        <input type="text" class="form-control" name="no_nota"
                            value="INV{{ str_pad($newNumber, 4, '0', STR_PAD_LEFT) }}" readonly>
                        <small class="form-text text-muted">This is your invoice number.</small>
                    </div>

                    <!-- Customer Selection -->
                    <div class="form-group">
                        <label for="customer_id">Customer Name</label>
                        <select class="form-control" name="sales_cust_id" required>
                            <option value="">Select Customer</option>
                            @foreach ($customers as $customer)
                                <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                            @endforeach
                        </select>
                        <small class="form-text text-muted">Please select a customer.</small>
                    </div>

                    <!-- Employee Selection -->
                    <div class="form-group">
                        <label for="employee_id">Employee Name:</label>
                        <input type="text" class="form-control" value="{{ Auth::user()->username }}" readonly>
                        <input type="hidden" name="sales_employes_id" value="{{ $employeId }}">
                    </div>
                    <!-- Date Section -->
                    <div class="form-group">
                        <label for="sales_date">Date</label>
                        <input type="date" class="form-control" name="sales_date" required>
                        <small class="form-text text-muted">Please select the date of the transaction.</small>
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
                            <input type="number" class="form-control" name="price" id="price" readonly>
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
                                <th>Amount</th>
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

                    <!-- Discount Section -->
                    <div class="card mt-4">
                        <div class="card-header" data-bs-toggle="collapse" data-bs-target="#discountCollapse">
                            <h5 class="mb-0">Discount Options <i class="bi bi-chevron-down"></i></h5>
                        </div>
                        <div class="collapse" id="discountCollapse">
                            <div class="card-body">
                                @if ($activeDiscounts->isNotEmpty())
                                    @foreach ($activeDiscounts as $discount)
                                        <div class="form-check mb-3">
                                            <input class="form-check-input discount-radio" type="radio" name="discount_id"
                                                value="{{ $discount->value }}" id="discount{{ $discount->id }}"
                                                data-name="{{ $discount->name }}">
                                            <label class="form-check-label" for="discount{{ $discount->id }}">
                                                {{ $discount->name }}
                                            </label>

                                            <!-- Add description text based on discount type -->
                                            @if (str_contains($discount->name, 'Discount per product'))
                                                <small class="d-block ms-4 text-muted">Minimum 1 product</small>
                                            @elseif (str_contains($discount->name, 'Minimum purchase discount'))
                                                <small class="d-block ms-4 text-muted">Minimum Rp.2.000.000</small>
                                            @elseif (str_contains($discount->name, 'Discount on the number of product purchases'))
                                                <small class="d-block ms-4 text-muted">Minimum 20 products</small>
                                            @endif

                                            <div class="ms-4 mt-2 discount-value-input" style="display: none;">
                                                <div class="input-group" style="max-width: 200px;">
                                                    <input type="number" class="form-control"
                                                        value="{{ $discount->value }}" min="0" max="100"
                                                        step="0.1">
                                                    <span class="input-group-text">%</span>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                    <input type="hidden" name="sales_disc" id="sales_disc" value="0">
                                @else
                                    <p>No active discounts available.</p>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Shipping Section -->
                    <div class="card mt-4 ">
                        <div class="card-header" data-bs-toggle="collapse" data-bs-target="#shippingCollapse">
                            <h5 class="mb-0">Shipping Options <i class="bi bi-chevron-down"></i></h5>
                        </div>
                        <div class="collapse" id="shippingCollapse">
                            <div class="card-body">
                                <label for="shipped_date">Shipped Date</label>
                                <input type="date" class="form-control" name="sales_shipdate" id="shipped_date">
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

                    {{-- SECTION FINAL PRICE --}}
                    <div class="card mt-4 border-0" style="background-color: #f8f9fa;">
                        <div class="card-body text-center">
                            <h4 class="text-muted">Final Price</h4>
                            <h2 class="display-4 mb-0 text-primary" id="finalPrice">Rp 0</h2>
                            {{-- <input type="hidden" name="final_price" id="final_price"> --}}
                        </div>
                        <input type="hidden" name="final_price" id="final_price">

                    </div>

                    {{-- SECTION PAYMENT METHODS --}}
                    @if ($activePayments->isNotEmpty())
                        <div class="form-group">
                            <label for="payment_methods_id">Payment Method</label>
                            <select class="form-control" name="payment_methods_id" required>
                                <option value="">Select Payment Method</option>
                                @foreach ($activePayments as $paymentMethod)
                                    <option value="{{ $paymentMethod->id }}">{{ $paymentMethod->name }}</option>
                                @endforeach
                            </select>
                            <small id="paymentMethodHelp" class="form-text text-muted">Please select a payment
                                method</small>
                        </div>
                    @else
                        <p>No active Payment available.</p>
                    @endif

                    <!-- COGS Method Section -->
                    <div class="form-group">
                        <label for="payment_methods_id">Select Cogs Method:</label>
                        <select class="form-control" name="cogs_method" required>
                            <option value="">Select COGS Method</option>
                            @foreach ($activeCogs as $cogs_method)
                                <option value="{{ $cogs_method->id }}">{{ $cogs_method->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="d-flex justify-content-end mt-4">
                        <a class="btn btn-secondary me-2" href="{{ url()->previous() }}">Cancel</a>
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

            // Call updateTotalPrice on page load to ensure initial calculation
            updateTotalPrice();

            function addProduct() {
                const productSelect = document.getElementById('product_id');
                const productName = productSelect.options[productSelect.selectedIndex].text;
                const price = parseFloat(document.getElementById('price').value) || 0;
                const quantity = parseInt(document.getElementById('quantity').value) || 1;
                const amount = price * quantity;

                if (productSelect.value) {
                    const tableBody = document.querySelector('#productTable tbody');
                    const row = document.createElement('tr');

                    row.innerHTML = `
                <td>${productName}</td>
                <td>Rp ${price.toFixed(2)}</td>
                <td>${quantity}</td>
                <td>Rp ${(amount).toFixed(2)}</td>
                <td><button type="button" class="btn btn-danger btn-sm remove-product">Remove</button></td>
            `;

                    tableBody.appendChild(row);

                    // Add the product to the products array
                    products.push({
                        product_id: productSelect.value,
                        quantity: quantity,
                        price: price
                    });

                    // Add event listener for the remove button
                    const removeBtn = row.querySelector('.remove-product');
                    removeBtn.addEventListener('click', function() {
                        row.remove();
                        // Remove the product from the products array based on row index
                        const index = Array.from(tableBody.children).indexOf(row);
                        if (index > -1) {
                            products.splice(index, 1);
                        }
                        updateProductsInput();
                        updateTotalPrice();
                    });

                    // Reset the product selection and quantity
                    productSelect.selectedIndex = 0;
                    document.getElementById('price').value = '';
                    document.getElementById('quantity').value = 1;

                    // Update the products input
                    updateProductsInput();
                    updateTotalPrice();
                }
            }

            function updateProductsInput() {
                // Update the hidden input with the JSON string
                productsInput.value = JSON.stringify(products);
            }

            // Update total price when discount is selected
            const discountRadios = document.querySelectorAll('.discount-radio');
            discountRadios.forEach(radio => {
                radio.addEventListener('change', function() {
                    // Hide all input fields first
                    document.querySelectorAll('.discount-value-input').forEach(input => {
                        input.style.display = 'none';
                    });

                    // Show input field for selected radio
                    if (this.checked) {
                        const inputDiv = this.closest('.form-check').querySelector(
                            '.discount-value-input');
                        if (inputDiv) {
                            inputDiv.style.display = 'block';

                            // Update the discount value when input changes
                            const valueInput = inputDiv.querySelector('input');
                            if (valueInput) {
                                valueInput.addEventListener('input', function() {
                                    radio.value = this.value;
                                    updateTotalPrice();
                                });
                            }
                        }
                    }
                    updateTotalPrice();
                });
            });

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
                        if (inputDiv) {
                            inputDiv.style.display = 'block';

                            // Update the shipping value when input changes
                            const valueInput = inputDiv.querySelector('input');
                            if (valueInput) {
                                valueInput.addEventListener('input', function() {
                                    radio.value = this.value;
                                    updateTotalPrice();
                                });
                            }
                        }
                    }
                    updateTotalPrice();
                });
            });

            function updateTotalPrice() {
                const rows = document.querySelectorAll('#productTable tbody tr');
                let totalPrice = 0;
                let totalQuantity = 0;
                let hasProducts = false;

                rows.forEach(row => {
                    const amount = parseFloat(row.children[3].textContent.replace('Rp ', '').replace(/,/g,
                        '')) || 0;
                    const quantity = parseInt(row.children[2].textContent) || 0;
                    totalPrice += amount;
                    totalQuantity += quantity;
                    if (quantity >= 1) hasProducts = true;
                });

                // Display the total price without discount or shipping
                const totalPriceElement = document.getElementById('totalPrice');
                if (totalPriceElement) {
                    totalPriceElement.textContent = `Rp ${totalPrice.toFixed(2)}`;
                }

                // Default values for discount and shipping
                let discountAmount = 0;
                let shippingValue = 0;

                // Only process discounts if there are any discount radios available
                const discountRadios = document.querySelectorAll('.discount-radio');
                if (discountRadios.length > 0) {
                    let perProductDiscount = document.querySelector('input[data-name="Discount per product"]');
                    let minimumPurchaseRadio = document.querySelector(
                        'input[data-name="Minimum purchase discount"]');
                    let volumeDiscountRadio = document.querySelector(
                        'input[data-name="Discount on the number of product purchases"]');

                    // Reset all radios
                    discountRadios.forEach(radio => {
                        radio.checked = false;
                        const valueInput = radio.closest('.form-check').querySelector(
                            '.discount-value-input');
                        if (valueInput) {
                            valueInput.style.display = 'none';
                        }
                    });

                    // Check conditions and apply highest applicable discount
                    if (totalQuantity >= 20 && volumeDiscountRadio) {
                        volumeDiscountRadio.checked = true;
                        const valueInput = volumeDiscountRadio.closest('.form-check').querySelector(
                            '.discount-value-input');
                        if (valueInput) {
                            valueInput.style.display = 'block';
                        }
                    } else if (totalPrice >= 2000000 && minimumPurchaseRadio) {
                        minimumPurchaseRadio.checked = true;
                        const valueInput = minimumPurchaseRadio.closest('.form-check').querySelector(
                            '.discount-value-input');
                        if (valueInput) {
                            valueInput.style.display = 'block';
                        }
                    } else if (hasProducts && perProductDiscount) {
                        perProductDiscount.checked = true;
                        const valueInput = perProductDiscount.closest('.form-check').querySelector(
                            '.discount-value-input');
                        if (valueInput) {
                            valueInput.style.display = 'block';
                        }
                    }

                    // Get selected discount value
                    const selectedDiscount = document.querySelector('input[name="discount_id"]:checked');
                    const discountValue = selectedDiscount ? parseFloat(selectedDiscount.value) : 0;

                    // Calculate discount amount
                    discountAmount = (totalPrice * discountValue) / 100;

                    const salesDiscElement = document.getElementById('sales_disc');
                    if (salesDiscElement) {
                        salesDiscElement.value = discountAmount.toFixed(2);
                    }
                }

                // Get shipping value if shipping options exist
                const selectedShipping = document.querySelector('input[name="shipping_id"]:checked');
                if (selectedShipping) {
                    shippingValue = parseFloat(selectedShipping.value) || 0;

                    const shippingCostElement = document.getElementById('shipping_cost');
                    if (shippingCostElement) {
                        shippingCostElement.value = shippingValue.toFixed(2);
                    }
                }

                // Calculate final price
                const finalPrice = totalPrice - discountAmount + shippingValue;

                // Update final price elements
                const finalPriceElement = document.getElementById('finalPrice');
                const finalPriceInput = document.getElementById('final_price');

                if (finalPriceElement) {
                    finalPriceElement.innerText = 'Rp ' + Math.max(finalPrice, 0).toLocaleString('id-ID', {
                        minimumFractionDigits: 2
                    });
                }

                if (finalPriceInput) {
                    finalPriceInput.value = Math.max(finalPrice, 0);
                }

                console.log('updateTotalPrice calculation:', {
                    totalPrice,
                    discountAmount,
                    shippingValue,
                    finalPrice: Math.max(finalPrice, 0)
                });
            }

            // Add event listeners for discount value inputs
            document.querySelectorAll('.discount-value-input input').forEach(input => {
                input.addEventListener('input', function() {
                    const radio = this.closest('.form-check').querySelector('.discount-radio');
                    if (radio) {
                        radio.value = this.value;
                        updateTotalPrice();
                    }
                });
            });

            // Attach the addProduct function to the button click event
            const addProductBtn = document.getElementById('addProduct');
            if (addProductBtn) {
                addProductBtn.addEventListener('click', addProduct);
            }

            // Handle form submission
            const form = document.querySelector('form');
            if (form) {
                form.addEventListener('submit', function(event) {
                    // Final update before submission
                    updateTotalPrice();
                    console.log('Final Price before submit:', document.getElementById('final_price').value);
                });
            }

            // Toggle collapsible sections if buttons exist
            const addDiscountBtn = document.getElementById('addDiscountBtn');
            if (addDiscountBtn) {
                addDiscountBtn.addEventListener('click', function() {
                    const discountSection = document.getElementById('discountSection');
                    if (discountSection) {
                        discountSection.style.display = discountSection.style.display === 'none' ? 'block' :
                            'none';
                    }
                });
            }

            const addShippingBtn = document.getElementById('addShippingBtn');
            if (addShippingBtn) {
                addShippingBtn.addEventListener('click', function() {
                    const shippingSection = document.getElementById('shippingSection');
                    if (shippingSection) {
                        shippingSection.style.display = shippingSection.style.display === 'none' ? 'block' :
                            'none';
                    }
                });
            }

            // Add event listeners for direct input changes if elements exist
            const salesDiscElement = document.getElementById('sales_disc');
            if (salesDiscElement) {
                salesDiscElement.addEventListener('input', updateTotalPrice);
            }

            const shippingCostElement = document.getElementById('shipping_cost');
            if (shippingCostElement) {
                shippingCostElement.addEventListener('input', updateTotalPrice);
            }
        });
    </script>
@endsection
