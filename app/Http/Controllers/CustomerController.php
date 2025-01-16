<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\customer;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
class CustomerController extends Controller
{

    protected function getValidationRules($productId = null)
    {
        return [
            'name' => 'nullable|string',
            'ward_id' => 'nullable|string',
            'ward_name' => 'nullable|string',
            'district_id' => 'nullable|string',
            'district_name' => 'nullable|string',
            'province_id' => 'nullable|string',
            'province_name' => 'nullable|string',
            'house_number' => 'nullable|string',
            'formality' => 'nullable|string',
            'type' => 'nullable|string',
            'price' => 'nullable|numeric|min:0',
            'acreage' => 'nullable|numeric|min:0',
            'width' => 'nullable|numeric|min:0',
            'length' => 'nullable|numeric|min:0',
            'host_name' => 'nullable|string',
            'host_phone1' => 'nullable|string',
            'host_phone2' => 'nullable|string',
            'host_phone3' => 'nullable|string'

        ];
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = customer::query();
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
        $types = customer::distinct()->pluck('type');
        $formalities = customer::distinct()->pluck('formality');
        $provinces = customer::distinct()->pluck('province_id');

        $customer = $query->latest()->paginate(20)->appends($request->all());
        $countcustomer = $query->count();

        return view('customer.index', compact('customer', 'types', 'formalities', 'provinces','ward','city','district','countcustomer'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('customer.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
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
            customer::create($data);

            DB::commit();
            return redirect()->route('admin.customer.index')
                ->with('success', 'Thêm bất động sản thành công!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Có lỗi xảy ra: ' . $e->getMessage())
                ->withInput();
        }
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(customer $customer, Request $request)
    {
        $countproduct =0;

        $acreage = $customer->acreage;
        $length = $customer->length;
        $price = $customer->price;
        // $type = $customer->type;
        $width = $customer->width;
        $query = Product::query();
        $query->where('price', '<=', $price)
                //  ->Where('width', '<=', $width)
                //  ->Where('length', '<=', $length)
                 ;

        // echo $query->toSql();
        $products = $query->latest()->paginate(20)->appends($request->all());

        return view('admin.customer.show', compact('customer','products','countproduct'));
        // print $Request;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(customer $customer)
    {
        $city =  DB::select('SELECT id, name FROM city order by id desc');
        $ward =  DB::select('SELECT id, name FROM ward order by id desc');
        $district =  DB::select('SELECT id, name FROM district order by id ');
        return view('admin.customer.edit', compact('customer','city','ward','district'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, customer $product)
    {
        $validator = Validator::make($request->all(), $this->getValidationRules($product->id));

        // var_dump($validator);
        // return;
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
            $product->update($data);
            // var_dump($data);
            //return;
            DB::commit();
            return redirect()->route('admin.customer.index')
                ->with('success', 'Cập nhật thông tin bất động sản thành công!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Có lỗi xảy ra: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
