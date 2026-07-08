<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    protected $fillable = [
        'name', 'company_name', 'email', 'phone', 'address', 'city',
        'state', 'country', 'zipcode', 'gst_number', 'status',
    ];

    protected function casts(): array
    {
        return ['status' => 'boolean'];
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function purchases()
    {
        return $this->hasMany(Purchase::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function outstandingBalance(): float
    {
        return (float) $this->purchases()->sum('due_amount');
    }

    public function scopeSearch($query, $term)
    {
        if (! $term) {
            return $query;
        }

        return $query->where(function ($q) use ($term) {
            $q->where('name', 'like', "%{$term}%")
                ->orWhere('company_name', 'like', "%{$term}%")
                ->orWhere('phone', 'like', "%{$term}%");
        });
    }
}
