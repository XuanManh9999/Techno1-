<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'product_id',
        'variant_id',
        'quantity',
    ];

    protected $casts = [
        'quantity' => 'integer',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function variant()
    {
        return $this->belongsTo(ProductVariant::class);
    }

    public function getSubtotalAttribute()
    {
        if (!$this->product) {
            return 0;
        }
        
        try {
            $price = $this->variant && $this->variant->final_price 
                ? $this->variant->final_price 
                : ($this->product->final_price ?? 0);
            return $this->quantity * $price;
        } catch (\Exception $e) {
            return 0;
        }
    }

    public function getProductNameAttribute()
    {
        if (!$this->product) {
            return 'Sản phẩm không tồn tại';
        }
        
        if ($this->variant && $this->variant->display_name) {
            return $this->variant->display_name;
        }
        return $this->product->name ?? 'Sản phẩm không tồn tại';
    }

    public function getProductImageAttribute()
    {
        if (!$this->product) {
            return null;
        }
        
        if ($this->variant && $this->variant->image) {
            return $this->variant->image;
        }
        return $this->product->image ?? null;
    }

    public function getProductPriceAttribute()
    {
        if (!$this->product) {
            return 0;
        }
        
        if ($this->variant && $this->variant->final_price) {
            return $this->variant->final_price;
        }
        return $this->product->final_price ?? 0;
    }
}

