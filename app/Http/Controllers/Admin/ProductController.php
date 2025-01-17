<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    protected function getValidationRules($productId = null)
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
            'status' => 'required|in:active,inactive,pending,sold,rented',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'files.*' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx|max:5120'
        ];
    }
    public function index(Request $request)
    {
        $query = Product::query();
        $city =  DB::select('SELECT id, name FROM city order by id desc');
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
        $query->orderBy('updated_at', 'desc');
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

        $search_price =  $request->input('price_from');
        $search_price1 =  $request->input('price_to');
        $search_p = (int)$search_price; // Ép kiểu thành số nguyên
        $search_p1 = (int)$search_price1; // Ép kiểu thành số nguyên

        // Filter by price range
        // if ($request->filled('price_from')) {
        //     $query->where('price', '>=', $request->price_from);
        // }
        // if ($request->filled('price_to')) {
        //     $query->where('price', '<=', $request->price_to);
        // }
        if ($request->filled('price_from')) {
            var_dump($search_p);
            $query->Where('price', '>', $search_p);
        }
        if ($request->filled('price_to')) {
       
            $query->Where('price', '<', $search_p1);
        }
        if ($request->filled('is_hot')) {

            $query->Where('is_hot', '=', 'true');
        }

        // echo $search_price;
        // echo $search_price1;

        // Log::info('Test data:', $search_price);
        // Filter by area range
        if ($request->filled('area_from')) {
            $query->where('acreage', '>=', $request->area_from);
        }
        if ($request->filled('area_to')) {
            $query->where('acreage', '<=', $request->area_to);
        }

        // Filter by area range
        if ($request->filled('height_from')) {
            $query->where('length', '>=', $request->height_from);
        }
        if ($request->filled('height_to')) {
            $query->where('length', '<=', $request->height_to);
        }

        // Filter by area range
        if ($request->filled('width_from')) {
            $query->where('width', '>=', $request->width_from);
        }
        if ($request->filled('width_to')) {
            $query->where('width', '<=', $request->width_from);
        }
        // Filter by location
        if ($request->filled('province_id')) {
            $query->where('province_id', $request->province_id);


        }
        if ($request->filled('district_id')) {
            $query->where('district_id', $request->district_id);


        }
        if ($request->filled('houseid')) {
            $query-> where('house_number', 'like', $request->houseid);
        }

        if ($request->filled('ward_id')) {
            $query->where('ward_id', $request->ward_id);
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
        $types = Product::distinct()->pluck('type');
        $formalities = Product::distinct()->pluck('formality');
        $provinces = Product::distinct()->pluck('province_id');

        $products = $query->latest()->paginate(20)->appends($request->all());
        $countproduct = $query->count();
        return view('admin.products.index', compact('products', 'types', 'formalities', 'provinces','ward','city','district','countproduct'));
    }

    public function create()
    {
        return view('admin.products.create');
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

            // Tạo mã code tự động
            $code = 'BDS' . date('ymd') . str_pad(Product::count() + 1, 4, '0', STR_PAD_LEFT);

            // Lấy dữ liệu từ request và thêm các giá trị mặc định
            $data = array_merge($request->all(), [
                'code' => $code,
                'status' => $request->status ?? 'pending',
                'is_hot' => $request->has('is_hot'),
                'show_in_web' => true,
            ]);

            // Tạo sản phẩm mới
            $product = Product::create($data);

            // Xử lý upload ảnh
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $image) {
                    $path = $image->store('products/images', 'public');
                    $product->images()->create([
                        'path' => $path,
                        'is_primary' => false
                    ]);
                }
                // Đặt ảnh đầu tiên làm ảnh chính
                if ($firstImage = $product->images()->first()) {
                    $firstImage->update(['is_primary' => true]);
                }
            }

            // Xử lý upload file
            if ($request->hasFile('files')) {
                foreach ($request->file('files') as $file) {
                    $path = $file->store('products/files', 'public');
                    $product->files()->create([
                        'name' => $file->getClientOriginalName(),
                        'path' => $path,
                        'type' => $file->getClientMimeType(),
                        'size' => $file->getSize()
                    ]);
                }
            }

            DB::commit();
            return redirect()->route('admin.products.index')
                ->with('success', 'Thêm bất động sản thành công!');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Lỗi khi tạo sản phẩm: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Có lỗi xảy ra: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function show($id)
    {
        $product = Product::findOrFail($id);
        return view('admin.products.show', compact('product'));
    }

    public function edit($id)
    {
        $product = Product::findOrFail($id);
        return view('admin.products.edit', compact('product'));
    }

    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);
        $validator = Validator::make($request->all(), $this->getValidationRules($id));

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            DB::beginTransaction();

            // Cập nhật thông tin sản phẩm
            $product->update($request->all());

            // Xử lý upload ảnh mới nếu có
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $image) {
                    $path = $image->store('products/images', 'public');
                    $product->images()->create([
                        'path' => $path,
                        'is_primary' => false
                    ]);
                }
                // Nếu chưa có ảnh chính, đặt ảnh đầu tiên làm ảnh chính
                if (!$product->images()->where('is_primary', true)->exists()) {
                    $product->images()->first()->update(['is_primary' => true]);
                }
            }

            // Xử lý upload file mới nếu có
            if ($request->hasFile('files')) {
                foreach ($request->file('files') as $file) {
                    $path = $file->store('products/files', 'public');
                    $product->files()->create([
                        'name' => $file->getClientOriginalName(),
                        'path' => $path,
                        'type' => $file->getClientMimeType(),
                        'size' => $file->getSize()
                    ]);
                }
            }

            DB::commit();
            return redirect()->route('admin.products.index')
                ->with('success', 'Cập nhật thông tin bất động sản thành công!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Có lỗi xảy ra: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function deleteImage($id)
    {
        try {
            DB::beginTransaction();

            $image = \App\Models\ProductImage::findOrFail($id);
            $product = $image->product;

            // Xóa file ảnh
            Storage::disk('public')->delete($image->path);

            // Xóa record trong database
            $image->delete();

            // Nếu ảnh bị xóa là ảnh chính và còn ảnh khác, đặt ảnh đầu tiên làm ảnh chính
            if ($image->is_primary && $product->images()->exists()) {
                $product->images()->first()->update(['is_primary' => true]);
            }

            DB::commit();
            return redirect()->back()->with('success', 'Xóa ảnh thành công!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Có lỗi xảy ra khi xóa ảnh: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            DB::beginTransaction();
            
            $product = Product::findOrFail($id);

            // Xóa ảnh
            foreach ($product->images as $image) {
                Storage::disk('public')->delete($image->path);
            }

            // Xóa file
            foreach ($product->files as $file) {
                Storage::disk('public')->delete($file->path);
            }

            // Xóa sản phẩm (các bản ghi trong bảng product_images và product_files sẽ tự động bị xóa do có onDelete('cascade'))
            $product->delete();

            DB::commit();
            return redirect()->route('admin.products.index')
                ->with('success', 'Xóa bất động sản thành công!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }
}
