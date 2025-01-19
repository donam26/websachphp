<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::query();
        $city =  DB::select('SELECT id, name FROM city order by id desc');
        $ward =  DB::select('SELECT id, name FROM ward order by id desc');
        $district =  DB::select('SELECT id, name FROM district order by id ');
        $status =  DB::select('SELECT distinct status FROM products  ');

        // Filter by title/name
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('name', 'like', "%{$search}%");
            });
        }
        $query->orderBy('updated_at', 'desc','type','desc',);
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
        if ($request->filled('type_input')) {

            $query->Where('type', '=', 'văn phòng');
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
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
        return view('home', compact('products', 'types', 'formalities', 'provinces','ward','city','district','countproduct','status'));
    }

    public function show($code)
    {
        $product = Product::where('code', $code)->firstOrFail();
        return view('products.show', compact('product'));
    }
}
