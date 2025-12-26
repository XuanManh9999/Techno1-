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
        try {
            // Kiểm tra stock của product hoặc variants
            if ($this->relationLoaded('variants') && $this->variants && $this->variants->count() > 0) {
                return $this->variants->sum('stock_quantity') > 0;
            }
            // Fallback: check if variants exist via query
            try {
                if ($this->variants()->count() > 0) {
                    return $this->variants()->sum('stock_quantity') > 0;
                }
            } catch (\Exception $e) {
                // If variants table doesn't exist, just check product stock
            }
        } catch (\Exception $e) {
            // If there's any error, just check product stock
        }
        return ($this->stock_quantity ?? 0) > 0;
    }

    public function hasVariants()
    {
        try {
            if ($this->relationLoaded('variants') && $this->variants) {
                return $this->variants->count() > 0;
            }
            // Fallback: check via query
            try {
                return $this->variants()->count() > 0;
            } catch (\Exception $e) {
                return false;
            }
        } catch (\Exception $e) {
            return false;
        }
    }

    public function getMinPriceAttribute()
    {
        try {
            if ($this->hasVariants()) {
                try {
                    $minVariantPrice = $this->variants()->min('sale_price') ?? $this->variants()->min('price');
                    if ($minVariantPrice) {
                        return min($this->final_price, $minVariantPrice);
                    }
                } catch (\Exception $e) {
                    // If variants query fails, just return product price
                }
            }
        } catch (\Exception $e) {
            // If hasVariants fails, just return product price
        }
        return $this->final_price;
    }

    public function getMaxPriceAttribute()
    {
        try {
            if ($this->hasVariants()) {
                try {
                    $maxVariantPrice = $this->variants()->max('sale_price') ?? $this->variants()->max('price');
                    if ($maxVariantPrice) {
                        return max($this->final_price, $maxVariantPrice);
                    }
                } catch (\Exception $e) {
                    // If variants query fails, just return product price
                }
            }
        } catch (\Exception $e) {
            // If hasVariants fails, just return product price
        }
        return $this->final_price;
    }
}

