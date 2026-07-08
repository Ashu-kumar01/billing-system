<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'invoice_id', 'purchase_id', 'customer_id', 'supplier_id', 'user_id',
        'amount', 'payment_method', 'transaction_id', 'payment_date', 'note',
    ];

    protected function casts(): array
    {
        return ['payment_date' => 'date'];
    }

    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }

    public function purchase()
    {
        return $this->belongsTo(Purchase::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scopeSearch($query, $term)
    {
        if (! $term) {
            return $query;
        }

        return $query->where('transaction_id', 'like', "%{$term}%")
            ->orWhereHas('customer', fn ($q) => $q->where('name', 'like', "%{$term}%"))
            ->orWhereHas('supplier', fn ($q) => $q->where('name', 'like', "%{$term}%"));
    }
}
