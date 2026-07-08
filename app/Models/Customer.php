<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $fillable = [
        'name', 'email', 'phone', 'address', 'city', 'state', 'country',
        'zipcode', 'opening_balance', 'status',
    ];

    protected function casts(): array
    {
        return ['status' => 'boolean', 'opening_balance' => 'decimal:2'];
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function outstandingBalance(): float
    {
        return (float) $this->invoices()->sum('due_amount') + (float) $this->opening_balance;
    }

    public function scopeSearch($query, $term)
    {
        if (! $term) {
            return $query;
        }

        return $query->where(function ($q) use ($term) {
            $q->where('name', 'like', "%{$term}%")
                ->orWhere('email', 'like', "%{$term}%")
                ->orWhere('phone', 'like', "%{$term}%");
        });
    }
}
