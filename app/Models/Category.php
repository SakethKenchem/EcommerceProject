<?php

namespace App\Models;

use Illuminate\Support\Str;
/**
 * This file is the Category model class.
 * It extends the Illuminate\Database\Eloquent\Model class.
 * The Category model represents a category in the application.
 */
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Category extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'slug', 'image', 'is_active', 'brand_id'];

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    protected static function boot()
{
    parent::boot();

    static::creating(function ($category) {
        $category->slug = Str::slug($category->name, '-');
    });
    
}

public function brand()
{
    return $this->belongsTo(Brand::class);
}
}
