<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductFifo extends Model
{
    protected $table = 'product_fifo';
    public $timestamps = false;
    protected $fillable = [
        'purchase_id',
        'purchase_date',
        'price',
        'stock',
        'product_id'
    ];

    public function purchase()
    {
        return $this->belongsTo(Purchase::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}