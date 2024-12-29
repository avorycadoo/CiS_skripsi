<?php

namespace App\Http\Controllers;

use App\Models\purchase;
use App\Models\purchase_detail;
use App\Models\Retur;
use App\Models\Sales_detail;
use Illuminate\Http\Request;

class PurchaseReturController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Fetch all return records with their associated customers and products
        $returs = Retur::with(['product'])->get();

        return view('purchaseRetur.index', compact('returs'));
    }

    public function detail($id)
    {
        // Fetch the sale with its details
        $retur = Retur::with(['product']) // Eager load related data
            ->findOrFail($id); // Fetch the sale or fail if not found

        return view('purchaseRetur.detail', compact('retur'));
    }

    public function updateStatus(Request $request, $id)
    {
        // Find the return record
        $retur = Retur::findOrFail($id);

        // Update the status
        $retur->status = $request->status;
        $retur->save();

        return response()->json(['success' => true]);
    }

    public function getTotalQuantity(Request $request)
    {
        $purchaseId = $request->input('purchase_id');
        $productId = $request->input('product_id');

        // Fetch total quantity from sales_detail
        $purchaseDetail = purchase_detail::where('purchase_id', $purchaseId)
            ->where('product_id', $productId)
            ->first();

        if ($purchaseDetail) {
            return response()->json([
                'quantity' => $purchaseDetail->quantity,
            ]);
        }

        return response()->json(['error' => 'purcahse detail not found'], 404);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Fetch purchase details to get products that can be returned
        $purchaseDetails = purchase_detail::with(['product'])->get();

        // Prepare an array to hold products for each sale
        $productsBySale = [];
        foreach ($purchaseDetails as $detail) {
            if (!isset($productsBySale[$detail->purchase_id])) {
                $productsBySale[$detail->purchase_id] = [];
            }
            // Include quantity in the product details
            $productsBySale[$detail->purchase_id][] = [
                'product_id' => $detail->product_id,
                'product' => $detail->product, // Assuming this includes the product name
                'quantity' => $detail->quantity // Fetch quantity from purchase_detail
            ];
        }

        // Pass the sales details, customers, and products by sale to the view
        return view('purchaseRetur.create', compact('purchaseDetails', 'productsBySale'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Fetch the purchase record to get the noNota
        $purchase = purchase::findOrFail($request->invoice_number); // Get the purchase record by ID
        $noNota = $purchase->noNota; // Get the noNota from the purchase record

        // Create a new return record
        $retur = new Retur();
        $retur->customers_id = '';
        $retur->invoice_number = $noNota; // Store the noNota instead of sales ID
        $retur->product_id = $request->product_id;
        $retur->quantity = $request->return_quantity; // Store the return quantity
        $retur->retur_desc = $request->retur_desc; // Optional description
        $retur->status = 'Return initiated'; // Set status to "Return initiated"
        $retur->type = 'pembelian'; // Use 'penjualan' or 'pembelian' as needed
        $retur->refund_amount = $request->refund_amount; // Store the refund amount

        // Save the return record to the database
        $retur->save();

        // Redirect back with a success message
        return redirect()->route('purchaseRetur.index')->with('success', 'Return has been submitted successfully.');
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
        try {
            $retur = Retur::find($id);
            $deletedData = $retur;
            $deletedData->delete();
            return redirect()->route('salesRetur.index')->with('status', 'Horray ! Your data is successfully deleted !');
        } catch (\PDOException $ex) {
            $msg = "Failed to delete data ! Make sure there is no related data before deleting it";
            return redirect()->route('salesRetur.index')->with('status', $msg);
        }
    }
}
