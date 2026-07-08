<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    protected $fillable = [
        'store_id', 'user_id', 'title', 'description', 'amount', 'category',
        'expense_date', 'payment_method', 'reference_no',
    ];

    protected function casts(): array
    {
        return ['expense_date' => 'date'];
    }

    public const CATEGORIES = ['rent', 'salary', 'utilities', 'transport', 'maintenance', 'purchase', 'other'];

    public function store()
    {
        return $this->belongsTo(Store::class);
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

        return $query->where('title', 'like', "%{$term}%")
            ->orWhere('reference_no', 'like', "%{$term}%");
    }
}
