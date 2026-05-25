<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Discount;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class DiscountController extends Controller
{
    public function index(Request $request)
    {
        $query = Discount::query();

        if ($search = trim($request->input('search', ''))) {
            $query->where(function ($q) use ($search) {
                $q->where('code', 'like', "%{$search}%")
                  ->orWhere('name', 'like', "%{$search}%");
            });
        }

        if ($status = $request->input('status')) {
            if ($status === 'active') {
                $query->active();
            } elseif ($status === 'inactive') {
                $query->where('is_active', false);
            } elseif ($status === 'expired') {
                $query->where('end_date', '<', now());
            }
        }

        $discounts = $query->latest()->paginate(15)->appends($request->query());

        return view('admin.discounts.index', compact('discounts'));
    }

    public function create()
    {
        return view('admin.discounts.create');
    }

    public function store(Request $request)
    {
        $validated = $this->validateInput($request);

        $validated['code'] = Str::upper(trim($validated['code']));
        $validated['is_active'] = $request->boolean('is_active');
        $validated['used_count'] = 0;

        Discount::create($validated);

        return redirect()->route('admin.discounts.index')->with('success', 'Thêm mã giảm giá thành công');
    }

    public function edit(Discount $discount)
    {
        return view('admin.discounts.edit', compact('discount'));
    }

    public function update(Request $request, Discount $discount)
    {
        $validated = $this->validateInput($request, $discount->id);

        $validated['code'] = Str::upper(trim($validated['code']));
        $validated['is_active'] = $request->boolean('is_active');

        $discount->update($validated);

        return redirect()->route('admin.discounts.index')->with('success', 'Cập nhật mã giảm giá thành công');
    }

    public function destroy(Discount $discount)
    {
        $discount->delete();
        return redirect()->route('admin.discounts.index')->with('success', 'Xoá mã giảm giá thành công');
    }

    private function validateInput(Request $request, ?int $ignoreId = null): array
    {
        $rules = [
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:discounts,code' . ($ignoreId ? ",{$ignoreId}" : ''),
            'description' => 'nullable|string|max:500',
            'type' => 'required|in:fixed,percent',
            'value' => 'required|numeric|min:0',
            'min_order_amount' => 'required|numeric|min:0',
            'max_discount_amount' => 'nullable|numeric|min:0',
            'usage_limit' => 'nullable|integer|min:1',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ];

        $validated = $request->validate($rules);

        if ($validated['type'] === 'percent' && $validated['value'] > 100) {
            abort(redirect()->back()->withInput()->withErrors(['value' => 'Giá trị giảm theo phần trăm không được vượt quá 100%']));
        }

        return $validated;
    }
}
