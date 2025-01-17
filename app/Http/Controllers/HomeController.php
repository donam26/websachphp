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

        // Get unique values for dropdowns
        $types = Product::distinct()->pluck('type');
        $formalities = Product::distinct()->pluck('formality');
        $provinces = Product::distinct()->pluck('province_id');

        // Get products with pagination
        $products = $query
                         ->latest()
                         ->paginate(10)
                         ->appends($request->all());

        return view('home', compact('products', 'types', 'formalities', 'provinces'));
    }

    public function show($code)
    {
        $product = Product::where('code', $code)->firstOrFail();
        return view('products.show', compact('product'));
    }
} 