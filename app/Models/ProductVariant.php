<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductVariant extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'sku',
        'name',
        'attributes',
        'price',
        'sale_price',
        'stock_quantity',
        'image',
        'is_default',
        'status',
        'sort_order',
    ];

    protected $casts = [
        'attributes' => 'array',
        'price' => 'decimal:2',
        'sale_price' => 'decimal:2',
        'stock_quantity' => 'integer',
        'is_default' => 'boolean',
        'status' => 'boolean',
        'sort_order' => 'integer',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function getFinalPriceAttribute()
    {
        if ($this->sale_price) {
            return $this->sale_price;
        }
        if ($this->price) {
            return $this->price;
        }
        return $this->product->final_price;
    }

    public function isInStock()
    {
        return $this->stock_quantity > 0;
    }

    public function getDisplayNameAttribute()
    {
        if ($this->name) {
            return $this->name;
        }

        $attrs = [];
        if ($this->attributes) {
            foreach ($this->attributes as $key => $value) {
                $attrs[] = "{$key}: {$value}";
            }
        }

        return $this->product->name . ($attrs ? ' (' . implode(', ', $attrs) . ')' : '');
    }

    public function getAttributesStringAttribute()
    {
        if (!$this->attributes) {
            return '';
        }

        $attrs = [];
        foreach ($this->attributes as $key => $value) {
            $attrs[] = "{$key}: {$value}";
        }

        return implode(', ', $attrs);
    }
}

