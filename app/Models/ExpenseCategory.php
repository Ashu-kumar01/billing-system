<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class ExpenseCategory extends Model
{
    protected $fillable = ['store_id', 'name', 'slug', 'description', 'status'];

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

    public function store()
    {
        return $this->belongsTo(Store::class);
    }
}
