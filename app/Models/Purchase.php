<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    protected $fillable = [
        'supplier_id', 'store_id', 'user_id', 'invoice_no', 'purchase_date',
        'subtotal', 'discount', 'tax', 'total', 'paid_amount', 'due_amount', 'note', 'status',
    ];

    protected function casts(): array
    {
        return ['purchase_date' => 'date'];
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(PurchaseItem::class);
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
            ->orWhereHas('supplier', fn ($q) => $q->where('name', 'like', "%{$term}%"));
    }
}
