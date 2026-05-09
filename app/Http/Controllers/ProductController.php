<?php

namespace App\Http\Controllers;

use App\Helpers\ApiClient;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ProductController extends Controller
{
<<<<<<< HEAD

    public function index()
    {
        $products = Product::all();
=======
    public function index()
    {
        $response = ApiClient::get('/products/');
        $apiData = $response->object();
        // dd($apiData);
        $products = $apiData->data ?? [];
>>>>>>> 5ecbfe40e72a06df6b8c5d4bc73b2fd0cf3e9361

        return view('product-page', compact('products'));
    }

    public function show($id)
    {
<<<<<<< HEAD
        $product = Product::findOrFail($id);
=======
        $response = ApiClient::get("/products/{$id}");
        $apiData = $response->object();

        if ($response->failed() || $response->status() == 404) {
            abort(404);
        }

        $product = $apiData->data ?? null;
>>>>>>> 5ecbfe40e72a06df6b8c5d4bc73b2fd0cf3e9361

        return view('product-detail', compact('product'));
    }
}