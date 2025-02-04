<?php

namespace App\Http\Controllers;

use App\Models\Payment_Methods;
use App\Models\Product;
use App\Models\purchase;
use App\Models\Suppliers;
use App\Models\Warehouse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PurchaseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $purchases = purchase::with(['supplier', 'paymentMethod', 'warehouse'])->get(); // Eager load suppliers and payment methods
        return view('purchase.index', ['datas' => $purchases]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Generate invoice number
        $lastInvoice = Purchase::orderBy('id', 'desc')->first();
        $newNumber = $lastInvoice ? (int) substr($lastInvoice->noNota, 3) + 1 : 1;

        // Fetch suppliers, payment methods, and products
        $suppliers = Suppliers::all();
        $paymentMethods = Payment_Methods::all();
        $products = Product::all();
        $warehouses = Warehouse::all();
        $activeWarehouses = DB::table('detailkonfigurasi')
            ->where('konfigurasi_id', 6)
            ->where('statusActive', 1)
            ->get();
        $activeShippings = DB::table('detailkonfigurasi')
            ->where('konfigurasi_id', 4)
            ->where('statusActive', 1)
            ->get();

        $activeCogs = DB::table('detailkonfigurasi')
            ->where('konfigurasi_id', 7)
            ->where('statusActive', 1)
            ->get();

        return view('purchase.create', compact('newNumber', 'suppliers', 'paymentMethods', 'warehouses', 'products', 'activeWarehouses', 'activeShippings', 'activeCogs'));
    }

    public function detail($id)
    {
        // Fetch the sale with its details
        $purchase = purchase::with(['supplier', 'paymentMethod', 'purchaseDetails.product']) // Eager load related data
            ->findOrFail($id); // Fetch the sale or fail if not found

        // Debugging: Check if supplier is null
        if (is_null($purchase->supplier)) {
            Log::info("Supplier is null for purchase ID: " . $id);
        }
        return view('purchase.detail', compact('purchase'));
    }

    public function dataKonfigurasi()
    {
        // Ambil semua discount yang belum aktif dari detailkonfigurasi
        $shippings = DB::table('detailkonfigurasi')->where('konfigurasi_id', 4)->get();
        $payments = DB::table('detailkonfigurasi')->where('konfigurasi_id', 5)->get();
        $cogs = DB::table('detailkonfigurasi')->where('konfigurasi_id', 7)->get();

        // Debugging line to check discounts
        // dd($discounts);

        return view('purchase.konfigurasi', compact('shippings', 'payments', 'cogs'));
    }

    public function updateConfiguration(Request $request)
    {
        // Update shippings 
        if ($request->has('shippings')) {
            $checkedShippings = $request->input('shippings', []); // Ambil pengiriman yang dipilih
            $allShippings = DB::table('detailkonfigurasi')->where('konfigurasi_id', 4)->get();

            foreach ($allShippings as $shipping) {
                if ($shipping->types === 'mandatory') {
                    // Jika mandatory, tetap aktif
                    DB::table('detailkonfigurasi')
                        ->where('id', $shipping->id)
                        ->update(['statusActive' => 1]);
                } elseif (in_array($shipping->id, $checkedShippings)) {
                    // Jika pengiriman dipilih, aktifkan
                    DB::table('detailkonfigurasi')
                        ->where('id', $shipping->id)
                        ->update(['statusActive' => 1]);
                } else {
                    // Jika pengiriman tidak dipilih, reset status menjadi 0
                    DB::table('detailkonfigurasi')
                        ->where('id', $shipping->id)
                        ->update(['statusActive' => 0]);
                }
            }
        } else {
            // Jika tidak ada pengiriman yang dipilih, reset semua status menjadi 0
            DB::table('detailkonfigurasi')->where('konfigurasi_id', 4)
                ->where('types', '!=', 'mandatory') // Hanya reset yang bukan mandatory
                ->update(['statusActive' => 0]);
        }

        // Update payments
        if ($request->has('payments')) {
            $checkedPayments = $request->input('payments', []); // Ambil pembayaran yang dipilih
            $allPayments = DB::table('detailkonfigurasi')->where('konfigurasi_id', 5)->get();

            foreach ($allPayments as $payment) {
                if ($payment->types === 'mandatory') {
                    // Jika mandatory, tetap aktif
                    DB::table('detailkonfigurasi')
                        ->where('id', $payment->id)
                        ->update(['statusActive' => 1]);
                } elseif (in_array($payment->id, $checkedPayments)) {
                    // Jika pembayaran dipilih, aktifkan
                    DB::table('detailkonfigurasi')
                        ->where('id', $payment->id)
                        ->update(['statusActive' => 1]);
                } else {
                    // Jika pembayaran tidak dipilih, reset status menjadi 0
                    DB::table('detailkonfigurasi')
                        ->where('id', $payment->id)
                        ->update(['statusActive' => 0]);
                }
            }
        } else {
            // Jika tidak ada pembayaran yang dipilih, reset semua status menjadi 0
            DB::table('detailkonfigurasi')->where('konfigurasi_id', 5)
                ->where('types', '!=', 'mandatory') // Hanya reset yang bukan mandatory
                ->update(['statusActive' => 0]);
        }

        // Update cogs 
        if ($request->has('cogs')) {
            $checkedCogs = $request->input('cogs', []); // Ambil cogs yang dipilih
            $allCogs = DB::table('detailkonfigurasi')->where('konfigurasi_id', 7)->get();

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
            DB::table('detailkonfigurasi')->where('konfigurasi_id', 7)
                ->where('types', '!=', 'mandatory') // Hanya reset yang bukan mandatory
                ->update(['statusActive' => 0]);
        }

        if ($request->has('shipping_values')) {
            foreach ($request->input('shipping_values') as $id => $value) {
                DB::table('detailkonfigurasi')
                    ->where('id', $id)
                    ->update(['value' => $value]);
            }
        }
        
        return redirect()->route("purchase.konfigurasi")->with('status', "Horray, Your konfigurasi data has been updated");

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Generate invoice number
        $noNota = 'PUR' . str_pad(DB::table('purchase')->max('id') + 1, 4, '0', STR_PAD_LEFT);

        // Determine warehouse_id based on selected option  
        $warehouseId = null;
        if ($request->input('warehouse_option') === 'multi') {
            $warehouseId = $request->input('warehouse_id');
        }
        // Insert into purchase table
        $purchaseId = DB::table('purchase')->insertGetId([
            'noNota' => $noNota,
            'total_price' => $request->input('final_price'),
            'purchase_date' => $request->input('purchase_date'),
            'receive_date' => $request->input('receive_date'),
            'shipping_cost' => $request->input('shipping_cost', 0),
            'payment_methods_id' => $request->input('payment_methods_id'),
            'suppliers_id' => $request->input('supplier_id'),
            'warehouse_id' => $warehouseId,
        ]);

        $products = json_decode($request->input('products'), true);
        foreach ($products as $product) {
            DB::table('purchase_detail')->insert([
                'product_id' => $product['product_id'],
                'purchase_id' => $purchaseId,
                'subtotal_price' => $product['price'] * $product['quantity'], // Calculate subtotal price for the product
                'quantity' => $product['quantity'],
            ]);

            // If multi-warehouse is selected, update product_has_warehouse
            if ($request->input('warehouse_option') === 'multi' && $warehouseId) {
                // Check if product already exists in this warehouse
                $existingRecord = DB::table('product_has_warehouse')
                    ->where('product_id', $product['product_id'])
                    ->where('warehouse_id', $warehouseId)
                    ->first();

                if ($existingRecord) {
                    // Update existing record
                    DB::table('product_has_warehouse')
                        ->where('product_id', $product['product_id'])
                        ->where('warehouse_id', $warehouseId)
                        ->increment('stock', $product['quantity']);
                } else {
                    // Create new record
                    DB::table('product_has_warehouse')->insert([
                        'product_id' => $product['product_id'],
                        'warehouse_id' => $warehouseId,
                        'stock' => $product['quantity']
                    ]);
                }
            }
        }

        // Call the updateInventory function
        $purchase = Purchase::find($purchaseId); // Retrieve the Purchase model instance
        $purchase->updateInventory($request->input('cogs_method'), $products); // Call the updateInventory method

        return redirect()->route("purchase.index")->with('status', "Horray, Your new purchase data is already inserted");
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $purchase = Purchase::with(['supplier', 'paymentMethod', 'purchaseDetails.product'])->findOrFail($id);
        return view('purchase.detail', compact('purchase'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
