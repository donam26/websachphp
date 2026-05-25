<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest');
    }

    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'username' => 'required|string|min:3|max:50|alpha_dash|unique:users',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'full_name' => 'required|string|max:255',
            'phone_number' => ['required', 'string', 'regex:/^[0-9]{9,11}$/'],
            'address' => 'required|string|max:500',
        ], [
            'username.alpha_dash' => 'Tên đăng nhập chỉ chấp nhận chữ, số, dấu gạch ngang và gạch dưới',
            'phone_number.regex' => 'Số điện thoại không hợp lệ (9-11 chữ số)',
            'password.confirmed' => 'Mật khẩu xác nhận không khớp',
        ]);

        $validated['password'] = Hash::make($validated['password']);
        $validated['role'] = User::ROLE_USER;

        $user = User::create($validated);

        auth()->login($user);

        return redirect()->route('home')->with('success', 'Đăng ký tài khoản thành công! Chào mừng bạn đến với BookStore.');
    }
}
