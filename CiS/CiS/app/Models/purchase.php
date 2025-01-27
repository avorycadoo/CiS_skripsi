<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class purchase extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'purchase';

    public function purchaseDetails()
    {
        return $this->hasMany(purchase_detail::class, 'purchase_id', 'id'); // One purchase can have many purchase details
    }

    public function paymentMethod()
    {
        return $this->belongsTo(Payment_Methods::class, 'payment_methods_id', 'id'); // Each purchase belongs to one payment method
    }

    public function supplier()
    {
        return $this->belongsTo(Suppliers::class, 'suppliers_id', 'id'); // Each purchase belongs to one supplier
    }

    public function warehouse()  
    {  
        return $this->belongsTo(Warehouse::class, 'warehouse_id', 'id'); // Each purchase belongs to one warehouse  
    } 
}
