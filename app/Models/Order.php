<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'order_number',
        'total_amount',
        'subtotal_amount',
        'discount_amount',
        'coupon_id',
        'status',
        'payment_status',
        'payment_method',
        'shipping_name',
        'shipping_phone',
        'shipping_address',
        'province_id',
        'district_id',
        'ward_id',
        'address_detail',
        'notes',
        'vnpay_transaction_id',
    ];

    protected $casts = [
        'total_amount' => 'decimal:2',
        'subtotal_amount' => 'decimal:2',
        'discount_amount' => 'decimal:2',
    ];

    const STATUS_PENDING = 'pending';
    const STATUS_PROCESSING = 'processing';
    const STATUS_SHIPPED = 'shipped';
    const STATUS_DELIVERED = 'delivered';
    const STATUS_CANCELLED = 'cancelled';

    const PAYMENT_STATUS_PENDING = 'pending';
    const PAYMENT_STATUS_PAID = 'paid';
    const PAYMENT_STATUS_FAILED = 'failed';

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function payment()
    {
        return $this->hasOne(Payment::class);
    }

    public function coupon()
    {
        return $this->belongsTo(Coupon::class);
    }

    public static function generateOrderNumber()
    {
        $date = date('Ymd');
        $prefix = 'ORD' . $date;
        
        // Lấy số thứ tự lớn nhất của ngày hôm nay
        $maxOrder = static::where('order_number', 'like', $prefix . '%')
            ->orderBy('order_number', 'desc')
            ->first();
        
        if ($maxOrder) {
            // Lấy số cuối cùng từ order_number hiện tại
            $lastNumber = (int) substr($maxOrder->order_number, -4);
            $nextNumber = $lastNumber + 1;
        } else {
            $nextNumber = 1;
        }
        
        $orderNumber = $prefix . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
        
        // Kiểm tra và retry nếu trùng (để tránh race condition)
        $attempts = 0;
        while (static::where('order_number', $orderNumber)->exists() && $attempts < 10) {
            $nextNumber++;
            $orderNumber = $prefix . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
            $attempts++;
        }
        
        // Nếu vẫn trùng sau 10 lần thử, thêm timestamp để đảm bảo unique
        if (static::where('order_number', $orderNumber)->exists()) {
            $orderNumber = $prefix . str_pad($nextNumber, 4, '0', STR_PAD_LEFT) . substr(time(), -3);
        }
        
        return $orderNumber;
    }
}

