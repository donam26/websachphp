<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query()->withCount('orders');

        if ($search = trim($request->input('search', ''))) {
            $query->where(function ($q) use ($search) {
                $q->where('username', 'like', "%{$search}%")
                  ->orWhere('full_name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone_number', 'like', "%{$search}%");
            });
        }

        if ($role = $request->input('role')) {
            $query->where('role', $role);
        }

        $users = $query->latest()->paginate(15)->appends($request->query());

        return view('admin.users.index', compact('users'));
    }

    public function show(User $user)
    {
        $user->loadCount('orders');
        $orders = $user->orders()->latest()->take(10)->get();

        return view('admin.users.show', compact('user', 'orders'));
    }

    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'username' => 'required|string|max:255|unique:users',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'full_name' => 'required|string|max:255',
            'phone_number' => ['required', 'string', 'regex:/^[0-9]{9,11}$/'],
            'address' => 'required|string|max:500',
            'role' => 'required|in:user,admin',
        ]);

        User::create([
            'username' => $validated['username'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'full_name' => $validated['full_name'],
            'phone_number' => $validated['phone_number'],
            'address' => $validated['address'],
            'role' => $validated['role'],
        ]);

        return redirect()->route('admin.users.index')->with('success', 'Thêm người dùng mới thành công');
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'username' => ['required', 'string', 'max:255', Rule::unique('users')->ignore($user->id)],
            'email' => ['required', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'password' => 'nullable|string|min:8',
            'full_name' => 'required|string|max:255',
            'phone_number' => ['required', 'string', 'regex:/^[0-9]{9,11}$/'],
            'address' => 'required|string|max:500',
            'role' => 'required|in:user,admin',
        ]);

        if ($user->id === auth()->id() && $validated['role'] !== User::ROLE_ADMIN) {
            return back()->with('error', 'Bạn không thể tự hạ quyền admin của chính mình');
        }

        $data = [
            'username' => $validated['username'],
            'email' => $validated['email'],
            'full_name' => $validated['full_name'],
            'phone_number' => $validated['phone_number'],
            'address' => $validated['address'],
            'role' => $validated['role'],
        ];

        if (!empty($validated['password'])) {
            $data['password'] = Hash::make($validated['password']);
        }

        $user->update($data);

        return redirect()->route('admin.users.show', $user)
            ->with('success', 'Cập nhật thông tin người dùng thành công');
    }

    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Bạn không thể tự xoá tài khoản của chính mình');
        }

        if ($user->isAdmin()) {
            return back()->with('error', 'Không thể xoá tài khoản admin');
        }

        if ($user->orders()->exists()) {
            return back()->with('error', 'Không thể xoá người dùng đã có đơn hàng');
        }

        $user->delete();

        return back()->with('success', 'Xoá người dùng thành công');
    }
}
