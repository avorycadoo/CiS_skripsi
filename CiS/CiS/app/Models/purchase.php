<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class purchase extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $guarded = [];
    protected $table = 'purchase';

    public function updateInventory($cogsMethod, $products)
    {
        // Get the COGS method name from detailkonfigurasi
        $cogsMethodName = DB::table('detailkonfigurasi')
            ->where('id', $cogsMethod)
            ->value('name');

        foreach ($products as $product) {
            if (strtolower($cogsMethodName) === 'fifo') {
                // Create a new FIFO entry for this purchase
                ProductFifo::create([
                    'purchase_id' => $this->id,
                    'purchase_date' => $this->purchase_date,
                    'price' => $product['price'],
                    'stock' => $product['quantity'],
                    'product_id' => $product['product_id']
                ]);

                // Update product stock
                DB::table('product')
                    ->where('id', $product['product_id'])
                    ->increment('stock', $product['quantity']);

            } elseif (strtolower($cogsMethodName) === 'average') {
                // Average Logic
                DB::table('product')
                    ->where('id', $product['product_id'])
                    ->increment('stock', $product['quantity']);
            }
        }
    }

    public function purchaseDetails()
    {
        return $this->hasMany(purchase_detail::class, 'purchase_id', 'id');
    }

    public function paymentMethod()
    {
        return $this->belongsTo(Payment_Methods::class, 'payment_methods_id', 'id');
    }

    public function supplier()
    {
        return $this->belongsTo(Suppliers::class, 'suppliers_id', 'id');
    }

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class, 'warehouse_id', 'id');
    }
}