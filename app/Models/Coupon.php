<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Coupon extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'description',
        'type',
        'value',
        'min_purchase_amount',
        'max_discount_amount',
        'usage_limit',
        'used_count',
        'usage_limit_per_user',
        'starts_at',
        'expires_at',
        'is_active',
    ];

    protected $casts = [
        'value' => 'decimal:2',
        'min_purchase_amount' => 'decimal:2',
        'max_discount_amount' => 'decimal:2',
        'usage_limit' => 'integer',
        'used_count' => 'integer',
        'usage_limit_per_user' => 'integer',
        'starts_at' => 'datetime',
        'expires_at' => 'datetime',
        'is_active' => 'boolean',
    ];

    const TYPE_PERCENTAGE = 'percentage';
    const TYPE_FIXED = 'fixed';

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function isActive()
    {
        if (!$this->is_active) {
            return false;
        }

        $now = Carbon::now();
        
        if ($this->starts_at && $now->lt($this->starts_at)) {
            return false;
        }

        if ($this->expires_at && $now->gt($this->expires_at)) {
            return false;
        }

        if ($this->usage_limit && $this->used_count >= $this->usage_limit) {
            return false;
        }

        return true;
    }

    public function calculateDiscount($amount)
    {
        if (!$this->isActive()) {
            return 0;
        }

        if ($this->min_purchase_amount && $amount < $this->min_purchase_amount) {
            return 0;
        }

        $discount = 0;

        if ($this->type === self::TYPE_PERCENTAGE) {
            $discount = ($amount * $this->value) / 100;
            if ($this->max_discount_amount) {
                $discount = min($discount, $this->max_discount_amount);
            }
        } else {
            $discount = min($this->value, $amount);
        }

        return round($discount, 2);
    }

    public function canBeUsedByUser($userId)
    {
        if (!$this->isActive()) {
            return false;
        }

        if ($this->usage_limit_per_user) {
            $userUsageCount = Order::where('user_id', $userId)
                ->where('coupon_id', $this->id)
                ->count();
            
            if ($userUsageCount >= $this->usage_limit_per_user) {
                return false;
            }
        }

        return true;
    }

    public function incrementUsage()
    {
        $this->increment('used_count');
    }
}
