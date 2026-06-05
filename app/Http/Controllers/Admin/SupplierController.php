<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Supplier;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    public function index(Request $request)
    {
        $query = Supplier::withCount('stockImports');

        if ($search = trim($request->input('search', ''))) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        $suppliers = $query->orderBy('name')->paginate(15)->appends($request->query());

        return view('admin.suppliers.index', compact('suppliers'));
    }

    public function store(Request $request)
    {
        $supplier = Supplier::create($this->validateData($request));

        return redirect()->route('admin.suppliers.index')
            ->with('success', 'Đã thêm nhà cung cấp "' . $supplier->name . '"');
    }

    public function update(Request $request, Supplier $supplier)
    {
        $supplier->update($this->validateData($request));

        return redirect()->route('admin.suppliers.index')
            ->with('success', 'Cập nhật nhà cung cấp thành công');
    }

    public function destroy(Supplier $supplier)
    {
        if ($supplier->stockImports()->exists()) {
            return back()->with('error', 'Không thể xoá: nhà cung cấp đã có phiếu nhập. Hãy đặt trạng thái "Ngừng hợp tác".');
        }

        $supplier->delete();

        return back()->with('success', 'Đã xoá nhà cung cấp');
    }

    private function validateData(Request $request): array
    {
        return $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:30',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string|max:255',
            'note' => 'nullable|string|max:1000',
            'status' => 'required|in:active,inactive',
        ]);
    }
}
