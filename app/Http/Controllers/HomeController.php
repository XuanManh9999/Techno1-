<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        try {
            $featuredProducts = Product::with('category')
                ->where('featured', true)
                ->where('status', true)
                ->limit(8)
                ->get();

            $latestProducts = Product::with('category')
                ->where('status', true)
                ->orderBy('created_at', 'desc')
                ->limit(8)
                ->get();

            $categories = Category::where('status', true)
                ->withCount(['products' => function($query) {
                    $query->where('status', true);
                }])
                ->get();

            return view('home', compact('featuredProducts', 'latestProducts', 'categories'));
        } catch (\Exception $e) {
            \Log::error('HomeController error: ' . $e->getMessage());
            return view('home', [
                'featuredProducts' => collect(),
                'latestProducts' => collect(),
                'categories' => collect()
            ]);
        }
    }
}

