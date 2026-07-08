<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Category extends Model
{
    protected $fillable = ['name', 'slug', 'description', 'status'];

    protected function casts(): array
    {
        return ['status' => 'boolean'];
    }

    protected static function booted()
    {
        static::saving(function ($category) {
            if (! $category->slug) {
                $category->slug = Str::slug($category->name).'-'.Str::random(4);
            }
        });
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

        return $query->where('name', 'like', "%{$term}%");
    }
}
