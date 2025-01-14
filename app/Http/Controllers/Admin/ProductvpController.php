<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\productvp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ProductvpController extends Controller
{
    protected function getValidationRules($Productvp = null)
    {

        return [
            'title' => 'required|string|max:255',
            'content' => 'nullable|string',
            'ward_id' => 'nullable|string',
            'ward_name' => 'nullable|string',
            'district_id' => 'nullable|string',
            'district_name' => 'nullable|string',
            'province_id' => 'nullable|string',
            'province_name' => 'nullable|string',
            'street' => 'nullable|string',
            'house_number' => 'nullable|string',
            'formality' => 'nullable|string',
            'type' => 'nullable|string',
            'price' => 'nullable|numeric|min:0',
            'acreage' => 'nullable|numeric|min:0',
            'width' => 'nullable|numeric|min:0',
            'length' => 'nullable|numeric|min:0',
            'floor_number' => 'nullable|integer|min:0',
            'room_number_total' => 'nullable|integer|min:0',
            'direction' => 'nullable|string',
            'host_name' => 'nullable|string',
            'host_phone1' => 'nullable|string',
            'close_deal_type',
            'status' => 'required|in:active,inactive,pending,sold,rented'
        ];
    }

    public function index(Request $request)
    {
        $query = productvp::query();
        $ward =  DB::select('SELECT id, name FROM ward order by id desc');
        $district =  DB::select('SELECT id, name FROM district order by id ');
        // Filter by title/name
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('name', 'like', "%{$search}%");
            });
        }

        // Filter by type
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        // Filter by formality
        if ($request->filled('formality')) {
            $query->where('formality', $request->formality);
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by price range
        if ($request->filled('price_from')) {
            $query->where('price', '>=', $request->price_from);
        }
        if ($request->filled('price_to')) {
            $query->where('price', '<=', $request->price_to);
        }

        // Filter by area range
        if ($request->filled('area_from')) {
            $query->where('acreage', '>=', $request->area_from);
        }
        if ($request->filled('area_to')) {
            $query->where('acreage', '<=', $request->area_to);
        }

        // Filter by location
        if ($request->filled('province_id')) {
            $query->where('province_id', $request->province_id);

            if ($request->filled('district_id')) {
                $query->where('district_id', $request->district_id);

                if ($request->filled('ward_id')) {
                    $query->where('ward_id', $request->ward_id);
                }
            }
        }

        // Filter by features
        if ($request->filled('features')) {
            $features = $request->features;
            if (in_array('elevator', $features)) {
                $query->where('elevator', true);
            }
            if (in_array('basement', $features)) {
                $query->where('basement', true);
            }
            if (in_array('terrace', $features)) {
                $query->where('terrace', true);
            }
            if (in_array('corner', $features)) {
                $query->where('has_corner', true);
            }
        }

        // Get unique values for dropdowns
        $types = Productvp::distinct()->pluck('type');
        $formalities = Productvp::distinct()->pluck('formality');
        $provinces = Productvp::distinct()->pluck('province_id');

        $products = $query->latest()->paginate(10);

        return view('admin.productsvp.index',compact('products', 'types', 'formalities', 'provinces','ward','district'));
    }

    public function create()
    {
        return view('admin.productsvp.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), $this->getValidationRules());

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            DB::beginTransaction();

            // Lấy dữ liệu từ request
            $data = $request->all();

            // Tạo sản phẩm mới
            Productvp::create($data);

            DB::commit();
            return redirect()->route('admin.productsvp.index')
                ->with('success', 'Thêm bất động sản thành công!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Có lỗi xảy ra: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function show(productvp $product)
    {
        return view('admin.productsvp.show', compact('product'));
    }

    public function edit(productvp $product)
    {
        return view('admin.productsvp.edit', compact('product'));
    }

    public function update(Request $request, productvp $productvp)
    {
        $validator = Validator::make($request->all(), $this->getValidationRules($productvp->id));

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            DB::beginTransaction();

            // Lấy dữ liệu từ request
            $data = $request->all();

            // Cập nhật sản phẩm
            $productvp->update($data);

            DB::commit();
            return redirect()->route('admin.productvp.index')
                ->with('success', 'Cập nhật thông tin bất động sản thành công!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Có lỗi xảy ra: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function destroy(Productvp $product)
    {
        try {
            DB::beginTransaction();

            $product->delete();

            DB::commit();
            return redirect()->route('admin.productsvp.index')
                ->with('success', 'Xóa bất động sản thành công!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }
}
