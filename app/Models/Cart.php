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
        $price = $this->variant ? $this->variant->final_price : $this->product->final_price;
        return $this->quantity * $price;
    }

    public function getProductNameAttribute()
    {
        if ($this->variant) {
            return $this->variant->display_name;
        }
        return $this->product->name;
    }

    public function getProductImageAttribute()
    {
        if ($this->variant && $this->variant->image) {
            return $this->variant->image;
        }
        return $this->product->image;
    }

    public function getProductPriceAttribute()
    {
        if ($this->variant) {
            return $this->variant->final_price;
        }
        return $this->product->final_price;
    }
}

