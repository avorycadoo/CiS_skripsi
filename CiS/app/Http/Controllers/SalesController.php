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

        // Pass the payment methods to the view
        return view('sales.create', compact('newNumber', 'customers', 'paymentMethods', 'products', 'employees', 'activeDiscounts', 'activeShippings', 'activePayments'));

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









    public function returPenjualan()
    {

    }

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

        // Debugging line to check discounts
        // dd($discounts);

        return view('sales.konfigurasi', compact('discounts', 'shippings', 'payments'));
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

        // Save the selected payment method IDs into detailkonfigurasi
        // if ($request->has('payment_methods')) {
        //     foreach ($request->input('payment_methods') as $paymentMethodId) {
        //         // Ensure the payment method ID is valid
        //         if (DB::table('payment_methods')->where('id', $paymentMethodId)->exists()) {
        //             DB::table('detailkonfigurasi')->updateOrInsert(
        //                 ['id' => $paymentMethodId],
        //                 ['statusActive' => 1] // Set it active
        //             );
        //         }
        //     }
        // }

        return redirect()->route("sales.konfigurasi")->with('status', "Horray, Your konfigurasi data has been updated");
    }


    public function store(Request $request)
    {
        \Log::info('Final Price received:', ['final_price' => $request->input('final_price')]);


        // Generate invoice number
        $noNota = 'INV' . str_pad(DB::table('sales')->max('id') + 1, 4, '0', STR_PAD_LEFT);

        // Insert into sales table
        $salesId = DB::table('sales')->insertGetId([
            'noNota' => $noNota,
            'total_price' => $request->input('final_price'), // Calculate total price
            'date' => $request->input('sales_date'),
            'employes_id' => $request->input('sales_employes_id'),
            'payment_methods_id' => $request->input('payment_methods_id'),
            'customers_id' => $request->input('sales_cust_id'),
            'shipping_cost' => $request->input('shipping_cost', 0),
            'discount' => $request->input('sales_disc', 0),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $products = json_decode($request->input('products'), true);
        // dd($products);
        foreach ($products as $product) {
            DB::table('sales_detail')->insert([
                'product_id' => $product['product_id'],
                'sales_id' => $salesId,
                'total_quantity' => $product['quantity'],
                'total_price' => $product['price'] * $product['quantity'], // Calculate total price for the product
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Update product stock
            DB::table('product')
                ->where('id', $product['product_id'])
                ->decrement('stock', $product['quantity']); // Decrease stock by the quantity sold
        }
        return redirect()->route("sales.index")->with('status', "Horray, Your new transaction data is already inserted");
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
