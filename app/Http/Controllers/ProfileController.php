<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $recentOrders = $user->orders()->latest()->take(5)->get();

        return view('profile.index', compact('user', 'recentOrders'));
    }

    public function update(Request $request)
    {
        $user = auth()->user();

        $validated = $request->validate([
            'full_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'phone_number' => ['required', 'string', 'regex:/^[0-9]{9,11}$/'],
            'address' => ['required', 'string', 'max:500'],
            'current_password' => ['nullable', 'string'],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
        ], [
            'phone_number.regex' => 'Số điện thoại không hợp lệ (9-11 chữ số)',
            'password.confirmed' => 'Mật khẩu xác nhận không khớp',
        ]);

        if (!empty($validated['password'])) {
            if (empty($validated['current_password']) || !Hash::check($validated['current_password'], $user->password)) {
                return back()
                    ->withErrors(['current_password' => 'Mật khẩu hiện tại không đúng'])
                    ->withInput();
            }
            $user->password = Hash::make($validated['password']);
        }

        $user->full_name = $validated['full_name'];
        $user->email = $validated['email'];
        $user->phone_number = $validated['phone_number'];
        $user->address = $validated['address'];
        $user->save();

        return back()->with('success', 'Cập nhật thông tin thành công');
    }
}
