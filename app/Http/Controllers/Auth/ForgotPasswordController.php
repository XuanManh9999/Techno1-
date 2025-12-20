<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ForgotPasswordController extends Controller
{
    public function showLinkRequestForm()
    {
        return view('auth.passwords.email');
    }

    public function sendResetLinkEmail(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return back()->withErrors(['email' => 'Email không tồn tại trong hệ thống.']);
        }

        // Tạo token
        $token = Str::random(64);
        
        // Lưu token vào database
        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $request->email],
            [
                'token' => $token,
                'created_at' => Carbon::now()
            ]
        );

        // Gửi email
        $resetLink = url('/password/reset/' . $token . '?email=' . urlencode($request->email));
        
        Mail::send('emails.password-reset', [
            'user' => $user,
            'resetLink' => $resetLink,
            'token' => $token
        ], function ($message) use ($user) {
            $message->to($user->email, $user->name)
                    ->subject('Đặt lại mật khẩu - Techno1');
        });

        return back()->with('status', 'Chúng tôi đã gửi link đặt lại mật khẩu đến email của bạn!');
    }
}

