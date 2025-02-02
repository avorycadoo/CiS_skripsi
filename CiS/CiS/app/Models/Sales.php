<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class Sales extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $guarded = ['id'];

    public function updateInventory($cogsMethod, $products)
    {
        foreach ($products as $product) {
            if ($cogsMethod === 'fifo') {
                $quantity = $product['quantity']; // Use quantity from the sales details
                $productStocks = ProductFifo::where('product_id', $product['product_id'])
                    ->orderBy('purchase_date', 'asc')
                    ->get();

                $totalFifoStock = $productStocks->sum('stock'); // Calculate total FIFO stock

                if ($totalFifoStock < $quantity) {
                    // If FIFO stock is insufficient, throw an exception or return an error message
                    throw new \Exception("Insufficient FIFO stock for product: " . $product['product_id']);
                    // Alternatively, you can return an error message and handle it in the controller
                    // return 'Insufficient FIFO stock for product: ' . $product['product_id'];
                }
                foreach ($productStocks as $stock) {
                    if ($quantity <= 0)
                        break;

                    $decrementQuantity = min($quantity, $stock->stock);

                    if ($decrementQuantity > 0) {
                        $stock->decrement('stock', $decrementQuantity);
                        DB::table('product')
                            ->where('id', $product['product_id'])
                            ->decrement('stock', $decrementQuantity); // Decrement stock for sales

                        $quantity -= $decrementQuantity;
                    }
                }
            } elseif ($cogsMethod === 'average') {
                DB::table('product')
                    ->where('id', $product['product_id'])
                    ->decrement('stock', $product['quantity']); // Decrement stock for sales
            }
        }
    }

    public function employe()
    {
        return $this->belongsTo(Employe::class, 'employes_id', 'id'); // Corrected to belongsTo
    }

    public function salesDetail()
    {
        return $this->hasMany(Sales_detail::class, 'sales_id', 'id'); // Ensure this is correct
    }


    public function paymentMethod()
    {
        return $this->belongsTo(Payment_Methods::class, 'payment_methods_id', 'id'); // Corrected to belongsTo
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customers_id', 'id'); // Corrected to belongsTo
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($sales) {
            $lastNota = self::latest('created_at')->first();

            $nextNotaNumber = $lastNota ? (int) substr($lastNota->noNota, 3) + 1 : 1;

            $sales->noNota = 'INV' . str_pad($nextNotaNumber, 4, '0', STR_PAD_LEFT);
            $sales->date = Carbon::now();
        });
    }
}
