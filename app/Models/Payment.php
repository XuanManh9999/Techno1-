<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'payment_method',
        'amount',
        'status',
        'transaction_id',
        'vnpay_response_code',
        'vnpay_transaction_no',
        'vnpay_response_data',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'vnpay_response_data' => 'array',
    ];

    const STATUS_PENDING = 'pending';
    const STATUS_SUCCESS = 'success';
    const STATUS_FAILED = 'failed';

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}

