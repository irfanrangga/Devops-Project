<?php

namespace App\Http\Controllers;

use App\Helpers\ApiClient;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $categories = ['Semua', 'Fashion', 'Percetakan', 'Desain Grafis', 'Alat Tulis', 'Merchandise'];
        
        $activeCategory = $request->query('kategori', 'Semua');

        // Filter produk berdasarkan kategori
        if ($activeCategory !== 'Semua') {
            $products = Product::where('kategori', $activeCategory)->get();
        } else {
            $products = Product::all();
        }

        return view('product-page', compact('products', 'categories', 'activeCategory'));
    }

    public function show($id)
    {
        $product = Product::findOrFail($id);
        return view('product-detail', compact('product'));
    }
}