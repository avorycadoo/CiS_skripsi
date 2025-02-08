<?php

namespace App\Http\Controllers;

use App\Models\Categories;
use App\Models\Product;
use App\Models\Product_Image;
use App\Models\ProductFifo;
use App\Models\Suppliers;
use App\Models\Warehouse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // $querybuilder = Product::all(); // ini untuk pake model
        // $image = Product_Image::all();

        // return view('product.index', [
        //     'datas' => $querybuilder,
        //     'image' => $image,
        // ]);
        $products = Product::with('productImage')->get();

        return view('product.index', [
            'datas' => $products,
        ]);
    }

    public function profitLoss()
    {
        $products = Product::select(
            'product.*',
            DB::raw('(price - cost) as profit_per_unit'),
            DB::raw('(price - cost) * stock as total_profit_potential')
        )
        ->with(['categories', 'suppliers'])
        ->get()
        ->map(function ($product) {
            $product->profit_margin_percentage = $product->cost > 0 
                ? round((($product->price - $product->cost) / $product->cost) * 100, 2)
                : 0;
            return $product;
        });

        return view('product.labaRugi', compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Categories::all(); // Ambil semua kategori
        $suppliers = Suppliers::all(); // Ambil semua kategori
        $product_image = Product_Image::all(); // Ambil semua kategori
        $warehouses = Warehouse::all();
        $products = Product::with('productImage')->get();
        // Check for uploaded image in the session
        $uploadedImage = session('uploaded_image');
        return view('product.create', compact('categories', 'suppliers', 'product_image', 'warehouses', 'products', 'uploadedImage'));
    }

    /**
     * Store a newly created resource in storage.
     */

    public function store(Request $request)
    {
        $data = new Product();
        $data->name = $request->get('product_name');
        $data->desc = $request->get('product_desc');
        $data->price = $request->get('product_price');
        $data->cost = $request->get('product_cost');
        $data->stock = $request->get('product_stock');
        $data->cogs_methods = 'fifo'; // Menggunakan FIFO tetap
        $data->minimum_stock = $request->get('product_minstock');
        $data->maksimum_retur = $request->get('product_maksretur');
        $data->status_active = '1';
        $data->categories_id = $request->get('product_category_id');
        $data->product_image_id = $request->get('product_image_id') ?: null; // Allow null if no image is selected
        $data->suppliers_id = $request->get('product_supplier_id');

        $data->save();

        // Save to product_has_warehouse
        $warehouseId = $request->get('product_warehouse_id');
        $stock = $request->get('product_stock'); // Assuming you want to set the initial stock

        if ($warehouseId) {
            DB::table('product_has_warehouse')->insert([
                'product_id' => $data->id,
                'warehouse_id' => $warehouseId,
                'stock' => $stock,
            ]);
        }

        $data->in_order_sales = 0;
        $data->save();
        
        return redirect()->route("product.index")->with('status', "Horray, Your new product data is already inserted");
    }


    public function createShipping(Request $request)
    {
        $validatedData = $request->validate([
            'product_id' => 'required|exists:product,id',
            'quantity_shipped' => 'required|integer|min:1',
        ]);

        $product = Product::find($validatedData['product_id']);
        $productFifoEntries = ProductFifo::where('product_id', $validatedData['product_id'])
            ->where('stock', '>', 0)
            ->orderBy('purchase_date', 'asc')
            ->get();

        $quantityToShip = $validatedData['quantity_shipped'];

        // Check if there are enough items in stock
        if ($product->stock < $quantityToShip) {
            return redirect()->route('sales.shipping')->with('error', 'Insufficient stock.');
        }

        // Reduce stock in FIFO order
        foreach ($productFifoEntries as $fifoEntry) {
            if ($quantityToShip <= 0) break;

            $reduceQuantity = min($fifoEntry->stock, $quantityToShip);
            $fifoEntry->stock -= $reduceQuantity;
            $fifoEntry->save();

            $quantityToShip -= $reduceQuantity;
        }

        // Update main product stock and in_order
        $product->stock -= $validatedData['quantity_shipped'];
        $product->in_order_penjualan -= $validatedData['quantity_shipped'];
        $product->save();

        return redirect()->route('sales.shipping')->with('success', 'Shipment created successfully.');
    }

    public function confirmReceipt(Product $product)
    {
        $productFifoEntries = ProductFifo::where('product_id', $product->id)
            ->where('stock', '>', 0)
            ->orderBy('purchase_date', 'asc')
            ->get();

        $quantityToReduce = $product->in_order_penjualan;

        // Reduce stock in FIFO order
        foreach ($productFifoEntries as $fifoEntry) {
            if ($quantityToReduce <= 0) break;

            $reduceQuantity = min($fifoEntry->stock, $quantityToReduce);
            $fifoEntry->stock -= $reduceQuantity;
            $fifoEntry->save();

            $quantityToReduce -= $reduceQuantity;
        }

        // Reset main product stock and in_order
        $product->stock -= $product->in_order_penjualan;
        $product->in_order_penjualan = 0;
        $product->save();

        return redirect()->route('sales.shipping')->with('success', 'Shipment receipt confirmed and stock updated.');
    }

    public function createShippingPurchase(Request $request)
    {
        $validatedData = $request->validate([
            'product_id' => 'required|exists:product,id',
            'quantity_shipped' => 'required|integer|min:1',
        ]);
    
        $product = Product::find($validatedData['product_id']);
        
        if ($product->in_order_penjualan <= 0) {
            return redirect()->route('purchase.shipping')->with('error', 'No orders pending for this product.');
        }
    
        $product->stock += $validatedData['quantity_shipped'];
        $product->in_order_penjualan -= $validatedData['quantity_shipped'];
        $product->save();
    
        return redirect()->route('purchase.shipping')->with('success', 'Shipment created successfully.');
    }
    
    public function confirmReceiptPurchase(Product $product)
    {
        if ($product->in_order_penjualan > 0) {
            $product->stock += $product->in_order_penjualan;
            $product->in_order_penjualan = 0;
            $product->save();
            
            return redirect()->route('purchase.shipping')->with('success', 'Shipment receipt confirmed and stock updated.');
        } 
        
        return redirect()->route('purchase.shipping')->with('error', 'No stock in order to confirm.');
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
        $data = Product::find($id);
        return view("product.edit", compact('data'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        $updatedData = $product;
        $updatedData->name = $request->name;
        $updatedData->desc = $request->desc;
        $updatedData->price = $request->price;
        $updatedData->cost = $request->cost;
        // $updatedData->stock = $request->stock;
        // $updatedData->cogs_methods = $request->cogs_methods;
        $updatedData->minimum_stock = $request->minimum_stock;
        $updatedData->maksimum_retur = $request->maksimum_retur;

        $updatedData->save();

        return redirect()->route("product.index")->with('status', "Horray, Your customer data is already updated");

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        try {
            //if no contraint error, then delete data. Redirect to index after it.
            $deletedData = $product;
            $deletedData->delete();
            return redirect()->route('product.index')->with('status', 'Horray ! Your data is successfully deleted !');
        } catch (\PDOException $ex) {
            // Failed to delete data, then show exception message
            $msg = "Failed to delete data ! Make sure there is no related data before deleting it";
            return redirect()->route('product.index')->with('status', $msg);
        }
    }
}
