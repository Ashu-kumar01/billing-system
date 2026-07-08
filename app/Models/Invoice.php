<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    protected $fillable = [
        'customer_id', 'store_id', 'counter_id', 'user_id', 'invoice_no', 'invoice_date',
        'subtotal', 'discount', 'tax', 'total', 'paid_amount', 'due_amount', 'payment_status', 'note',
    ];

    protected function casts(): array
    {
        return ['invoice_date' => 'date'];
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    public function counter()
    {
        return $this->belongsTo(Counter::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(InvoiceItem::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function scopeSearch($query, $term)
    {
        if (! $term) {
            return $query;
        }

        return $query->where('invoice_no', 'like', "%{$term}%")
            ->orWhereHas('customer', fn ($q) => $q->where('name', 'like', "%{$term}%"));
    }
}
