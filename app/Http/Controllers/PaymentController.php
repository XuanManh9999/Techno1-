<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
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
        try {
            $order = Order::where('user_id', Auth::id())
                ->where('payment_status', Order::PAYMENT_STATUS_PENDING)
                ->findOrFail($orderId);

            $vnp_TmnCode = config('services.vnpay.tmn_code');
            // Hỗ trợ cả secret_key và hash_secret
            $vnp_HashSecret = config('services.vnpay.secret_key') ?: config('services.vnpay.hash_secret');
            $vnp_Url = config('services.vnpay.url');
            $vnp_ReturnUrl = config('services.vnpay.return_url');

            // Kiểm tra config VNPAY - giống code mẫu thành công
            if (!$vnp_TmnCode || !$vnp_HashSecret || !$vnp_Url || !$vnp_ReturnUrl) {
                Log::error('VNPay configuration is missing', [
                    'tmn' => (bool) $vnp_TmnCode,
                    'hash' => (bool) $vnp_HashSecret,
                    'url' => (bool) $vnp_Url,
                    'return_url' => (bool) $vnp_ReturnUrl,
                ]);

                return redirect()->route('payment.create', $orderId)
                    ->with('error', 'VNPay chưa được cấu hình đúng. Vui lòng liên hệ quản trị viên.');
            }

            if ($order->total_amount <= 0) {
                return redirect()->route('payment.create', $orderId)
                    ->with('error', 'Không thể khởi tạo thanh toán cho đơn hàng này.');
            }

            // Tạo transaction reference - giống code mẫu thành công
            $vnp_TxnRef = 'ORD' . $order->id . '_' . time();
            $vnp_OrderInfo = 'Thanh toan don hang ' . $order->order_number;
            $vnp_OrderType = 'other';
            $vnp_Amount = (int)($order->total_amount * 100); // VNPay sử dụng đơn vị đồng * 100
            $vnp_Locale = 'vn';
            $vnp_IpAddr = $request->ip();
            $vnp_CreateDate = date('YmdHis');

            // Chuẩn bị dữ liệu đầu vào
            $inputData = [
                "vnp_Version" => "2.1.0",
                "vnp_TmnCode" => $vnp_TmnCode,
                "vnp_Amount" => $vnp_Amount,
                "vnp_Command" => "pay",
                "vnp_CreateDate" => $vnp_CreateDate,
                "vnp_CurrCode" => "VND",
                "vnp_IpAddr" => $vnp_IpAddr,
                "vnp_Locale" => $vnp_Locale,
                "vnp_OrderInfo" => $vnp_OrderInfo,
                "vnp_OrderType" => $vnp_OrderType,
                "vnp_ReturnUrl" => $vnp_ReturnUrl,
                "vnp_TxnRef" => $vnp_TxnRef,
            ];

            // Sắp xếp mảng theo key - giống code mẫu thành công
            ksort($inputData);
            $hashdata = $this->buildHashData($inputData);
            $vnpSecureHash = hash_hmac('sha512', $hashdata, $vnp_HashSecret);
            $vnp_Url = $vnp_Url . '?' . http_build_query($inputData) . '&vnp_SecureHash=' . $vnpSecureHash;

            // Log để debug - chỉ log trong development
            if (config('app.debug')) {
                Log::info('VNPAY Payment Request Details', [
                    'order_id' => $order->id,
                    'order_number' => $order->order_number,
                    'amount' => $vnp_Amount,
                    'txn_ref' => $vnp_TxnRef,
                    'tmn_code' => $vnp_TmnCode,
                    'return_url' => $vnp_ReturnUrl,
                    'input_data' => $inputData,
                    'hash_data' => $hashdata,
                    'secure_hash' => $vnpSecureHash,
                    'payment_url' => $vnp_Url,
                ]);
            }

            // Lưu payment record - giống code mẫu thành công
            Payment::updateOrCreate(
                ['order_id' => $order->id],
                [
                    'amount' => $order->total_amount,
                    'payment_method' => 'VNPAY',
                    'transaction_id' => $vnp_TxnRef,
                    'status' => Payment::STATUS_PENDING,
                    'vnpay_response_data' => null,
                ]
            );

            return redirect($vnp_Url);
        } catch (\Exception $e) {
            Log::error('VNPAY payment error: ' . $e->getMessage(), [
                'order_id' => $orderId,
                'user_id' => Auth::id(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect()->route('payment.create', $orderId)
                ->with('error', 'Có lỗi xảy ra khi khởi tạo thanh toán VNPAY: ' . $e->getMessage());
        }
    }

    public function vnpayReturn(Request $request)
    {
        // Hỗ trợ cả secret_key và hash_secret
        $vnp_HashSecret = config('services.vnpay.secret_key') ?: config('services.vnpay.hash_secret');
        $vnp_SecureHash = $request->vnp_SecureHash;

        $inputData = [];
        foreach ($request->all() as $key => $value) {
            if (Str::startsWith($key, 'vnp_')) {
                $inputData[$key] = $value;
            }
        }

        unset($inputData['vnp_SecureHash']);
        ksort($inputData);
        $hashData = $this->buildHashData($inputData);
        $secureHash = hash_hmac('sha512', $hashData, $vnp_HashSecret);

        // Kiểm tra hash - giống code mẫu thành công
        if ($secureHash !== $vnp_SecureHash) {
            return redirect()->route('home')
                ->with('error', 'Chữ ký không hợp lệ.');
        }

        // Tìm payment bằng transaction_id - giống code mẫu thành công
        $payment = Payment::where('transaction_id', $request->vnp_TxnRef)->first();

        if (!$payment) {
            Log::warning('Không tìm thấy bản ghi thanh toán cho mã giao dịch.', ['txn' => $request->vnp_TxnRef]);
            return redirect()->route('home')
                ->with('error', 'Không tìm thấy giao dịch tương ứng.');
        }

        $order = $payment->order;

            // Xử lý thanh toán thành công - giống code mẫu thành công
            if ($request->vnp_ResponseCode === '00') {
                $payment->update([
                    'status' => Payment::STATUS_SUCCESS,
                    'vnpay_response_data' => $request->all(),
                ]);

                $order->update([
                    'payment_status' => Order::PAYMENT_STATUS_PAID,
                    'payment_method' => 'VNPAY',
                    'status' => Order::STATUS_PROCESSING,
                ]);

                if (Auth::check() && Auth::id() == $order->user_id) {
                    return redirect()
                        ->route('orders.show', $order->id)
                        ->with('success', 'Thanh toán thành công!');
                }

                return redirect()
                    ->route('login')
                    ->with('success', 'Thanh toán thành công! Vui lòng đăng nhập để xem đơn hàng.');
            }

            // Xử lý thanh toán thất bại - giống code mẫu thành công
            $payment->update([
                'status' => Payment::STATUS_FAILED,
                'vnpay_response_data' => $request->all(),
            ]);

            $order->update([
                'payment_status' => Order::PAYMENT_STATUS_PENDING,
            ]);

            $errorMessage = $request->vnp_Message ?? $request->vnp_ResponseCode ?? 'Lỗi không xác định';

            if (Auth::check() && Auth::id() == $order->user_id) {
                return redirect()
                    ->route('payment.create', $order->id)
                    ->with('error', 'Thanh toán thất bại: ' . $errorMessage);
            }

            return redirect()
                ->route('login')
                ->with('error', 'Thanh toán thất bại: ' . $errorMessage);
    }

    /**
     * Build hash data for VNPAY
     * Format: key1=value1&key2=value2&key3=value3
     * Giống code mẫu thành công - không skip null/empty values
     */
    protected function buildHashData(array $inputData): string
    {
        $hashData = '';
        foreach ($inputData as $key => $value) {
            $hashData .= ($hashData ? '&' : '') . urlencode($key) . '=' . urlencode($value);
        }
        return $hashData;
    }
}

