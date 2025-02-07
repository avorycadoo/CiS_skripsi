<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\detailKonfigurasi;
use App\Models\Employe;
use App\Models\Payment_Methods;
use App\Models\Product;
use App\Models\Sales;
use App\Models\Sales_detail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Auth;

class SalesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $sales = Sales::with(['customer', 'paymentMethod'])->get(); // Eager load customers and payment methods
        return view('sales.index', ['datas' => $sales]);
    }

    public function shipping()
    {
        $products = Product::all();
        return view('sales.shipping', compact('products'));
    }

    public function createShipping()
    {
        $products = Product::all();
        return view('sales.createShipping', compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Ambil nomor nota terakhir dari database
        $lastInvoice = Sales::orderBy('noNota', 'desc')->first();
        $newNumber = $lastInvoice ? (int) substr($lastInvoice->noNota, 3) + 1 : 1;

        // Ambil semua pelanggan, metode pembayaran, produk, dan karyawan
        $customers = Customer::all();
        $paymentMethods = Payment_Methods::all();
        $products = Product::all();
        $employees = Employe::all(); // Fetch employees

        $activeDiscounts = DB::table('detailkonfigurasi')
            ->where('konfigurasi_id', 1)
            ->where('statusActive', 1)
            ->get();

        $activeShippings = DB::table('detailkonfigurasi')
            ->where('konfigurasi_id', 2)
            ->where('statusActive', 1)
            ->get();

        // Fetch active payment configurations
        $activePayments = DB::table('detailkonfigurasi')
            ->where('konfigurasi_id', 3)
            ->where('statusActive', 1)
            ->get(); // Get only the IDs of active payments

        $activeCogs = DB::table('detailkonfigurasi')
            ->where('konfigurasi_id', 8)
            ->where('statusActive', 1)
            ->get();

        $user = Auth::user();
    
        // Find the corresponding employee ID
        $employeId = Employe::where('users_id', $user->id)->value('id');
        
        // Pass the payment methods to the view
        return view('sales.create', compact('newNumber', 'customers', 'paymentMethods', 'products', 'employees', 'activeDiscounts', 'activeShippings', 'activePayments', 'activeCogs', 'employeId'));

    }

    public function createReturn()
    {
        // Fetch all customers
        $customers = Customer::all();

        // Fetch sales data to use for autofilling invoice and purchase date
        $sales = Sales::with('customer')->get(); // Assuming you have a Sale model

        // Fetch sales details to get products that can be returned
        $salesDetails = Sales_detail::with(['product', 'sales.customer'])->get();

        // Group sales by customer
        $salesByCustomer = [];
        foreach ($sales as $sale) {
            $salesByCustomer[$sale->customers_id][] = $sale;
        }

        // Prepare an array to hold products for each sale
        $productsBySale = [];
        foreach ($salesDetails as $detail) {
            if (!isset($productsBySale[$detail->sales_id])) {
                $productsBySale[$detail->sales_id] = [];
            }
            // Include total_quantity in the product details
            $productsBySale[$detail->sales_id][] = [
                'product_id' => $detail->product_id,
                'product' => $detail->product, // Assuming this includes the product name
                'total_quantity' => $detail->total_quantity // Fetch total_quantity from sales_detail
            ];
        }

        // Pass the sales details, customers, and products by sale to the view
        return view('sales.retur', compact('salesDetails', 'customers', 'salesByCustomer', 'productsBySale'));
    }

    // public function returPenjualan()
    // {

    // }

    public function detail($id)
    {
        // Fetch the sale with its details
        $sale = Sales::with(['customer', 'paymentMethod', 'salesDetail.product']) // Eager load related data
            ->findOrFail($id); // Fetch the sale or fail if not found

        return view('sales.detail', compact('sale'));
    }

    public function dataKonfigurasi()
    {
        // Ambil semua discount yang belum aktif dari detailkonfigurasi
        $discounts = DB::table('detailkonfigurasi')->where('konfigurasi_id', 1)->get();
        $shippings = DB::table('detailkonfigurasi')->where('konfigurasi_id', 2)->get();
        $payments = DB::table('detailkonfigurasi')->where('konfigurasi_id', 3)->get();
        $cogs = DB::table('detailkonfigurasi')->where('konfigurasi_id', 8)->get();

        // Debugging line to check discounts
        // dd($discounts);

        return view('sales.konfigurasi', compact('discounts', 'shippings', 'payments', 'cogs'));
    }

    public function updateConfiguration(Request $request)
    {
        // Update discounts
        if ($request->has('discounts')) {
            $allDiscounts = DB::table('detailkonfigurasi')->where('konfigurasi_id', 1)->get();

            // If discounts are selected, update their status
            foreach ($allDiscounts as $discount) {
                if ($request->has('discounts') && in_array($discount->id, $request->input('discounts', []))) {
                    DB::table('detailkonfigurasi')
                        ->where('id', $discount->id)
                        ->update(['statusActive' => 1]);
                } else {
                    DB::table('detailkonfigurasi')
                        ->where('id', $discount->id)
                        ->update(['statusActive' => 0]);
                }
            }
        } else {
            // Jika tidak ada pembayaran yang dipilih, reset semua status menjadi 0
            DB::table('detailkonfigurasi')->where('konfigurasi_id', 1)
                ->where('types', '!=', 'mandatory') // Hanya reset yang bukan mandatory
                ->update(['statusActive' => 0]);
        }

        // Update shippings 
        if ($request->has('shippings')) {
            $allShippings = DB::table('detailkonfigurasi')->where('konfigurasi_id', 2)->get();

            foreach ($allShippings as $shipping) {
                if ($shipping->types === 'mandatory') {
                    DB::table('detailkonfigurasi')
                        ->where('id', $shipping->id)
                        ->update(['statusActive' => 1]);
                } elseif (in_array($shipping->id, $request->input('shippings', []))) {
                    DB::table('detailkonfigurasi')
                        ->where('id', $shipping->id)
                        ->update(['statusActive' => 1]);
                } else {
                    DB::table('detailkonfigurasi')
                        ->where('id', $shipping->id)
                        ->update(['statusActive' => 0]);
                }
            }
        } else {
            // Jika tidak ada pembayaran yang dipilih, reset semua status menjadi 0
            DB::table('detailkonfigurasi')->where('konfigurasi_id', 2)
                ->where('types', '!=', 'mandatory') // Hanya reset yang bukan mandatory
                ->update(['statusActive' => 0]);
        }


        // Update payments
        if ($request->has('payments')) {
            $allPayments = DB::table('detailkonfigurasi')->where('konfigurasi_id', 3)->get();

            foreach ($allPayments as $payment) {
                if ($payment->types === 'mandatory') {
                    DB::table('detailkonfigurasi')
                        ->where('id', $payment->id)
                        ->update(['statusActive' => 1]);
                } elseif (in_array($payment->id, $request->input('payments', []))) {
                    DB::table('detailkonfigurasi')
                        ->where('id', $payment->id)
                        ->update(['statusActive' => 1]);
                } else {
                    DB::table('detailkonfigurasi')
                        ->where('id', $payment->id)
                        ->update(['statusActive' => 0]);
                }
            }
        } else {
            // Jika tidak ada pembayaran yang dipilih, reset semua status menjadi 0
            DB::table('detailkonfigurasi')->where('konfigurasi_id', 3)
                ->where('types', '!=', 'mandatory') // Hanya reset yang bukan mandatory
                ->update(['statusActive' => 0]);
        }

        // Update cogs 
        if ($request->has('cogs')) {
            $checkedCogs = $request->input('cogs', []); // Ambil cogs yang dipilih
            $allCogs = DB::table('detailkonfigurasi')->where('konfigurasi_id', 8)->get();

            foreach ($allCogs as $cogs_method) {
                if ($cogs_method->types === 'mandatory') {
                    // Jika mandatory, tetap aktif
                    DB::table('detailkonfigurasi')
                        ->where('id', $cogs_method->id)
                        ->update(['statusActive' => 1]);
                } elseif (in_array($cogs_method->id, $checkedCogs)) {
                    // Jika pengiriman dipilih, aktifkan
                    DB::table('detailkonfigurasi')
                        ->where('id', $cogs_method->id)
                        ->update(['statusActive' => 1]);
                } else {
                    // Jika pengiriman tidak dipilih, reset status menjadi 0
                    DB::table('detailkonfigurasi')
                        ->where('id', $cogs_method->id)
                        ->update(['statusActive' => 0]);
                }
            }
        } else {
            // Jika tidak ada pengiriman yang dipilih, reset semua status menjadi 0
            DB::table('detailkonfigurasi')->where('konfigurasi_id', 8)
                ->where('types', '!=', 'mandatory') // Hanya reset yang bukan mandatory
                ->update(['statusActive' => 0]);
        }

        // Update discount values
        if ($request->has('discount_values')) {
            foreach ($request->input('discount_values') as $id => $value) {
                DB::table('detailkonfigurasi')
                    ->where('id', $id)
                    ->update(['value' => $value]);
            }
        }

        // Update shipping values
        if ($request->has('shipping_values')) {
            foreach ($request->input('shipping_values') as $id => $value) {
                DB::table('detailkonfigurasi')
                    ->where('id', $id)
                    ->update(['value' => $value]);
            }
        }

        return redirect()->route("sales.konfigurasi")->with('status', "Horray, Your konfigurasi data has been updated");
    }


    public function store(Request $request)
    {
        try {
            // Start database transaction
            DB::beginTransaction();

            try {
                // Map COGS method ID to name (based on your form values)
                $cogsMethodMap = [
                    '18' => 'fifo',
                    '19' => 'average'
                ];

                $cogsMethod = $cogsMethodMap[$request->input('cogs_method')] ?? null;

                // Validate that the COGS method is valid
                if (!$cogsMethod) {
                    throw new \Exception('Invalid COGS method selected');
                }

                // Generate invoice number
                $lastId = DB::table('sales')->max('id') ?? 0;
                $noNota = 'INV' . str_pad($lastId + 1, 4, '0', STR_PAD_LEFT);

                // Create the sale record
                $sale = Sales::create([
                    'noNota' => $noNota,
                    'total_price' => $request->input('final_price'),
                    'date' => $request->input('sales_date'),
                    'shipped_date' => $request->input('sales_shipdate'),
                    'employes_id' => $request->input('sales_employes_id'),
                    'payment_methods_id' => $request->input('payment_methods_id'),
                    'customers_id' => $request->input('sales_cust_id'),
                    'shipping_cost' => $request->input('shipping_cost', 0),
                    'discount' => $request->input('sales_disc', 0),
                ]);

                // Process products
                $products = json_decode($request->input('products'), true);

                if (empty($products)) {
                    throw new \Exception('No products provided for the sale');
                }

                // Insert sales details and collect product data for inventory update
                $productsForInventory = [];
                foreach ($products as $product) {
                    // Insert into sales_detail
                    DB::table('sales_detail')->insert([
                        'product_id' => $product['product_id'],
                        'sales_id' => $sale->id,
                        'total_quantity' => $product['quantity'],
                        'total_price' => $product['price'] * $product['quantity'],
                        'created_at' => now(),
                        'updated_at' => now()
                    ]);

                    // Prepare product data for inventory update
                    $productsForInventory[] = [
                        'product_id' => $product['product_id'],
                        'quantity' => $product['quantity']
                    ];
                }

                // Check the selected shipping method
                $selectedShippingMethod = detailKonfigurasi::where('name', 'Products are sent by store delivery service')->first();
                $isDeliveryService = $selectedShippingMethod && $selectedShippingMethod->value == $request->shipping_id;

                // Update inventory only if the shipping method is NOT the delivery service
                if (!$isDeliveryService) {
                    // Update inventory using the model's updateInventory method
                    $sale->updateInventory($cogsMethod, $productsForInventory);
                }

                // Commit transaction
                DB::commit();

                // Handle shipping logic
                if ($isDeliveryService) {
                    foreach ($products as $product) {
                        $purchasedProduct = Product::find($product['product_id']);
                        $purchasedProduct->in_order_penjualan += $product['quantity'];
                        $purchasedProduct->save();
                    }
                    return redirect()->route('sales.shipping');
                }

                return redirect()->route('sales.index')->with('success', 'Sale has been created successfully');

            } catch (\Exception $e) {
                DB::rollBack();
                \Log::error('Failed to create sale: ' . $e->getMessage());
                throw $e;
            }

        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()
                ->back()
                ->withErrors($e->errors())
                ->withInput();
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Failed to create sale. ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $data = Sales::find($id);
        return view("sales.edit", compact('data'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Sales $sales)
    {
        $updatedData = $sales;
        $updatedData->noNota = $request->noNota;
        $updatedData->total_price = $request->total_price;
        $updatedData->date = $request->date;
        $updatedData->shipped_date = $request->shipped_date;
        $updatedData->shipping_cost = $request->shipping_cost;
        $updatedData->discount = $request->discount;

        $updatedData->save();


        return redirect()->route("sales.index")->with('status', "Horray, Your transaction data is already updated");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // \Log::info('Deleting sales record:', ['id' => $sales->id]);

        try {
            $sales = Sales::find($id);
            $deletedData = $sales;
            $deletedData->delete();
            return redirect()->route('sales.index')->with('status', 'Horray ! Your data is successfully deleted !');
        } catch (\PDOException $ex) {
            $msg = "Failed to delete data ! Make sure there is no related data before deleting it";
            return redirect()->route('sales.index')->with('status', $msg);
        }
    }
}
