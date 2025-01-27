<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Sales extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $guarded = ['id'];

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

            $nextNotaNumber = $lastNota ? (int)substr($lastNota->noNota, 3) + 1 : 1;

            $sales->noNota = 'INV' . str_pad($nextNotaNumber, 4, '0', STR_PAD_LEFT);
            $sales->date = Carbon::now();
        });
    }
}
