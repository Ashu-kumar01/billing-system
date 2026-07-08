<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Unit extends Model
{
    protected $fillable = ['name', 'short_name', 'description', 'status'];

    protected function casts(): array
    {
        return ['status' => 'boolean'];
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function scopeSearch($query, $term)
    {
        if (! $term) {
            return $query;
        }

        return $query->where('name', 'like', "%{$term}%")->orWhere('short_name', 'like', "%{$term}%");
    }
}
