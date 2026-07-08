<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Store extends Model
{
    protected $fillable = [
        'name', 'code', 'email', 'phone', 'address', 'city', 'state',
        'country', 'zipcode', 'gst_number', 'subscription_plan', 'status',
    ];

    protected function casts(): array
    {
        return ['status' => 'boolean'];
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function counters()
    {
        return $this->hasMany(Counter::class);
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }

    public function purchases()
    {
        return $this->hasMany(Purchase::class);
    }

    public function expenses()
    {
        return $this->hasMany(Expense::class);
    }
}
