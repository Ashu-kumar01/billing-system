<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Counter extends Model
{
    protected $fillable = ['store_id', 'name', 'code', 'location', 'status'];

    protected function casts(): array
    {
        return ['status' => 'boolean'];
    }

    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }
}
