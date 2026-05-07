<?php

namespace App\Http\Controllers;

use App\Helpers\ApiClient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ProductController extends Controller
{
    public function index()
    {
        $response = ApiClient::get('/products/');
        $apiData = $response->object();
        // dd($apiData);
        $products = $apiData->data ?? [];

        return view('product-page', compact('products'));
    }

    public function show($id)
    {
        $response = ApiClient::get("/products/{$id}");
        $apiData = $response->object();

        if ($response->failed() || $response->status() == 404) {
            abort(404);
        }

        $product = $apiData->data ?? null;

        return view('product-detail', compact('product'));
    }
}