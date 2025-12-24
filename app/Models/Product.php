<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'short_description',
        'price',
        'sale_price',
        'sku',
        'stock_quantity',
        'image',
        'gallery',
        'category_id',
        'brand_id',
        'status',
        'featured',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'sale_price' => 'decimal:2',
        'stock_quantity' => 'integer',
        'status' => 'boolean',
        'featured' => 'boolean',
        'gallery' => 'array',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function cartItems()
    {
        return $this->hasMany(Cart::class);
    }

    public function variants()
    {
        return $this->hasMany(ProductVariant::class)->where('status', true)->orderBy('sort_order');
    }

    public function defaultVariant()
    {
        return $this->hasOne(ProductVariant::class)->where('is_default', true)->where('status', true);
    }

    public function getFinalPriceAttribute()
    {
        return $this->sale_price ?? $this->price;
    }

    public function isInStock()
    {
        // Kiểm tra stock của product hoặc variants
        if ($this->variants()->count() > 0) {
            return $this->variants()->sum('stock_quantity') > 0;
        }
        return $this->stock_quantity > 0;
    }

    public function hasVariants()
    {
        return $this->variants()->count() > 0;
    }

    public function getMinPriceAttribute()
    {
        if ($this->hasVariants()) {
            $minVariantPrice = $this->variants()->min('sale_price') ?? $this->variants()->min('price');
            if ($minVariantPrice) {
                return min($this->final_price, $minVariantPrice);
            }
        }
        return $this->final_price;
    }

    public function getMaxPriceAttribute()
    {
        if ($this->hasVariants()) {
            $maxVariantPrice = $this->variants()->max('sale_price') ?? $this->variants()->max('price');
            if ($maxVariantPrice) {
                return max($this->final_price, $maxVariantPrice);
            }
        }
        return $this->final_price;
    }
}

