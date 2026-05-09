<?php

namespace App\Http\Controllers;

use App\Helpers\ApiClient;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ProductController extends Controller
{

    public function index()
    {
        $products = Product::all();

        return view('product-page', compact('products'));
    }

    public function show($id)
    {
        $product = Product::findOrFail($id);

        return view('product-detail', compact('product'));
    }
}