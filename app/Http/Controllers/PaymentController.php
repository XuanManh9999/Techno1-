<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class PaymentController extends Controller
{
    public function create($orderId)
    {
        $order = Order::where('user_id', Auth::id())
            ->where('payment_status', Order::PAYMENT_STATUS_PENDING)
            ->findOrFail($orderId);

        return view('payment.create', compact('order'));
    }

    public function vnpay(Request $request, $orderId)
    {
        $order = Order::where('user_id', Auth::id())
            ->where('payment_status', Order::PAYMENT_STATUS_PENDING)
            ->findOrFail($orderId);

        $vnp_TmnCode = config('services.vnpay.tmn_code');
        $vnp_HashSecret = config('services.vnpay.hash_secret');
        $vnp_Url = config('services.vnpay.url');
        $vnp_ReturnUrl = config('services.vnpay.return_url');

        $vnp_TxnRef = $order->order_number . '_' . time();
        $vnp_OrderInfo = 'Thanh toan don hang ' . $order->order_number;
        $vnp_OrderType = 'other';
        $vnp_Amount = $order->total_amount * 100;
        $vnp_Locale = 'vn';
        $vnp_IpAddr = $request->ip();

        $inputData = array(
            "vnp_Version" => "2.1.0",
            "vnp_TmnCode" => $vnp_TmnCode,
            "vnp_Amount" => $vnp_Amount,
            "vnp_Command" => "pay",
            "vnp_CreateDate" => date('YmdHis'),
            "vnp_CurrCode" => "VND",
            "vnp_IpAddr" => $vnp_IpAddr,
            "vnp_Locale" => $vnp_Locale,
            "vnp_OrderInfo" => $vnp_OrderInfo,
            "vnp_OrderType" => $vnp_OrderType,
            "vnp_ReturnUrl" => $vnp_ReturnUrl,
            "vnp_TxnRef" => $vnp_TxnRef,
        );

        if (isset($vnp_BankCode) && $vnp_BankCode != "") {
            $inputData['vnp_BankCode'] = $vnp_BankCode;
        }

        ksort($inputData);
        $query = "";
        $i = 0;
        $hashdata = "";
        foreach ($inputData as $key => $value) {
            if ($i == 1) {
                $hashdata .= '&' . urlencode($key) . "=" . urlencode($value);
            } else {
                $hashdata .= urlencode($key) . "=" . urlencode($value);
                $i = 1;
            }
            $query .= urlencode($key) . "=" . urlencode($value) . '&';
        }

        $vnp_Url = $vnp_Url . "?" . $query;
        if (isset($vnp_HashSecret)) {
            $vnpSecureHash = hash_hmac('sha512', $hashdata, $vnp_HashSecret);
            $vnp_Url .= 'vnp_SecureHash=' . $vnpSecureHash;
        }

        // Lưu transaction ID
        $order->update(['vnpay_transaction_id' => $vnp_TxnRef]);

        return redirect($vnp_Url);
    }

    public function vnpayReturn(Request $request)
    {
        $vnp_HashSecret = config('services.vnpay.hash_secret');
        $vnp_SecureHash = $request->vnp_SecureHash;
        $inputData = array();

        foreach ($request->all() as $key => $value) {
            if (substr($key, 0, 4) == "vnp_") {
                $inputData[$key] = $value;
            }
        }

        unset($inputData['vnp_SecureHash']);
        ksort($inputData);
        $i = 0;
        $hashData = "";
        foreach ($inputData as $key => $value) {
            if ($i == 1) {
                $hashData = $hashData . '&' . urlencode($key) . "=" . urlencode($value);
            } else {
                $hashData = $hashData . urlencode($key) . "=" . urlencode($value);
                $i = 1;
            }
        }

        $secureHash = hash_hmac('sha512', $hashData, $vnp_HashSecret);

        if ($secureHash == $vnp_SecureHash) {
            $orderNumber = explode('_', $inputData['vnp_TxnRef'])[0];
            $order = Order::where('order_number', $orderNumber)->first();

            if ($order && $inputData['vnp_ResponseCode'] == '00') {
                // Thanh toán thành công
                $order->update([
                    'payment_status' => Order::PAYMENT_STATUS_PAID,
                    'payment_method' => 'VNPAY',
                    'status' => Order::STATUS_PROCESSING,
                ]);

                Payment::create([
                    'order_id' => $order->id,
                    'payment_method' => 'VNPAY',
                    'amount' => $order->total_amount,
                    'status' => Payment::STATUS_SUCCESS,
                    'transaction_id' => $inputData['vnp_TransactionNo'],
                    'vnpay_response_code' => $inputData['vnp_ResponseCode'],
                    'vnpay_transaction_no' => $inputData['vnp_TransactionNo'],
                    'vnpay_response_data' => $inputData,
                ]);

                return redirect()->route('orders.show', $order->id)
                    ->with('success', 'Thanh toán thành công!');
            } else {
                // Thanh toán thất bại
                if ($order) {
                    $order->update([
                        'payment_status' => Order::PAYMENT_STATUS_FAILED,
                    ]);

                    Payment::create([
                        'order_id' => $order->id,
                        'payment_method' => 'VNPAY',
                        'amount' => $order->total_amount,
                        'status' => Payment::STATUS_FAILED,
                        'vnpay_response_code' => $inputData['vnp_ResponseCode'] ?? '99',
                        'vnpay_response_data' => $inputData,
                    ]);
                }

                return redirect()->route('orders.index')
                    ->with('error', 'Thanh toán thất bại. Vui lòng thử lại.');
            }
        } else {
            return redirect()->route('orders.index')
                ->with('error', 'Chữ ký không hợp lệ');
        }
    }
}

