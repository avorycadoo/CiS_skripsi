<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Sales extends Model
{
    use HasFactory;
    use SoftDeletes;

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

}
