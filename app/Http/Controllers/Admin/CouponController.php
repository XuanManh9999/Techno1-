<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use Illuminate\Http\Request;
use Carbon\Carbon;

class CouponController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('admin');
    }

    public function index(Request $request)
    {
        $query = Coupon::query();

        // Tìm kiếm
        if ($request->filled('search')) {
            $searchTerm = trim($request->search);
            $query->where(function($q) use ($searchTerm) {
                $q->where('code', 'like', '%' . $searchTerm . '%')
                  ->orWhere('name', 'like', '%' . $searchTerm . '%')
                  ->orWhere('description', 'like', '%' . $searchTerm . '%');
            });
        }

        // Lọc theo loại
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        // Lọc theo trạng thái
        if ($request->filled('is_active') && $request->is_active !== '') {
            $query->where('is_active', filter_var($request->is_active, FILTER_VALIDATE_BOOLEAN));
        }

        // Lọc theo ngày hết hạn
        if ($request->filled('expired')) {
            if ($request->expired == '1') {
                $query->where('expires_at', '<', now());
            } elseif ($request->expired == '0') {
                $query->where(function($q) {
                    $q->whereNull('expires_at')
                      ->orWhere('expires_at', '>=', now());
                });
            }
        }

        $coupons = $query->orderBy('created_at', 'desc')->paginate(15)->withQueryString();

        return view('admin.coupons.index', compact('coupons'));
    }

    public function create()
    {
        return view('admin.coupons.create');
    }

    public function store(Request $request)
    {
        $rules = [
            'code' => 'required|string|max:50|unique:coupons,code',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|in:percentage,fixed',
            'value' => 'required|numeric|min:0',
            'min_purchase_amount' => 'nullable|numeric|min:0',
            'max_discount_amount' => 'nullable|numeric|min:0',
            'usage_limit' => 'nullable|integer|min:1',
            'usage_limit_per_user' => 'nullable|integer|min:1',
            'starts_at' => 'nullable|date',
            'is_active' => 'nullable|boolean',
        ];

        // Add expires_at validation conditionally
        if ($request->filled('expires_at')) {
            if ($request->filled('starts_at')) {
                $rules['expires_at'] = 'nullable|date|after:starts_at';
            } else {
                $rules['expires_at'] = 'nullable|date';
            }
        } else {
            $rules['expires_at'] = 'nullable|date';
        }

        $request->validate($rules);

        try {
            Coupon::create([
                'code' => strtoupper(trim($request->code)),
                'name' => $request->name,
                'description' => $request->description ?: null,
                'type' => $request->type,
                'value' => $request->value,
                'min_purchase_amount' => $request->filled('min_purchase_amount') && $request->min_purchase_amount != '' ? $request->min_purchase_amount : null,
                'max_discount_amount' => $request->filled('max_discount_amount') && $request->max_discount_amount != '' ? $request->max_discount_amount : null,
                'usage_limit' => $request->filled('usage_limit') && $request->usage_limit != '' ? (int)$request->usage_limit : null,
                'usage_limit_per_user' => $request->filled('usage_limit_per_user') && $request->usage_limit_per_user != '' ? (int)$request->usage_limit_per_user : null,
                'starts_at' => $request->filled('starts_at') && $request->starts_at != '' ? Carbon::parse($request->starts_at) : null,
                'expires_at' => $request->filled('expires_at') && $request->expires_at != '' ? Carbon::parse($request->expires_at) : null,
                'is_active' => $request->boolean('is_active'),
            ]);

            return redirect()->route('admin.coupons.index')
                ->with('success', 'Thêm mã giảm giá thành công');
        } catch (\Exception $e) {
            return back()->withInput()
                ->with('error', 'Có lỗi xảy ra khi thêm mã giảm giá: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        $coupon = Coupon::findOrFail($id);
        return view('admin.coupons.edit', compact('coupon'));
    }

    public function update(Request $request, $id)
    {
        $coupon = Coupon::findOrFail($id);

        $rules = [
            'code' => 'required|string|max:50|unique:coupons,code,' . $id,
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|in:percentage,fixed',
            'value' => 'required|numeric|min:0',
            'min_purchase_amount' => 'nullable|numeric|min:0',
            'max_discount_amount' => 'nullable|numeric|min:0',
            'usage_limit' => 'nullable|integer|min:1',
            'usage_limit_per_user' => 'nullable|integer|min:1',
            'starts_at' => 'nullable|date',
            'is_active' => 'nullable|boolean',
        ];

        // Add expires_at validation conditionally
        if ($request->filled('expires_at')) {
            if ($request->filled('starts_at')) {
                $rules['expires_at'] = 'nullable|date|after:starts_at';
            } else {
                $rules['expires_at'] = 'nullable|date';
            }
        } else {
            $rules['expires_at'] = 'nullable|date';
        }

        $request->validate($rules);

        try {
            $coupon->update([
                'code' => strtoupper(trim($request->code)),
                'name' => $request->name,
                'description' => $request->description ?: null,
                'type' => $request->type,
                'value' => $request->value,
                'min_purchase_amount' => $request->filled('min_purchase_amount') && $request->min_purchase_amount != '' ? $request->min_purchase_amount : null,
                'max_discount_amount' => $request->filled('max_discount_amount') && $request->max_discount_amount != '' ? $request->max_discount_amount : null,
                'usage_limit' => $request->filled('usage_limit') && $request->usage_limit != '' ? (int)$request->usage_limit : null,
                'usage_limit_per_user' => $request->filled('usage_limit_per_user') && $request->usage_limit_per_user != '' ? (int)$request->usage_limit_per_user : null,
                'starts_at' => $request->filled('starts_at') && $request->starts_at != '' ? Carbon::parse($request->starts_at) : null,
                'expires_at' => $request->filled('expires_at') && $request->expires_at != '' ? Carbon::parse($request->expires_at) : null,
                'is_active' => $request->boolean('is_active'),
            ]);

            return redirect()->route('admin.coupons.index')
                ->with('success', 'Cập nhật mã giảm giá thành công');
        } catch (\Exception $e) {
            return back()->withInput()
                ->with('error', 'Có lỗi xảy ra khi cập nhật mã giảm giá: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        $coupon = Coupon::findOrFail($id);
        
        // Kiểm tra xem có đơn hàng nào sử dụng mã này không
        if ($coupon->orders()->count() > 0) {
            return back()->with('error', 'Không thể xóa mã giảm giá vì đã có đơn hàng sử dụng');
        }

        $coupon->delete();

        return redirect()->route('admin.coupons.index')
            ->with('success', 'Xóa mã giảm giá thành công');
    }
}
