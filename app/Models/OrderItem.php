<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'product_id',
        'variant_id',
        'variant_attributes',
        'quantity',
        'price',
        'subtotal',
    ];

    protected $casts = [
        'quantity' => 'integer',
        'price' => 'decimal:2',
        'subtotal' => 'decimal:2',
        'variant_attributes' => 'array',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function variant()
    {
        return $this->belongsTo(ProductVariant::class);
    }

    public function getProductNameAttribute()
    {
        if ($this->variant) {
            return $this->variant->display_name;
        }
        if ($this->variant_attributes) {
            $attrs = [];
            foreach ($this->variant_attributes as $key => $value) {
                $attrs[] = "{$key}: {$value}";
            }
            return $this->product->name . ($attrs ? ' (' . implode(', ', $attrs) . ')' : '');
        }
        return $this->product->name;
    }
}

